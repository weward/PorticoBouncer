<?php

namespace Weward\PorticoBouncer;

use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class Package extends Package
{
    public bool $hasMiddlewares = false;

    public function shortName(): string
    {
        return Str::after($this->name, 'laravel-');
    }

    public function hasMiddlewares(): static
    {
        $this->hasMiddlewares = true;

        return $this;
    }
}