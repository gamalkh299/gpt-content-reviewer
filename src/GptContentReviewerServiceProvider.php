<?php

namespace gamalkh\GptContentReviewer;

use gamalkh\GptContentReviewer\Commands\GptContentReviewerCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GptContentReviewerServiceProvider extends PackageServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/gpt-content-review.php', 'gpt-content-review');
        $this->app->singleton('GptContentReview', function () {
            return new GptContentReviewer();
        });
    }


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


    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/gpt-content-review.php' => config_path('gpt-content-review.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
