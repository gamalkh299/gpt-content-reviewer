<?php

namespace gamalkh\GptContentReviewer\Commands;

use Illuminate\Console\Command;

class GptContentReviewerCommand extends Command
{
    public $signature = 'gpt-content-reviewer';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
