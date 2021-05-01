# Laravel wrapper for the Google Distance Matrix API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mybit/laravel-google-distance-matrix.svg?style=flat-square)](https://packagist.org/packages/mybit/laravel-google-distance-matrix)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mybit/laravel-google-distance-matrix/run-tests?label=tests)](https://github.com/mybit/laravel-google-distance-matrix/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/mybit/laravel-google-distance-matrix/Check%20&%20fix%20styling?label=code%20style)](https://github.com/mybit/laravel-google-distance-matrix/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/mybit/laravel-google-distance-matrix.svg?style=flat-square)](https://packagist.org/packages/mybit/laravel-google-distance-matrix)

---

Fetches estimated travel time and distance for multiple destinations from the Google Maps API.

## Installation

You can install the package via composer [VCS](https://getcomposer.org/doc/05-repositories.md#vcs) (package currently not available via packagist):

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/flofloflo/laravel-google-distance-matrix"
        }
    ],
    "require": {
        "mybit/laravel-google-distance-matrix": "dev-master"
    }
}
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Mybit\GoogleDistanceMatrix\GoogleDistanceMatrixServiceProvider" --tag="laravel-google-distance-matrix-config"
```

This is the contents of the published config file:

```php
return [
     /**
     * The API key which should be used for calls to the Google Maps Distance Matrix API
     */
    'api_key' => env('GOOGLE_API_KEY', null),

    /**
     * Default values
     */
    'defaults' => [
        // Unit system used for distances
        'units' => GoogleDistanceMatrix::UNITS_METRIC,
        // Driving mode used for distance calculation
        'mode' => GoogleDistanceMatrix::MODE_DRIVING,
    ]
];
```

## Usage

```php
$distanceMatrix = new Mybit\GoogleDistanceMatrix();
$distance = $distanceMatrix
    ->setLanguage('de-DE')
    ->addOrigin('53.54942880970846, 9.95784213616111')
    ->addDestination('53.549626412962326, 9.968088174277717')
    ->sendRequest();
```

## Testing

Currently no tests implemented :-(

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Final Bytes](https://github.com/finalbytes/google-distance-matrix-api) - I used his google-distance-matrix-api package as starting point for this implementation
-   [Spatie](https://spatie.be/open-source) - Using their package skeleton
-   [Florian Heller](https://github.com/flofloflo)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
