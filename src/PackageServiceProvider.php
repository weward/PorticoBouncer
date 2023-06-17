<?php 

namespace Weward\PorticoBouncer;

use spatie\LaravelPackageTools\PackageServiceProvider;

abstract class PackageServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        parent::boot();

        if ($this->package->hasMiddlewares) {
            $this->publishes([
                $this->package->basePath('Middleware') => app_path("Http/Middleware"),
            ], "{$this->package->shortName()}-middleware");
        }

        return $this;
    }

}