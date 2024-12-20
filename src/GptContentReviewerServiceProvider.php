<?php

namespace gamalkh\GptContentReviewer;

use gamalkh\GptContentReviewer\Commands\GptContentReviewerCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GptContentReviewerServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/gpt-content-reviewer.php', 'gpt-content-reviewer');
        $this->app->singleton('GptContentReviewer', function () {
            return new GptContentReviewer;
        });
    }

    public function configurePackage(Package $package): void
    {

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
            __DIR__.'/../config/gpt-content-reviewer.php' => config_path('gpt-content-reviewer.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_gpt_content_reviewer_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_gpt_content_reviewer_table.php'),
        ], 'migrations');
    }
}
