<?php

namespace App\Services;

use App\Models\Proposal;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\ModelManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AnalyzeBid
{
    private ?SVR $scoreModel = null;

    public function analyze(Proposal $proposal): array
    {
        $this->loadOrTrainModel();
        $features = $this->extractFeatures($proposal);
        $score = $this->scoreModel->predict($features['data']);
        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'features' => $features['data'],
            'notes' => $features['notes'],
        ];
    }

    private function loadOrTrainModel(): void
    {
        $modelManager = new ModelManager();
        $scoreModelPath = storage_path('app/models/score_model.phpml');

        $modelsDir = storage_path('app/models');
        if (!file_exists($modelsDir)) {
            mkdir($modelsDir, 0775, true);
            Log::info("Created models directory at {$modelsDir}");
        }

        if (file_exists($scoreModelPath)) {
            $scoreModel = $modelManager->restoreFromFile($scoreModelPath);
            if ($scoreModel instanceof SVR) {
                $this->scoreModel = $scoreModel;
                Log::info('Loaded existing score model');
                return;
            } else {
                throw new \Exception("Loaded score model is not an SVR instance.");
            }
        }

        $trainingData = $this->getTrainingData();
        if (empty($trainingData)) {
            throw new \Exception('No training data available to train model.');
        }

        $X = array_map(function ($bid) {
            return [
                $this->normalizeFeature($bid['pricing'], 10000, 10000000),
                $this->normalizeFeature($bid['delivery_days'], 0, 360),
                $this->normalizeFeature($bid['valid_days'], 0, 180),
            ];
        }, $trainingData);

        $yScore = array_column($trainingData, 'score');

        $this->scoreModel = new SVR(Kernel::LINEAR);
        $this->scoreModel->train($X, $yScore);
        $modelManager->saveToFile($this->scoreModel, $scoreModelPath);
        Log::info("Saved score model to {$scoreModelPath}");

        Log::info('Trained and saved new score model', ['training_samples' => count($trainingData)]);
    }

    private function getTrainingData(): array
    {
        $proposals = Proposal::whereNotNull('ai_score')
            ->get(['pricing', 'delivery_timeline', 'valid_until', 'ai_score'])
            ->toArray();

        if (empty($proposals)) {
            Log::warning('No historical data found, using fallback training data.');
            return $this->getFallbackTrainingData();
        }

        $now = Carbon::now();
        return array_map(function ($proposal) use ($now) {
            $price = (float) str_replace(['₱', ','], '', $proposal['pricing'] ?? '₱0');
            $deliveryDays = $proposal['delivery_timeline']
                ? $now->diffInDays(Carbon::parse($proposal['delivery_timeline']), false)
                : 0;
            $validDays = $proposal['valid_until']
                ? $now->diffInDays(Carbon::parse($proposal['valid_until']), false)
                : 0;

            return [
                'pricing' => $price,
                'delivery_days' => max(0, $deliveryDays),
                'valid_days' => max(0, $validDays),
                'score' => $proposal['ai_score'],
            ];
        }, $proposals);
    }

    private function getFallbackTrainingData(): array
    {
        return [
            ['pricing' => '₱50000', 'delivery_days' => 30, 'valid_days' => 60, 'score' => 85],
            ['pricing' => '₱2000000', 'delivery_days' => 180, 'valid_days' => 10, 'score' => 40],
            ['pricing' => '₱100000', 'delivery_days' => 60, 'valid_days' => 90, 'score' => 75],
            ['pricing' => '₱25000', 'delivery_days' => 15, 'valid_days' => 120, 'score' => 90],
            ['pricing' => '₱1500000', 'delivery_days' => 240, 'valid_days' => 30, 'score' => 50],
            ['pricing' => '₱75000', 'delivery_days' => 45, 'valid_days' => 45, 'score' => 80],
            ['pricing' => '₱5000000', 'delivery_days' => 90, 'valid_days' => 5, 'score' => 30],
            ['pricing' => '₱200000', 'delivery_days' => 120, 'valid_days' => 60, 'score' => 65],
            ['pricing' => '₱30000', 'delivery_days' => 20, 'valid_days' => 180, 'score' => 92],
            ['pricing' => '₱800000', 'delivery_days' => 300, 'valid_days' => 15, 'score' => 45],
        ];
    }

    private function extractFeatures(Proposal $proposal): array
    {
        $now = Carbon::now();
        $notes = [];

        $price = 0.0;
        if ($proposal->pricing) {
            try {
                $price = (float) str_replace(['₱', ','], '', $proposal->pricing);
                if ($price < 0) {
                    $notes[] = 'Pricing is negative.';
                    $price = 0.0;
                } elseif ($price < 10000 || $price > 10000000) {
                    $notes[] = "Pricing ₱{$price} is outside typical range (₱10000-₱10000000).";
                }
            } catch (\Exception $e) {
                $notes[] = 'Invalid pricing format.';
            }
        } else {
            $notes[] = 'Pricing missing.';
        }

        $deliveryDays = 0;
        if ($proposal->delivery_timeline) {
            try {
                $deliveryDate = Carbon::parse($proposal->delivery_timeline);
                $deliveryDays = max(0, $now->diffInDays($deliveryDate, false));
                if ($deliveryDays < 0) $notes[] = 'Delivery timeline is in the past.';
                elseif ($deliveryDays > 360) $notes[] = 'Delivery timeline exceeds 1 year.';
            } catch (\Exception $e) {
                $notes[] = 'Invalid delivery timeline format.';
            }
        } else {
            $notes[] = 'Delivery timeline missing.';
        }

        $validDays = 0;
        if ($proposal->valid_until) {
            try {
                $validDate = Carbon::parse($proposal->valid_until);
                $validDays = max(0, $now->diffInDays($validDate, false));
                if ($validDays < 0) $notes[] = 'Proposal has expired.';
                elseif ($validDays < 5) $notes[] = 'Validity period is unusually short.';
            } catch (\Exception $e) {
                $notes[] = 'Invalid valid_until format.';
            }
        } else {
            $notes[] = 'Valid until missing.';
        }

        $normalizedData = [
            $this->normalizeFeature($price, 10000, 10000000),
            $this->normalizeFeature($deliveryDays, 0, 360),
            $this->normalizeFeature($validDays, 0, 180),
        ];

        return [
            'data' => $normalizedData,
            'notes' => $notes,
        ];
    }

    private function normalizeFeature(float $value, float $min, float $max): float
    {
        if ($max === $min) return 0.5;
        $normalized = ($value - $min) / ($max - $min);
        return max(0, min(1, $normalized));
    }
}
