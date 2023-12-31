<?php

namespace Weward\PorticoBouncer;

use Illuminate\Support\Str;
use Weward\PorticoBouncer\Commands\InstallCommand;

class Package
{
    public string $name;

    public array $configFileNames = [];

    public bool $hasViews = false;

    public ?string $viewNamespace = null;

    public bool $hasTranslations = false;

    public bool $hasAssets = false;

    public bool $hasMiddlewares = false;

    public bool $hasControllers = false;

    public bool $hasRequests = false;

    public bool $hasServices = false;

    public bool $hasTests = false;

    public bool $hasPackageRoutes = false;

    public bool $hasModels = false;

    public bool $hasTraits = false;

    public bool $hasFactories = false;

    public bool $hasInertiaViews = false;

    public bool $runsMigrations = false;

    public array $migrationFileNames = [];

    public array $routeFileNames = [];

    public array $commands = [];

    public array $consoleCommands = [];

    public array $viewComponents = [];

    public array $sharedViewData = [];

    public array $viewComposers = [];

    public string $basePath;

    public ?string $publishableProviderName = null;

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function hasConfigFile($configFileName = null): static
    {
        $configFileName = $configFileName ?? $this->shortName();

        if (! is_array($configFileName)) {
            $configFileName = [$configFileName];
        }

        $this->configFileNames = $configFileName;

        return $this;
    }

    public function publishesServiceProvider(string $providerName): static
    {
        $this->publishableProviderName = $providerName;

        return $this;
    }

    public function hasInstallCommand($callable): static
    {
        $installCommand = new InstallCommand($this);

        $callable($installCommand, $this);

        $this->consoleCommands[] = $installCommand;

        return $this;
    }

    public function shortName(): string
    {
        return Str::after($this->name, 'laravel-');
    }

    public function hasViews(string $namespace = null): static
    {
        $this->hasViews = true;

        $this->viewNamespace = $namespace;

        return $this;
    }

    public function hasViewComponent(string $prefix, string $viewComponentName): static
    {
        $this->viewComponents[$viewComponentName] = $prefix;

        return $this;
    }

    public function hasViewComponents(string $prefix, ...$viewComponentNames): static
    {
        foreach ($viewComponentNames as $componentName) {
            $this->viewComponents[$componentName] = $prefix;
        }

        return $this;
    }

    public function sharesDataWithAllViews(string $name, $value): static
    {
        $this->sharedViewData[$name] = $value;

        return $this;
    }

    public function hasViewComposer($view, $viewComposer): static
    {
        if (! is_array($view)) {
            $view = [$view];
        }

        foreach ($view as $viewName) {
            $this->viewComposers[$viewName] = $viewComposer;
        }

        return $this;
    }

    public function hasTranslations(): static
    {
        $this->hasTranslations = true;

        return $this;
    }

    public function hasAssets(): static
    {
        $this->hasAssets = true;

        return $this;
    }

    public function hasMiddlewares(): static
    {
        $this->hasMiddlewares = true;

        return $this;
    }

    public function hasControllers(): static
    {
        $this->hasControllers = true;

        return $this;
    }

    public function hasRequests(): static
    {
        $this->hasRequests = true;

        return $this;
    }

    public function hasServices(): static
    {
        $this->hasServices = true;

        return $this;
    }

    public function hasTests(): static
    {
        $this->hasTests = true;

        return $this;
    }

    public function hasPackageRoutes(): static
    {
        $this->hasPackageRoutes = true;

        return $this;
    }

    public function hasModels(): static
    {
        $this->hasModels = true;

        return $this;
    }

    public function hasTraits(): static
    {
        $this->hasTraits = true;

        return $this;
    }

    public function hasFactories(): static
    {
        $this->hasFactories = true;

        return $this;
    }

    public function hasInertiaViews(): static
    {
        $this->hasInertiaViews = true;

        return $this;
    }

    public function runsMigrations(bool $runsMigrations = true): static
    {
        $this->runsMigrations = $runsMigrations;

        return $this;
    }

    public function hasMigration(string $migrationFileName): static
    {
        $this->migrationFileNames[] = $migrationFileName;

        return $this;
    }

    public function hasMigrations(...$migrationFileNames): static
    {
        $this->migrationFileNames = array_merge(
            $this->migrationFileNames,
            collect($migrationFileNames)->flatten()->toArray()
        );

        return $this;
    }

    public function hasCommand(string $commandClassName): static
    {
        $this->commands[] = $commandClassName;

        return $this;
    }

    public function hasCommands(...$commandClassNames): static
    {
        $this->commands = array_merge($this->commands, collect($commandClassNames)->flatten()->toArray());

        return $this;
    }

    public function hasConsoleCommand(string $commandClassName): static
    {
        $this->consoleCommands[] = $commandClassName;

        return $this;
    }

    public function hasConsoleCommands(...$commandClassNames): static
    {
        $this->consoleCommands = array_merge($this->consoleCommands, collect($commandClassNames)->flatten()->toArray());

        return $this;
    }

    public function hasRoute(string $routeFileName): static
    {
        $this->routeFileNames[] = $routeFileName;

        return $this;
    }

    public function hasRoutes(...$routeFileNames): static
    {
        $this->routeFileNames = array_merge($this->routeFileNames, collect($routeFileNames)->flatten()->toArray());

        return $this;
    }

    public function basePath(string $directory = null): string
    {
        if ($directory === null) {
            return $this->basePath;
        }

        return $this->basePath.DIRECTORY_SEPARATOR.ltrim($directory, DIRECTORY_SEPARATOR);
    }

    public function viewNamespace(): string
    {
        return $this->viewNamespace ?? $this->shortName();
    }

    public function setBasePath(string $path): static
    {
        $this->basePath = $path;

        return $this;
    }
}
