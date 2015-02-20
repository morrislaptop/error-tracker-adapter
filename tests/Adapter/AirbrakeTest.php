<?php namespace tests\Morrislaptop\ErrorTracker\Adapter;

use Airbrake\Client;
use Airbrake\Configuration;
use Morrislaptop\ErrorTracker\Adapter\Airbrake;
use PhpSpec\Exception\Wrapper\CollaboratorException;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;
use VCR\Request;
use VCR\VCR;

class AirbrakeTest extends TrackerTestCase {

    /**
     * @vcr airbrake.yml
     */
    public function testReport()
    {
        // Arrange.
        $config = new Configuration('23423423432');
        $airbrake = new Client($config);
        $airbrake = new Airbrake($airbrake);
        $exception = new CollaboratorException('No collab!');

        // Act & PHP-VCR Asserts.
        $airbrake->report($exception, ['php_version' => '5.4']);
    }

    /**
     * {inheritDoc}
     */
    public function setMatchers()
    {
        parent::setMatchers();

        VCR::configure()->addRequestMatcher('exception', function (Request $first, Request $second) {
            $first = simplexml_load_string($first->getBody());
            $second = simplexml_load_string($second->getBody());

            $this->assertSame((string) $second->error->class, (string) $first->error->class);
            $this->assertSame((string) $second->request->params->var, (string) $first->request->params->var);

            return true;
        });
        VCR::configure()->enableRequestMatchers(['method', 'url', 'host', 'post_fields', 'exception']);
    }


}