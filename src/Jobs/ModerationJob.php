<?php

namespace gamalkh\GptContentReviewer\Jobs;

use gamalkh\GptContentReviewer\GptContentReviewer;
use gamalkh\GptContentReviewer\Models\GptReviewer;
use gamalkh\GptContentReviewer\Traits\ReasonHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ModerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels , ReasonHelper;

    protected $review;

    public function __construct(GptReviewer $review)
    {

        $this->review = $review;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        if (!$this->review) {
            // Log error if the review cannot be found
            \Log::error("Review not found for ID: {$this->review->id}");

            return;
        }

        $reviewable = $this->review->reviewable;

        if (!$reviewable) {
            // Log error if the reviewable model cannot be found
            \Log::error("Reviewable model not found for ID: {$this->review->reviewable_id}");

            return;
        }

        $columnsToReview = $reviewable->getReviewableColumns();
        $columnsToReview = is_array($columnsToReview) ? $columnsToReview : [$columnsToReview];

        foreach ($columnsToReview as $column) {
            if (!isset($reviewable->$column)) {
                \Log::warning("Column {$column} does not exist in the model.");
                continue;
            }

            $content = $reviewable->$column;

            // Initialize the Content Review Service
            $service = app(GptContentReviewer::class);

            try {
                $response = $service->ModerateContent($content);

                $this->review->update([
                    'is_flagged' => $response[0]['flagged'] ?? false,
                    'reason' => $this->getFlaggingReasons($response) ?? null,
                    'response' => $response,
                    'status' => 'completed',
                ]);


                //user-defined callback
                if (method_exists($reviewable, 'handleReviewResult')) {
                    $reviewable->handleReviewResult($this->review);
                }
            } catch (\Exception $e) {
                $this->review->update([
                    'status' => 'failed',
                    'reason' => $e->getMessage(),
                ]);
            }
        }
    }
}
