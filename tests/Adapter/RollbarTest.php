<?php namespace tests\Morrislaptop\ErrorTracker\Adapter;

use PHPUnit_Framework_ExpectationFailedException;
use RollbarNotifier;
use Morrislaptop\ErrorTracker\Adapter\Rollbar;
use PhpSpec\Exception\Wrapper\CollaboratorException;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;
use VCR\Request;
use VCR\VCR;

class RollbarTest extends TrackerTestCase
{
    /**
     * @var PHPUnit_Framework_ExpectationFailedException
     */
    var $requestsMatchedException = null;

    /**
     * @vcr rollbar.yml
     */
    public function testReport()
    {
        // Arrange.
        $rollbar   = new RollbarNotifier([
            'access_token' => 'c2d318596d6a4607afcf1a0c96ddfbd8',
            'batched'      => false
        ]);
        $rollbar   = new Rollbar($rollbar);
        $exception = new CollaboratorException('No collab!');

        // Act.
        $rollbar->report($exception);

        // Assert.
        if ($this->requestsMatchedException) {
            throw $this->requestsMatchedException;
        }
    }

    /**
     * {inheritDoc}
     */
    public function setMatchers()
    {
        parent::setMatchers();

        VCR::configure()->addRequestMatcher('exception', function (Request $first, Request $second) {
            $first  = json_decode($first->getBody())->data->body->trace->exception;
            $second = json_decode($second->getBody())->data->body->trace->exception;

            // The below asserts will throw exceptions, however Rollbar swallows them up and PHPUnit doesn't detect
            // them. So we catch and set the exception to be rethrown when outside Rollbar.
            try {
                $this->assertSame($first->class, $second->class);
                $this->assertSame($first->message, $second->message);
            } catch (PHPUnit_Framework_ExpectationFailedException $e) {
                $this->requestsMatchedException = $e;
            }

            return true;
        });

        VCR::configure()->enableRequestMatchers(['method', 'url', 'host', 'post_fields', 'exception']);
    }


}