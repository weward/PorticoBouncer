<?php

namespace Weward\PorticoBouncer;

// use Spatie\LaravelPackageTools\Package as SpatiePackage;
use Weward\PorticoBouncer\Package;
use Weward\PorticoBouncer\PackageServiceProvider;
// use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Weward\PorticoBouncer\Commands\InstallCommand;

class PorticoBouncerServiceProvider extends PackageServiceProvider
{
    protected $packageName = 'porticobouncer';

    public function boot()
    {
        parent::boot();

        // $this->publishMiddlewares();
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
            // ->hasMiddlewares()
            ->hasControllers()
            ->hasRequests()
            ->hasServices()
            ->hasTests()
            ->hasPackageRoutes()
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->startWith(function(InstallCommand $command) {
                        $command->info("Installing package {$this->packageName}");
                    })
                    // ->publishMiddlewares()
                    ->publishControllers()
                    ->publishRequests()
                    ->publishServices()
                    ->publishTests()
                    ->publishPackageRoutes()
                    ->endWith(function (InstallCommand $command) {
                        $command->info("Done installing package {$this->packageName}");
                    });
            });
    }
}
