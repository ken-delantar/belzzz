<?php
// app/Jobs/AnalyzeContract.php
namespace App\Jobs;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Process;

class AnalyzeContract implements ShouldQueue
{
    use Dispatchable, Queueable;
    protected $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function handle()
    {
        $filePath = storage_path('app/public/' . $this->contract->file_path);
        $scriptPath = base_path('scripts/analyze_contract.py');
        $pythonPath = env('PYTHON_PATH', '/usr/bin/python3');
        $command = "\"{$pythonPath}\" \"{$scriptPath}\" \"{$filePath}\"";
        $result = Process::run($command);

        if ($result->successful()) {
            $output = json_decode($result->output(), true);
            $this->contract->update([
                'status' => $output['is_fraud'] ? 'flagged' : 'approved',
                'fraud_notes' => $output['notes'] ?? null,
            ]);
        } else {
            $this->contract->update([
                'status' => 'error',
                'fraud_notes' => 'Analysis failed: ' . $result->errorOutput(),
            ]);
        }
    }
}
