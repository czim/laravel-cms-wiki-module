# CMS for Laravel - Wiki Documentation Module

Simple wiki for the CMS.

This module adds a wiki that may be browsed and maintained by CMS users. 

Its recommended use is to provide in-application documentation about the CMS.
For example: editors can browse the wiki to read about custom CMS functionality; administrators can maintain the wiki contents.  

To be used to with the [Laravel CMS Core](https://github.com/czim/laravel-cms-core).

This package is compatible and tested with Laravel 5.3 and 5.4.


## Installation

Add the module class to your `cms-modules.php` configuration file:

``` php
    'modules' => [
        // ...
        Czim\CmsWikiModule\Modules\WikiModule::class,
    ],
```

Add the service provider to your `cms-modules.php` configuration file:

``` php
    'providers' => [
        // ...
        Czim\CmsWikiModule\Providers\CmsWikiModuleServiceProvider::class,
        // ...
    ],
```

To publish the config and migration:

``` bash
php artisan vendor:publish
```

Run the CMS migration:

```bash
php artisan cms:migrate
```

## Usage

The wiki will automatically be present in your menu, with a link to the home page.



### Security

As with any module, only authenticated CMS users can access its routes. 

Additionally a non-admin user must have the following permissions:

| Permission key             | Description       |
| -------------------------- | ----------------- |
| wiki.page.create           | Create new wiki pages |
| wiki.page.edit             | Edit existing wiki pages |
| wiki.page.delete           | Delete wiki pages |

Or simply set `wiki.page.*` for all of the above.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/czim/laravel-cms-wiki-module.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/czim/laravel-cms-wiki-module.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/czim/laravel-cms-wiki-module
[link-downloads]: https://packagist.org/packages/czim/laravel-cms-wiki-module
[link-author]: https://github.com/czim
[link-contributors]: ../../contributors
