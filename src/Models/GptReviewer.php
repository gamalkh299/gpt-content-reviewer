<?php

namespace gamalkh\GptContentReviewer\Models;

use Illuminate\Database\Eloquent\Model;

class GptReviewer extends model
{
    protected $table = 'gpt_content_reviewer_table';

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'is_flagged',
        'reason',
        'response',
        'status',
    ];
}
