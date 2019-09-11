# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)

## Version Compatibility

| Laravel Sitemap                                  | Laravel                      |
|:-------------------------------------------------|:-----------------------------|
| ![Laravel Sitemap v1.0.x][laravel_sitemap_1_0_x] | ![Laravel v5.4][laravel_5_4] |
| ![Laravel Sitemap v1.1.x][laravel_sitemap_1_1_x] | ![Laravel v5.5][laravel_5_5] |
| ![Laravel Sitemap v1.2.x][laravel_sitemap_1_2_x] | ![Laravel v5.6][laravel_5_6] |
| ![Laravel Sitemap v1.3.x][laravel_sitemap_1_3_x] | ![Laravel v5.7][laravel_5_7] |
| ![Laravel Sitemap v1.4.x][laravel_sitemap_1_4_x] | ![Laravel v5.8][laravel_5_8] |
| ![Laravel Sitemap v2.0.x][laravel_sitemap_2_0_x] | ![Laravel v6.0][laravel_6_0] |

[laravel_5_4]:  https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"
[laravel_5_5]:  https://img.shields.io/badge/v5.5-supported-brightgreen.svg?style=flat-square "Laravel v5.5"
[laravel_5_6]:  https://img.shields.io/badge/v5.6-supported-brightgreen.svg?style=flat-square "Laravel v5.6"
[laravel_5_7]:  https://img.shields.io/badge/v5.7-supported-brightgreen.svg?style=flat-square "Laravel v5.7"
[laravel_5_8]:  https://img.shields.io/badge/v5.8-supported-brightgreen.svg?style=flat-square "Laravel v5.8"
[laravel_6_0]:  https://img.shields.io/badge/v6.0-supported-brightgreen.svg?style=flat-square "Laravel v6.0"

[laravel_sitemap_1_0_x]: https://img.shields.io/badge/version-1.0.*-blue.svg?style=flat-square "Laravel Sitemap v1.0.*"
[laravel_sitemap_1_1_x]: https://img.shields.io/badge/version-1.1.*-blue.svg?style=flat-square "Laravel Sitemap v1.1.*"
[laravel_sitemap_1_2_x]: https://img.shields.io/badge/version-1.2.*-blue.svg?style=flat-square "Laravel Sitemap v1.2.*"
[laravel_sitemap_1_3_x]: https://img.shields.io/badge/version-1.3.*-blue.svg?style=flat-square "Laravel Sitemap v1.3.*"
[laravel_sitemap_1_4_x]: https://img.shields.io/badge/version-1.4.*-blue.svg?style=flat-square "Laravel Sitemap v1.4.*"
[laravel_sitemap_2_0_x]: https://img.shields.io/badge/version-2.0.*-blue.svg?style=flat-square "Laravel Sitemap v2.0.*"

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
