# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  
## Server Requirements

The Laravel Sitemap package has a few system requirements:

    - PHP >= 7.0

## Version Compatibility

| Laravel Sitemap                                  | Laravel                      |
|:-------------------------------------------------|:-----------------------------|
| ![Laravel Sitemap v1.0.x][laravel_sitemap_1_0_x] | ![Laravel v5.4][laravel_5_4] |
| ![Laravel Sitemap v1.1.x][laravel_sitemap_1_1_x] | ![Laravel v5.5][laravel_5_5] |

[laravel_5_4]:  https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"
[laravel_5_5]:  https://img.shields.io/badge/v5.5-supported-brightgreen.svg?style=flat-square "Laravel v5.5"

[laravel_sitemap_1_0_x]: https://img.shields.io/badge/version-1.0.*-blue.svg?style=flat-square "Laravel Sitemap v1.0.*"
[laravel_sitemap_1_1_x]: https://img.shields.io/badge/version-1.1.*-blue.svg?style=flat-square "Laravel Sitemap v1.1.*"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: 

```bash
composer require arcanedev/laravel-sitemap
```

## Laravel

### Setup

> **NOTE :** The package will automatically register itself if you're using Laravel `>= v5.5`, so you can skip this section.

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
