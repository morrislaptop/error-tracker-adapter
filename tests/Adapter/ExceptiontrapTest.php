<?php namespace tests\Morrislaptop\ErrorTracker\Adapter;

use Exceptiontrap as Exceptiontrap_Client;
use Morrislaptop\ErrorTracker\Adapter\Exceptiontrap;
use PhpSpec\Exception\Wrapper\CollaboratorException;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;
use VCR\Request;
use VCR\VCR;

class ExceptiontrapTest extends TrackerTestCase
{

    /**
     * @vcr exceptiontrap.yml
     */
    public function testReport()
    {
        // Arrange.
        $exceptiontrap = new Exceptiontrap_Client();
        $exceptiontrap = new Exceptiontrap($exceptiontrap);
        $exception     = new CollaboratorException('No collab!');

        // Act & PHP-VCR Asserts.
        $exceptiontrap->report($exception);
    }

    /**
     * {inheritDoc}
     */
    public function setMatchers()
    {
        parent::setMatchers();

        VCR::configure()->addRequestMatcher('exception', function (Request $first, Request $second) {
            $first  = json_decode($first->getBody());
            $second = json_decode($second->getBody());

            $this->assertSame($first->problem->name, $second->problem->name);
            $this->assertSame($first->problem->message, $second->problem->message);

            return true;
        });

        VCR::configure()->enableRequestMatchers(['method', 'url', 'host', 'post_fields', 'exception']);
    }


}