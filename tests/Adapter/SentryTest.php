<?php namespace tests\Morrislaptop\ErrorTracker\Adapter;

use Raven_Client;
use Morrislaptop\ErrorTracker\Adapter\Sentry;
use PhpSpec\Exception\Wrapper\CollaboratorException;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;
use VCR\Request;
use VCR\VCR;

class SentryTest extends TrackerTestCase {

    /**
     * @vcr sentry.yml
     */
    public function testReport()
    {
        // Arrange.
        $raven = new Raven_Client('https://adfasd:dafdsaf@app.getsentry.com/23432');
        $sentry = new Sentry($raven);
        $exception = new CollaboratorException('No collab!');

        // Act & PHP-VCR Asserts.
        $sentry->report($exception, ['php_version' => '5.4']);
    }

    /**
     * {inheritDoc}
     */
    public function setMatchers()
    {
        parent::setMatchers();

        VCR::configure()->addRequestMatcher('exception', function (Request $first, Request $second) {
            $first = json_decode(gzuncompress(base64_decode($first->getBody())));
            $second = json_decode(gzuncompress(base64_decode($second->getBody())));

            $this->assertSame($first->message, $second->message);
            $this->assertSame($first->extra->php_version, $second->extra->php_version);

            return true;
        });
        VCR::configure()->enableRequestMatchers(['method', 'url', 'host', 'post_fields', 'exception']);
    }


}