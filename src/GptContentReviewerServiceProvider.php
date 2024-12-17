<?php

namespace gamalkh\GptContentReviewer;

use gamalkh\GptContentReviewer\Commands\GptContentReviewerCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GptContentReviewerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('gpt-content-reviewer')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_gpt_content_reviewer_table')
            ->hasCommand(GptContentReviewerCommand::class);
    }
}
