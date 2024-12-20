<?php

namespace gamalkh\GptContentReviewer\Traits;

use gamalkh\GptContentReviewer\Models\GptReviewer;

trait HasReviews
{
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
