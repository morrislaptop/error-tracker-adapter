# error-tracker-adapter

[![Build
Status](https://secure.travis-ci.org/morrislaptop/error-tracker-adapter.png)](http://travis-ci.org/morrislaptop/error-tracker-adapter)
[![Total
Downloads](https://poser.pugx.org/morrislaptop/error-tracker-adapter/downloads.png)](https://packagist.org/packages/morrislaptop/error-tracker-adapter)
[![Latest Stable
Version](https://poser.pugx.org/morrislaptop/error-tracker-adapter/v/stable.png)](https://packagist.org/packages/morrislaptop/error-tracker-adapter)

Track errors and exceptions through the most popular SaaS platforms.

**Error Tracker Adapter** is a PHP library which helps you track exceptions and errors in your application by providing a powerful abstraction layer for error tracker SaaS platforms and / or local repositories like emails and log files. 

It has been created on two main principles:

* [Code to an interface and not an implemenation](https://www.google.co.uk/?q=code%20to%20an%20interface)
* [Protecting yourself from third party APIs breaking your application](http://butunclebob.com/ArticleS.JamesGrenning.AlternativeToTheHopeAndPrayMethod)

## Architecture 

### SaaS Platforms

SaaS platforms are supported via the use of adapters, the currently supported platforms are:

* [X] [Sentry](https://getsentry.com/) via [raven/raven](https://github.com/getsentry/raven-php)
* [X] [Bugsnag](https://bugsnag.com/) via [bugsnag/bugsnag](https://github.com/bugsnag/bugsnag-php)
* [X] [AirBrake](https://airbrake.io/) via [dbltr/php-airbrake](https://github.com/dbtlr/php-airbrake)
* [X] [Rollbar](https://rollbar.com/) via [rollbar/rollbar](https://github.com/rollbar/rollbar-php)
* [X] [Exceptiontrap](https://exceptiontrap.com/) via [exceptiontrap/exceptiontrap](https://github.com/itmLABS/exceptiontrap-php)
* [X] [Raygun](https://raygun.io/) via [mindscape/raygun4php](https://github.com/MindscapeHQ/raygun4php)
* [ ] [Bugify](https://bugify.com/)

### Local Repositories

Local repositories like emails, logs or database are supported via the use of providers, the currently supported repositories are:

* [X] Email Repoter via [error-tracker-adapter-email](https://github.com/morrislaptop/error-tracker-adapter-email)
* [X] Log Reporter

If you use [Monolog](https://github.com/Seldaek/monolog), you now have the ability to log your exceptions into any of it's handlers - Pushover notifications, HipChat, Flowdock, Slack etc.. 

```php
$monolog = new Monolog\Logger();
$monolog->pushHandler(new FlowdockHandler('apiToken', Monolog\Logger::ERROR));
$logger = new Morrislaptop\ErrorTracker\Provider\Log($monolog);
```

### Grouping Trackers

Also, you can use Group trackers which can use multiple trackers in different ways:

* [X] Chain Reporter for reporting until one is successful (e.g. falling back to a log if Sentry is down)
* [X] Net Reporter for reporting to all trackers (e.g. logging AND reporting to Sentry for redundancy)

### Extending Things

You can write your own provider or adapter by implementing the `Tracker` interface. Optionally you can use the other interfaces `Adapter`, `Provider` or `Group` and/or extend the abstract classes `AbstractAdapter`, `AbstractProvider` or `AbstractGroup` to help classify your trackers. 

## Installation

The recommended way to install is through [Composer](http://getcomposer.org):

```
$ composer require morrislaptop/error-tracker-adapter
```

## Usage

The use of this library is a _reporter_ and not a renderer. So it's recommended that you handle exceptions in your application with your own class and then report to the interface if it's the right error type and/or environment.

An example exception handler for your application might look like.. 

```php
<?php namespace App\Exceptions;

use Exception;
use Morrislaptop\ErrorTracker\Tracker;

class ExceptionHandler
{
	/**
	 * @var Tracker
	 */
	protected $tracker;

	/**
	 * @param Tracker $tracker
	 */
	function __construct(Tracker $tracker)
	{
		$this->tracker = $tracker;
	}

	/**
	 * Bootstrap this class into the runtime
	 */
	public function bootstrap() {
		error_reporting(-1);
		set_error_handler([$this, 'handleError']);
		set_exception_handler([$this, 'handleException']);
		register_shutdown_function([$this, 'handleShutdown']);
	}

	/**
	 * Convert a PHP error to an ErrorException.
	 *
	 * @param  int  $level
	 * @param  string  $message
	 * @param  string  $file
	 * @param  int  $line
	 * @param  array  $context
	 * @return void
	 *
	 * @throws \ErrorException
	 */
	public function handleError($level, $message, $file = '', $line = 0, $context = array())
	{
		if (error_reporting() & $level)
		{
			throw new ErrorException($message, 0, $level, $file, $line);
		}
	}

	/**
	 * Handle an uncaught exception from the application.
	 *
	 * Note: Most exceptions can be handled via the try / catch block in
	 * the HTTP and Console kernels. But, fatal error exceptions must
	 * be handled differently since they are not normal exceptions.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function handleException($e)
	{
		if (!getenv('APP_DEBUG')) {
			$this->tracker->report($e);
		}

		throw $e; // throw back to core PHP to render
	}

	/**
	 * Handle the PHP shutdown event.
	 *
	 * @return void
	 */
	public function handleShutdown()
	{
		if ( ! is_null($error = error_get_last()) && $this->isFatal($error['type']))
		{
			$this->handleException(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
		}
	}

	/**
	 * Determine if the error type is fatal.
	 *
	 * @param  int  $type
	 * @return bool
	 */
	protected function isFatal($type)
	{
		return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
	}

}
```

For convenience, the above generic exception handler is included with the library so you can quickly get started, by simply creating it and calling the `bootstrap()` method. 

```php
$tracker = new Morrislaptop\ErrorTracker\Adapter\Sentry(new Raven_Client('https://blah.com'));
// or
$tracker = new Morrislaptop\ErrorTracker\Adapter\BugSnag(new Bugsnag_Client('2344324342'));

$handler = new Morrislaptop\ErrorTracker\ExceptionHandler($tracker);
$handler->bootstrap();
```

### Group Providers

```php

$monolog = new Monolog\Logger();
$monolog->pushHandler(new StreamHandler('path/to/your.log', Monolog\Logger::ERROR));
$logger = new Morrislaptop\ErrorTracker\Provider\Log($monolog);

$sentry = new Morrislaptop\ErrorTracker\Adapter\Sentry(new Raven_Client('https://blah.com'));
$bugsnag = new Morrislaptop\ErrorTracker\Adapter\BugSnag(new Bugsnag_Client('2344324342'));

// If you wanted to fallback to a log if reporting to Sentry fails..
$chain = new Morrislaptop\ErrorTracker\Group\Chain();
$chain->add($sentry);
$chain->add($logger);
$chain->report(new \Exception('This will be logged to file if Sentry is down'));

// If you wanted to report to Bugsnag AND Sentry to compare those platforms
$chain = new Morrislaptop\ErrorTracker\Group\Chain();
$chain->add($sentry);
$chain->add($bugsnag);
$chain->report(new \Exception('Which is the better platform? This exception will go to both'));

```

### Contexts

Many platforms support the idea of contexts to give you more information about exceptions occuring in your application. This can happen on the user, environment or event level. 

This will be implemented via a `ContextInterface` in the future. In the mean time you can pass extra information via the `report()` method like..

```php
$this->tracker->report($e, ['user_id' => Session::get('user_id'));
```

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

## License

MIT license. For the full copyright and license information, please read the LICENSE file that was distributed with this source code.
