<?php

namespace Weward\PorticoBouncer;

// use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Weward\PorticoBouncer\Commands\InstallCommand;

class PorticoBouncerServiceProvider extends PackageServiceProvider
{
    protected $packageName = 'porticobouncer';

    public function boot()
    {
        parent::boot();
    }

    // public function configurePackage(SpatiePackage $package): void
    public function configurePackage(Package $package): void
    {
        /*
         * Package $package for default Spatie methods
         *
         * For custom methods/properties, use the Weward/PorticoBouncer/PackageServiceProvider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name($this->packageName)
            ->hasMiddlewares()
            ->hasControllers()
            ->hasRequests()
            ->hasServices()
            ->hasTests()
            ->hasPackageRoutes()
            ->hasModels()
            ->hasTraits()
            ->hasFactories()
            ->hasInstallCommand(function (InstallCommand $command, $packageInstance) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info("Installing package {$this->packageName}");
                    })
                    ->publishMiddlewares()
                    ->publishControllers()
                    ->publishRequests()
                    ->publishServices()
                    ->publishTests()
                    ->publishPackageRoutes()
                    ->publishModels()
                    ->publishTraits()
                    ->publishFactories()
                    ->endWith(function (InstallCommand $command) {
                        $command->info("Done installing package {$this->packageName}");
                    });
            });
    }
}
