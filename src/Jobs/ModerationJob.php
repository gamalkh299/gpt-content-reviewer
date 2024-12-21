<?php

namespace gamalkh\GptContentReviewer\Jobs;

use gamalkh\GptContentReviewer\GptContentReviewer;
use gamalkh\GptContentReviewer\Models\GptReviewer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ModerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reviewId;

    public function __construct($reviewId, $content)
    {

        $this->reviewId = $reviewId;
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $review = GptReviewer::find($this->reviewId);

        if (! $review) {
            // Log error if the review cannot be found
            \Log::error("Review not found for ID: {$this->reviewId}");

            return;
        }

        // Initialize the Content Review Service
        $service = app(GptContentReviewer::class);

        // Review the content
        try {
            $response = $service->ModerateContent($this->content);

            $review->update([
                'is_flagged' => $response['flagged'] ?? false,
                'reason' => $response['reason'] ?? null,
                'status' => 'completed',
            ]);
        } catch (\Exception $e) {
            $review->update([
                'status' => 'failed',
                'reason' => $e->getMessage(),
            ]);
        }
    }
}
