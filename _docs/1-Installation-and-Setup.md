# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  4. [FAQ](4-FAQ.md)
  
## Server Requirements

The Laravel Sitemap package has a few system requirements:

    - PHP >= 7.0

## Version Compatibility

| Laravel Sitemap                              | Laravel                      |
|:---------------------------------------------|:-----------------------------|
| ![Laravel Sitemap v1.x][laravel_sitemap_1_x] | ![Laravel v5.4][laravel_5_4] |

[laravel_5_0]:  https://img.shields.io/badge/v5.0-supported-brightgreen.svg?style=flat-square "Laravel v5.0"
[laravel_5_1]:  https://img.shields.io/badge/v5.1-supported-brightgreen.svg?style=flat-square "Laravel v5.1"
[laravel_5_2]:  https://img.shields.io/badge/v5.2-supported-brightgreen.svg?style=flat-square "Laravel v5.2"
[laravel_5_3]:  https://img.shields.io/badge/v5.3-supported-brightgreen.svg?style=flat-square "Laravel v5.3"
[laravel_5_4]:  https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"

[laravel_sitemap_0_x]: https://img.shields.io/badge/version-0.*-blue.svg?style=flat-square "Laravel Sitemap v0.*"
[laravel_sitemap_1_x]: https://img.shields.io/badge/version-1.*-blue.svg?style=flat-square "Laravel Sitemap v1.*"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: 

```bash
composer require arcanedev/laravel-sitemap
```

## Laravel

### Setup

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [
    ...
    Arcanedev\LaravelSitemap\LaravelSitemapServiceProvider::class,
],
```

### Artisan commands

To publish the config/lang/view files, run this command:

```bash
php artisan vendor:publish --provider="Arcanedev\LaravelSitemap\LaravelSitemapServiceProvider"
```
