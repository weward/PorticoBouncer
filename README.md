# PorticoBouncer

This also serves as a template

---

# Requirements

- Install Silber/Bouncer package

```sh
    composer require silber/bouncer:^1.0
```

- Add `HasRolesAndAbilities` trait in `User.php`

```php
    use Silber\Bouncer\Database\HasRolesAndAbilities;

    use HasRolesAndAbilities;
```

- Publish Silber/Bouncer's migration 

```sh
    php artisan vendor:publish --tag="bouncer.migrations"
```

- Run the migration

```sh 
    php artisan migrate
```


---

### Installation

- Submit project via Packagist

- Composer require weward/porticobounce:^#.#.#

- `composer dump-auto`

- `php artisan package:discover`

- `php artisan porticobouncer:install`

- Check if the package files were registered properly (If the specified files has these values):
    - Thh `HasPorticoBouncerPermissions` trait is present in the `User.php` model

    - Custom models are present in `AppServiceProvider.php`

    ```
        Bouncer::useAbilityModel(\App\Models\Admin\Ability::class);
        Bouncer::useRoleModel(\App\Models\Admin\Role::class);
    ```
    - The `porticobouncer` routes were registered in the `RouteServiceProvider`
    - The `'portico.bouncer'` entry exists in thhhe $middlewareAliases array of the `Http/Kernel.php`
    - Add Role index route to `resources/js/Properties/navMenu.js`
    ```js
        {
            label: 'User Roles',
            route: route('roles.index'),
            icon: 'mdi-account-star'
        },
    ```

- Run tests
    - `php artisan test --filter=ability`
    - `php artisan test --filter=role`

#### OR publish files manually:

- `php artisan vendor:publish --tag=subpackage-middleware` if would publish middlewares that came with the package to `App\Http\Middleware`
- `php artisan vendor:publish --tag=subpackage-controllers` if would publish controllers that came with the package to `App\Http\Controllers`
- `php artisan vendor:publish --tag=subpackage-requests` if would publish requests that came with the package to `App\Http\Requests`
- `php artisan vendor:publish --tag=subpackage-services` if would publish services that came with the package to `App\Services\Admin`
- `php artisan vendor:publish --tag=subpackage-tests` if would publish services that came with the package to `tests\Feature\Admin`
- `php artisan vendor:publish --tag=subpackage-package-routes` if would publish routes that came with the package to `routes` *Note:* This will also register the route file `porticobouncer.php` in the app's `RouteServiceProvider.php`

- Add `Weward\PorticoBouncer\PorticoBouncerServiceProvider::class,` to the `'providers'` array of the `config/app.php` file. 


---

# Developing and Updating the Package

- Include `PorticoBouncer` package folder (`dev/Personal/laravel-packages`) into the VSCode workspace
- Develop, update and test the files in the main project
- Copy over all the updated files into its respective folders in the `PorticoBouncer` package
- Commit the changes to `PorticoBouncer`
- Add **tags** to `PorticoBouncer`
- Update the packagist entry


---

### Creating a new package

- Use Spatie package-skeleton-laravel to install and configure a new package (+namespace|etc)

- Copy the ff from Spatie/laravel-package-tools and update the imports in your own package

    - `InstallCommand.php`

    - `Package.php`

    - `PackageServiceProvider.php`

- If will be creating a new method (publishing files), add the method implementation in the boot() method of Package.php

- Add the variables in the PackageServiceProvider

- Call the method in the configurePackage() method of the PackageNameServiceProvider

---



# This is just a test

[![Latest Version on Packagist](https://img.shields.io/packagist/v/weward/porticobouncer.svg?style=flat-square)](https://packagist.org/packages/weward/porticobouncer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/weward/porticobouncer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/weward/porticobouncer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/weward/porticobouncer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/weward/porticobouncer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/weward/porticobouncer.svg?style=flat-square)](https://packagist.org/packages/weward/porticobouncer)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/PorticoBouncer.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/PorticoBouncer)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require weward/porticobouncer
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="porticobouncer-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="porticobouncer-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="porticobouncer-views"
```

## Usage

```php
$porticoBouncer = new Weward\PorticoBouncer();
echo $porticoBouncer->echoPhrase('Hello, Weward!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [weward](https://github.com/weward)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
