<?php

namespace App\Jobs;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Process;

class AnalyzeProposal implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $proposal;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

    public function handle()
    {
        $filePath = storage_path('app/private/' . $this->proposal->documentation_path); // Adjusted for 'private' disk
        $scriptPath = base_path('scripts/analyze_contract.py'); // Reuse your existing script
        $pythonPath = 'C:\Python312\python.exe'; // Adjust as needed for your environment
        $command = "\"{$pythonPath}\" \"{$scriptPath}\" \"{$filePath}\"";
        $result = Process::run($command);

        if ($result->successful()) {
            $output = json_decode($result->output(), true);
            $this->proposal->update([
                'status' => $output['is_fraud'] ? 'flagged' : 'approved',
                'fraud_notes' => $output['notes'] ?? null,
            ]);
        } else {
            $this->proposal->update([
                'status' => 'error',
                'fraud_notes' => 'Analysis failed: ' . $result->errorOutput(),
            ]);
        }
    }
}
