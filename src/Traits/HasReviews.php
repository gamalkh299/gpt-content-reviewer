<?php

namespace gamalkh\GptContentReviewer\Traits;

use gamalkh\GptContentReviewer\Models\GptReviewer;

trait HasReviews
{

    /**
     *
     * Get the column(s) that should be reviewed.
     * @return array|string
     */
        public abstract function getReviewableColumns();


    /**
     * @param GptReviewer $review
     * @return void
     */
        public abstract function handleReviewResult(GptReviewer $review): void;



    /**
     * Define a polymorphic relation to reviews.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reviews()
    {
        return $this->morphMany(GptReviewer::class, 'reviewable');
    }



}
