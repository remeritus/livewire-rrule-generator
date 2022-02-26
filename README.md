<p align="center">
<img src="livewire-rrule-generator-logo.png" width="640px" alt="">
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/remeritus/livewire-rrule-generator.svg?style=flat-square)](https://packagist.org/packages/remeritus/livewire-rrule-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/remeritus/livewire-rrule-generator/run-tests?label=tests)](https://github.com/remeritus/livewire-rrule-generator/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/remeritus/livewire-rrule-generator/Check%20&%20fix%20styling?label=code%20style)](https://github.com/remeritus/livewire-rrule-generator/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/remeritus/livewire-rrule-generator.svg?style=flat-square)](https://packagist.org/packages/remeritus/livewire-rrule-generator)

Livewire Rrule Generator is a GUI for `rlanvin/php-rrule`. It gerates `RRULE` from [RFC 5545](https://datatracker.ietf.org/doc/html/rfc5545) complaint strings that can be used to manage recurring events. 

Functionality is limited to Daily/Weekly/Monthly/Yearly rrules and GUI mimics Google Calendar approach.


## Installation

You can install the package via composer:

```bash
composer require remeritus/livewire-rrule-generator
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="livewire-rrule-generator_without_prefix-config"
```


This is the content of the published config file:

```php
return [
    'title'         => 'Define Schedule',
    'includeWeekend' => TRUE,
    'frequencies' => [
        'DAILY'     => TRUE,
        'WEEKLY'    => TRUE,
        'MONTHLY'   => TRUE,
        'YEARLY'    => TRUE,
    ],
    'defaultView'   => 'WEEKLY',
    'weekStarts'    => 'MO',
];
```

## Usage
### In your views
#### If you want to create new Rrule string
```php
<livewire:rrule-generator/>
```
#### If you want to edit existing Rrule string
If you want to edit existing RRule you can pass it to `rrule-string`
```php
<livewire:rrule-generator rrule-string='FREQ=WEEKLY;COUNT=30;INTERVAL=1' />
```
### Accessing  RRule String
- There is an `<input type="hidden" ... name="rrule_string"/>` from which you can extract the RRule String. So if you place `<livewire:rrule-generator/>` within a form it will be part of the forms data. 
- You can also listen to livewire event `rruleCreated`, which emits RRule String on Rrule's creation.

[//]: # (## Testing)

[//]: # ()
[//]: # (```bash)

[//]: # (composer test)

[//]: # (```)

[//]: # (## Changelog)

[//]: # ()
[//]: # (Please see [CHANGELOG]&#40;CHANGELOG.md&#41; for more information on what has changed recently.)

[//]: # (## Contributing)

[//]: # ()
[//]: # (Please see [CONTRIBUTING]&#40;.github/CONTRIBUTING.md&#41; for details.)

[//]: # (## Security Vulnerabilities)

[//]: # ()
[//]: # (Please review [our security policy]&#40;../../security/policy&#41; on how to report security vulnerabilities.)

## Credits

- [Richard Sihm](https://github.com/remeritus)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
