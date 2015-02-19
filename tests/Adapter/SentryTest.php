<?php namespace tests\Morrislaptop\ErrorTracker\Adapter;

use Morrislaptop\ErrorTracker\Adapter\Sentry;
use PhpSpec\Exception\Wrapper\CollaboratorException;
use Raven_Client;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;
use VCR\Request;
use VCR\VCR;

class SentryTest extends TrackerTestCase {

    /**
     * @vcr sentry.yml
     */
    public function testPushAndPop()
    {
        // Arrange.
        $raven = new Raven_Client('https://adfasd:dafdsaf@app.getsentry.com/23432');
        $sentry = new Sentry($raven);
        $exception = new CollaboratorException('No collab!');

        // Act.
        $eventId = $sentry->report($exception, ['php_version' => '5.4']);

        // Assert.
        //$this->assert();
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

            $this->assertSame($second->message, $first->message);
            $this->assertSame($second->extra->php_version, $first->extra->php_version);

            return true;
        });
        VCR::configure()->enableRequestMatchers(['method', 'url', 'host', 'post_fields', 'exception']);
    }


}