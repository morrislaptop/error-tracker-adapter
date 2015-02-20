# error-tracker-adapter

[![Build
Status](https://secure.travis-ci.org/morrislaptop/error-tracker-adapter.png)](http://travis-ci.org/morrislaptop/error-tracker-adapter)
[![Total
Downloads](https://poser.pugx.org/morrislaptop/error-tracker-adapter/downloads.png)](https://packagist.org/packages/morrislaptop/error-tracker-adapter)
[![Latest Stable
Version](https://poser.pugx.org/morrislaptop/error-tracker-adapter/v/stable.png)](https://packagist.org/packages/morrislaptop/error-tracker-adapter)

Track errors and exceptions through the most popular SaaS platforms.

**Error Tracker Adapter** is a PHP library which helps you track exceptions and errors in your application by providing a powerful abstraction layer for error tracker SaaS platforms. 

It has been created on two main principles:

* [Code to an interface and not an implemenation](https://www.google.co.uk/?q=code%20to%20an%20interface)
* [Protecting yourself from third party APIs breaking your application](http://butunclebob.com/ArticleS.JamesGrenning.AlternativeToTheHopeAndPrayMethod)

The supported platforms are:

* [X] Sentry
* [] Bugsnag
* [] AirBrake

## Installation

The recommended way to install is through [Composer](http://getcomposer.org):

```
$ composer require morrislaptop/error-tracker-adapter
```

## Usage

The use of this library is a _reporter_ and not a renderer.

### Factory

@todo

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

See [CONTRIBUTING](CONTRIBUTING.md) file.

## Unit Tests

In order to run the test suite, install the developement dependencies:

```
$ composer install --dev
```

Then, run the following command:

```
$ phpunit && phpspec run
```

PHP-VCR is used to record the outgoing requests to the SaaS platforms and the requests are committed to the repo. By default PHP-VCR is set to not allow any requests that don't match the existing, verified request signatures. If you're sure that you've made a change that results in a different request and that should be recorded as the correct request you can:

* Update the `setMatchers()` function in your test to return true
* Delete the fixture, run the test and commit the new signature

## Versioning

Follows [Semantic Versioning](http://semver.org/).

## Credits

Inspiration:

* [ivory-http-adapter](https://github.com/egeloen/ivory-http-adapter)
* [geocoder-php](https://github.com/geocoder-php/Geocoder)
* [omnipay](https://github.com/thephpleague/omnipay)

## License

MIT license. For the full copyright and license information, please read the LICENSE file that was distributed with this source code.
