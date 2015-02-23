<?php namespace spec\Morrislaptop\ErrorTracker\Adapter;

use Exceptiontrap;
use InvalidArgumentException;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UnexpectedValueException;

class ExceptiontrapSpec extends ObjectBehavior
{
    /**
     * @var Exceptiontrap
     */
    var $exceptiontrap;

    function let()
    {
        $this->exceptiontrap = new Exceptiontrap();
        $this->beConstructedWith($this->exceptiontrap);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Adapter\Exceptiontrap');
    }

    function it_reports_exceptions()
    {
        throw new SkippingException('Can\'t mock static Exceptiontrap client library');
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
