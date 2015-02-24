<?php namespace spec\Morrislaptop\ErrorTracker\Adapter;

use Raygun4php\RaygunClient;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use UnexpectedValueException;

class RaygunSpec extends ObjectBehavior
{
    /**
     * @var RaygunClient
     */
    var $raygun;

    function let(RaygunClient $raygun)
    {
        $this->raygun = $raygun;
        $this->beConstructedWith($raygun);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Adapter\Raygun');
    }

    function it_reports_exceptions()
    {
        // Arrange.
        $exception = new UnexpectedValueException();
        $extra = ['php' => 'wins'];

        // Act.
        $this->report($exception, $extra);

        // Assert.
        $this->raygun->SendException($exception, null, $extra)->shouldHaveBeenCalled();
    }
}
