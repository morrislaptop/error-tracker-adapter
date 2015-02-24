<?php namespace tests\Morrislaptop\ErrorTracker\Adapter;

use Bugsnag_Client;
use Morrislaptop\ErrorTracker\Adapter\Bugsnag;
use PhpSpec\Exception\Wrapper\CollaboratorException;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;
use VCR\Request;
use VCR\VCR;

class BugsnagTest extends TrackerTestCase {

    /**
     * @vcr bugsnag.yml
     */
    public function testReport()
    {
        // Arrange.
        $bugsnag = new Bugsnag_Client('23423');
        $bugsnag = new Bugsnag($bugsnag);
        $exception = new CollaboratorException('No collab!');

        // Act & PHP-VCR Asserts.
        $bugsnag->report($exception, ['php_version' => '5.4']);
    }

    /**
     * {inheritDoc}
     */
    public function setMatchers()
    {
        parent::setMatchers();

        VCR::configure()->addRequestMatcher('exception', function (Request $first, Request $second) {
            $first = json_decode($first->getBody());
            $second = json_decode($second->getBody());

            $this->assertSame($first->events[0]->exceptions[0]->errorClass, $second->events[0]->exceptions[0]->errorClass);
            $this->assertSame($first->events[0]->metaData->php_version, $second->events[0]->metaData->php_version);

            return true;
        });
        VCR::configure()->enableRequestMatchers(['method', 'url', 'host', 'post_fields', 'exception']);
    }


}