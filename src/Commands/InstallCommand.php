<?php

namespace Weward\PorticoBouncer\Commands;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
// use Spatie\LaravelPackageTools\Package;
use Weward\PorticoBouncer\Package;

class InstallCommand extends Command
{
    protected Package $package;

    public ?Closure $startWith = null;

    protected bool $shouldPublishConfigFile = false;

    protected bool $shouldPublishAssets = false;

    protected bool $shouldPublishMiddlewares = false;

    protected bool $shouldPublishControllers = false;

    protected bool $shouldPublishRequests = false;

    protected bool $shouldPublishServices = false;

    protected bool $shouldPublishTests = false;

    protected bool $shouldPublishPackageRoutes = false;

    protected bool $shouldPublishModels = false;

    protected bool $shouldPublishMigrations = false;

    protected bool $askToRunMigrations = false;

    protected bool $copyServiceProviderInApp = false;

    protected ?string $starRepo = null;

    protected ?string $shouldInstallEverything = null;

    public ?Closure $endWith = null;

    public $hidden = true;

    public function __construct(Package $package)
    {
        $this->signature = $package->shortName().':install';

        $this->description = 'Install '.$package->name;

        $this->package = $package;

        parent::__construct();
    }

    public function handle()
    {
        if ($this->startWith) {
            ($this->startWith)($this);
        }

        if ($this->shouldPublishConfigFile) {
            $this->comment('Publishing config file...');

            $this->callSilently('vendor:publish', [
                '--tag' => "{$this->package->shortName()}-config",
            ]);
        }

        if ($this->shouldPublishAssets) {
            $this->comment('Publishing assets...');

            $this->callSilently('vendor:publish', [
                '--tag' => "{$this->package->shortName()}-assets",
            ]);
        }

        if ($this->shouldPublishMiddlewares) {
            $this->comment('Publishing middlewares...');

            $this->callSilently("vendor:publish", [
                '--tag' => "{$this->package->shortName()}-middlewares",
            ]);
        }

        if ($this->shouldPublishControllers) {
            $this->comment('Publishing controllers...');

            $this->callSilently("vendor:publish", [
                '--tag' => "{$this->package->shortName()}-controllers",
            ]);
        }

        if ($this->shouldPublishRequests) {
            $this->comment('Publishing requests...');

            $this->callSilently("vendor:publish", [
                '--tag' => "{$this->package->shortName()}-requests",
            ]);
        }

        if ($this->shouldPublishServices) {
            $this->comment('Publishing services...');

            $this->callSilently("vendor:publish", [
                '--tag' => "{$this->package->shortName()}-services",
            ]);
        }

        if ($this->shouldPublishTests) {
            $this->comment('Publishing tests...');

            $this->callSilently("vendor:publish", [
                '--tag' => "{$this->package->shortName()}-tests",
            ]);
        }

        if ($this->shouldPublishPackageRoutes) {
            $this->comment('Publishing package routes...');

            $this->callSilently("vendor:publish", [
                '--tag' => "{$this->package->shortName()}-package-routes",
            ]);
        }

        if ($this->shouldPublishMigrations) {
            $this->comment('Publishing migration...');

            $this->callSilently('vendor:publish', [
                '--tag' => "{$this->package->shortName()}-migrations",
            ]);
        }

        if ($this->askToRunMigrations) {
            if ($this->confirm('Would you like to run the migrations now?')) {
                $this->comment('Running migrations...');

                $this->call('migrate');
            }
        }

        if ($this->copyServiceProviderInApp) {
            $this->comment('Publishing service provider...');

            $this->copyServiceProviderInApp();
        }

        if ($this->starRepo) {
            if ($this->confirm('Would you like to star our repo on GitHub?')) {
                $repoUrl = "https://github.com/{$this->starRepo}";

                if (PHP_OS_FAMILY == 'Darwin') {
                    exec("open {$repoUrl}");
                }
                if (PHP_OS_FAMILY == 'Windows') {
                    exec("start {$repoUrl}");
                }
                if (PHP_OS_FAMILY == 'Linux') {
                    exec("xdg-open {$repoUrl}");
                }
            }
        }

        if ($this->shouldInstallEverything) {
            if ($this->confirm('Would you like to auto-install everything? (Select NO to publish each feature one by one)')) {
                return;
            }
        }

        $this->info("{$this->package->shortName()} has been installed!");

        if ($this->endWith) {
            ($this->endWith)($this);
        }
    }

    public function publishConfigFile(): self
    {
        $this->shouldPublishConfigFile = true;

        return $this;
    }

    public function publishAssets(): self
    {
        $this->shouldPublishAssets = true;

        return $this;
    }

    public function publishMiddlewares(): self
    {
        $this->shouldPublishMiddlewares = true;

        return $this;
    }

    public function publishControllers(): self
    {
        $this->shouldPublishControllers = true;

        return $this;
    }

    public function publishRequests(): self
    {
        $this->shouldPublishRequests = true;

        return $this;
    }

    public function publishServices(): self
    {
        $this->shouldPublishServices = true;

        return $this;
    }

    public function publishTests(): self
    {
        $this->shouldPublishTests = true;

        return $this;
    }

    public function publishPackageRoutes(): self
    {
        $this->shouldPublishPackageRoutes = true;

        return $this;
    }

    public function publishModels(): self
    {
        $this->shouldPublishModels = true;

        return $this;
    }

    public function publishMigrations(): self
    {
        $this->shouldPublishMigrations = true;

        return $this;
    }

    public function askToRunMigrations(): self
    {
        $this->askToRunMigrations = true;

        return $this;
    }

    public function copyAndRegisterServiceProviderInApp(): self
    {
        $this->copyServiceProviderInApp = true;

        return $this;
    }

    public function askToStarRepoOnGitHub($vendorSlashRepoName): self
    {
        $this->starRepo = $vendorSlashRepoName;

        return $this;
    }

    public function askIfShouldInstallEverythingAutomatically(): self 
    {
        $this->shouldInstallEverything = true;

        return $this;
    }

    public function startWith($callable): self
    {
        $this->startWith = $callable;

        return $this;
    }

    public function endWith($callable): self
    {
        $this->endWith = $callable;

        return $this;
    }

    protected function copyServiceProviderInApp(): self
    {
        $providerName = $this->package->publishableProviderName;

        if (! $providerName) {
            return $this;
        }

        $this->callSilent('vendor:publish', ['--tag' => $this->package->shortName().'-provider']);

        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        $class = '\\Providers\\'.$providerName.'::class';

        if (Str::contains($appConfig, $namespace.$class)) {
            return $this;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\\BroadcastServiceProvider::class,",
            "{$namespace}\\Providers\\BroadcastServiceProvider::class,".PHP_EOL."        {$namespace}{$class},",
            $appConfig
        ));

        file_put_contents(app_path('Providers/'.$providerName.'.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/'.$providerName.'.php'))
        ));

        return $this;
    }
}
