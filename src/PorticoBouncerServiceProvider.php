<?php

namespace Weward\PorticoBouncer;

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
         * Package $package for adding variables and setters
         *
         * PackageServiceProvider for defining the method implementation
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name($this->packageName)
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
    }
}
