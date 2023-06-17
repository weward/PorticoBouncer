<?php

namespace Weward\PorticoBouncer;

// use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Weward\PorticoBouncer\Commands\PorticoBouncerCommand;

class PorticoBouncerServiceProvider extends PackageServiceProvider
{
    protected $packageName = 'porticobouncer';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name($this->packageName)
            // ->hasConfigFile()
            // ->hasViews()
            ->hasMiddlewares()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info("Installing package {$this->packageName}");
                    })
                    ->endWith(function (InstallCommand $command) {
                        $command->info("Done installing package {$this->packageName}");
                    });
            });
        // ->hasMigration('create_porticobouncer_table')
        // ->hasCommand(PorticoBouncerCommand::class);
    }

    public function boot()
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            if ($this->package->hasTranslations) {
                $this->publishes([
                    $this->package->basePath('/../resources/lang') => $langPath,
                ], "{$this->package->shortName()}-translations");
            }
        }

        return $this;
    }
}
