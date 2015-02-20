<?php namespace spec\Morrislaptop\ErrorTracker\Adapter;

use Airbrake\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UnexpectedValueException;

class AirbrakeSpec extends ObjectBehavior
{
    /**
     * @var Client
     */
    var $airbrake;

    function let(Client $airbrake) {
        $this->airbrake = $airbrake;
        $this->beConstructedWith($airbrake);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Adapter\Airbrake');
    }

    function it_reports_exceptions()
    {
        // Arrange.
        $exception = new UnexpectedValueException();
        $extra = ['php' => 'wins'];

        // Act.
        $this->report($exception, $extra);

        // Assert.
        $this->airbrake->notifyOnException($exception, $extra)->shouldHaveBeenCalled();
    }
}
