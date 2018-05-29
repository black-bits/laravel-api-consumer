# API consumers for Laravel offering Eloquent style syntax

[![Latest Version on Packagist](https://img.shields.io/packagist/v/black-bits/laravel-api-consumer.svg?style=flat-square)](https://packagist.org/packages/black-bits/laravel-api-consumer)
[![Total Downloads](https://img.shields.io/packagist/dt/black-bits/laravel-api-consumer.svg?style=flat-square)](https://packagist.org/packages/black-bits/laravel-api-consumer)

This Laravel package lets you generate API Consumers with Endpoints and Shapes which usage is similar to Laravel's Eloquent models.

You can generate one Consumer per API service you want to consume. Each Consumer can have any number of Endpoints with multiple Shapes.

An Endpoint represents e.g. a resource on a REST API like /users. Endpoints return Collections of Shapes. You can model the methods the API offers here.

A Shape represents an Object returned by an Endpoint and allows you to e.g. transform or validate the Object's properties.

### Disclaimer
_This package is currently in development and is not production ready._

## Installation

You can install the package via composer

```bash
composer require black-bits/laravel-api-consumer
```

Next you can publish the config

```bash
php artisan vendor:publish --provider="BlackBits\ApiConsumer\ApiConsumerServiceProvider"
```

## Usage

To make a new Api Consumer Service you can simply run
``` bash
php artisan make:api-consumer ConsumerName
```

To add an endpoint (e.g. UserEndpoint) to this service run the following command, this will also create a default shape (UserShape) for that Endpoint
``` bash
php artisan make:api-consumer-endpoint UserEndpoint -c ConsumerName
```

To add a custom Collection Callback run the following command.
You can use this e.g. to create a filter that only shows Users that receive a newsletter
``` bash
php artisan make:api-consumer-collection-callback ReceivesNewsletter
```

You can find an example implementation here: [black-bits/laravel-api-consumer-showcase](https://github.com/black-bits/laravel-api-consumer-showcase)

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Security

If you discover any security related issues, please email [hello@blackbits.io](mailto:hello@blackbits.io) instead of using the issue tracker.

## Credits

- [Oliver Heck](https://github.com/oheck)
- [Andreas Przywara](https://github.com/aprzywara)
- [Adrian Raeuchle](https://github.com/araeuchle)
- [All Contributors](../../contributors)

## Support us

Black Bits, Inc. is a web and consulting agency specialized in Laravel and AWS based in Grants Pass, Oregon. You'll find an overview of what we do [on our website](https://blackbits.io).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
