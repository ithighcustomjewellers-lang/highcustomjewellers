<?php

namespace App\Jobs;

use App\Http\Controllers\CampaignController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $leadId;

    /**
     * Create a new job instance.
     */
    public function __construct($leadId)
    {
        $this->leadId = $leadId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(CampaignController::class)
            ->start($this->leadId);
    }
}
