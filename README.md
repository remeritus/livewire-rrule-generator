# Livewire RRule Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/remeritus/livewire-rrule-generator.svg?style=flat-square)](https://packagist.org/packages/remeritus/livewire-rrule-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/remeritus/livewire-rrule-generator/run-tests?label=tests)](https://github.com/remeritus/livewire-rrule-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/remeritus/livewire-rrule-generator/Check%20&%20fix%20styling?label=code%20style)](https://github.com/remeritus/livewire-rrule-generator/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/remeritus/livewire-rrule-generator.svg?style=flat-square)](https://packagist.org/packages/remeritus/livewire-rrule-generator)

Generate RRule Strings using Livewire.

## Installation

You can install the package via composer:

```bash
composer require remeritus/livewire-rrule-generator
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="livewire-rrule-generator_without_prefix-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="example-views"
```

This is the content of the published config file:

```php
return [
    'title'         => 'Define Schedule',
    'includeWeekend' => TRUE,
    'frequencies' => [
        'SECONDLY'  => FALSE,
        'MINUTELY'  => FALSE,
        'HOURLY'    => FALSE,
        'DAILY'     => TRUE,
        'WEEKLY'    => TRUE,
        'MONTHLY'   => TRUE,
        'YEARLY'    => FALSE,
    ],
    'defaultView'   => 'WEEKLY',
    'weekStarts'    => 'MO',
];
```

## Usage

```php
<livewire:rrule-generator/>
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Richard Sihm](https://github.com/remeritus)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
