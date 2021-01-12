# Laravel - Roles

## Associate users with roles
 
![Header Image](https://github.com/MacsiDigital/repo-design/raw/master/laravel-roles/header.png)

<p align="center">
 <a href="https://github.com/MacsiDigital/laravel-roles/actions?query=workflow%3Atests"><img src="https://github.com/MacsiDigital/laravel-roles/workflows/Run%20tests/badge.svg" style="max-width:100%;" alt="tests badge"></a>
 <a href="https://packagist.org/packages/macsidigital/laravel-roles"><img src="https://img.shields.io/packagist/v/macsidigital/laravel-roles.svg?style=flat-square" alt="version badge"/></a>
 <a href="https://packagist.org/packages/macsidigital/laravel-roles"><img src="https://img.shields.io/packagist/dt/macsidigital/laravel-roles.svg?style=flat-square" alt="downloads badge"/></a>
</p>

This package allows you to manage and assign user roles.

## Support us

We invest a lot in creating [open source packages](https://macsidigital.co.uk/open-source), and would be grateful for a [sponsor](https://github.com/sponsors/MacsiDigital) if you make money from your product that uses them.

## Installation

This package can be used in Laravel 6.0 or higher.

You can install the package via composer:

``` bash
composer require macsidigital/laravel-roles
```

You can either publish the migration and config files separatly or use our helpful install command

The install function

``` bash
php artisan roles:install
```

You must publish the migration with:

``` bash
php artisan vendor:publish --tag="roles-migrations"
```

After the migration has been published you can create the db tables by running the migrations:

``` bash
php artisan migrate
```

You can publish the config file with:

``` bash
php artisan vendor:publish --tag="roles-config"
```

## Usage

Once installed you can do stuff like this:

```php
$user->assignRole('writer');

$user->hasRole('writer');

$user->removeRole('writer');
```

Every guard will have its own set of roles that can be assigned to the guard's users.

## Testing

``` bash
composer test
```

## Todos

[] Build out some proper documentation

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email [info@macsi.co.uk](mailto:info@macsi.co.uk) instead of using the issue tracker.

## Credits

- [Colin Hall](https://github.com/colinhall17)
- [MacsiDigital](https://github.com/macsidigital)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
