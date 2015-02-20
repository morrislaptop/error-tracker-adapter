<?php namespace spec\Morrislaptop\ErrorTracker\Adapter;

use Bugsnag_Client;
use UnexpectedValueException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BugsnagSpec extends ObjectBehavior
{
    /**
     * @var Bugsnag_Client
     */
    var $bugsnag;

    function let(Bugsnag_Client $bugsnag)
    {
        $this->bugsnag = $bugsnag;
        $this->beConstructedWith($bugsnag);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Adapter\Bugsnag');
    }

    function it_reports_exceptions()
    {
        // Arrange.
        $exception = new UnexpectedValueException();
        $extra = ['php' => 'wins'];

        // Act.
        $this->report($exception, $extra);

        // Assert.
        $this->bugsnag->setMetaData($extra)->shouldHaveBeenCalled();
        $this->bugsnag->exceptionHandler($exception)->shouldHaveBeenCalled();
    }
}
