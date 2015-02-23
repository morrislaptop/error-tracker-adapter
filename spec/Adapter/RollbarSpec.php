<?php

namespace spec\Morrislaptop\ErrorTracker\Adapter;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RollbarNotifier;
use UnexpectedValueException;

class RollbarSpec extends ObjectBehavior
{
    /**
     * @var RollbarNotifier
     */
    var $rollbar;

    function let(RollbarNotifier $rollbar)
    {
        $this->rollbar = $rollbar;
        $this->beConstructedWith($rollbar);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Adapter\Rollbar');
    }

    function it_reports_exceptions()
    {
        // Arrange.
        $exception = new UnexpectedValueException();
        $extra = [];

        // Act.
        $this->report($exception, $extra);

        // Assert.
        $this->rollbar->report_exception($exception)->shouldHaveBeenCalled();
    }

    function it_throws_an_exception_with_extra_data()
    {
        // Arrange.
        $exception = new UnexpectedValueException();
        $extra = ['user_id' => 2343432424];

        // Act & Assert.
        $this->shouldThrow(InvalidArgumentException::class)->duringReport($exception, $extra);
    }
}
