<?php namespace tests\Morrislaptop\ErrorTracker\Adapter;

use PhpSpec\Exception\Example\SkippingException;
use Raygun4php\RaygunClient;
use Morrislaptop\ErrorTracker\Adapter\Raygun;
use PhpSpec\Exception\Wrapper\CollaboratorException;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;
use VCR\Request;
use VCR\VCR;

class RagyunTest extends TrackerTestCase
{

    /**
     * @vcr raygun.yml
     */
    public function testReport()
    {
        $this->markTestSkipped('php-vcr can\'t record ssl socket connections, php-vcr/php-vcr#109');
    }

}