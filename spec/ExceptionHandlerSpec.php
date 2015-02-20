<?php namespace spec\Morrislaptop\ErrorTracker;

use ErrorException;
use Exception;
use Morrislaptop\ErrorTracker\Tracker;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExceptionHandlerSpec extends ObjectBehavior
{
    /**
     * @var Tracker
     */
    var $tracker;

    function let(Tracker $tracker)
    {
        putenv('APP_DEBUG=0');
        $this->tracker = $tracker;
        $this->beConstructedWith($tracker);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\ExceptionHandler');
    }

    function it_converts_errors_to_exceptions()
    {
        // Arrange.
        $error = [
            'level' => E_NOTICE,
            $message = 'Error from phpspec',
            $file = __FILE__,
            $line = __LINE__
        ];

        // Act & Assert.
        $this->shouldThrow(ErrorException::class)->during('handleError', $error);
    }

    function it_rethrows_exceptions()
    {
        // Arrange.
        $exception = new Exception();

        // Act & Assert.
        $this->shouldThrow(Exception::class)->duringHandleException($exception);
    }

    function it_reports_when_not_in_debug()
    {
        // Arrange.
        $exception = new Exception();

        // Act & Assert.
        $this->shouldThrow(Exception::class)->duringHandleException($exception);
        $this->tracker->report($exception)->shouldHaveBeenCalled();
    }

    function it_doesnt_report_when_in_debug()
    {
        // Arrange.
        putenv('APP_DEBUG=1');
        $exception = new Exception();

        // Act & Assert.
        $this->shouldThrow(Exception::class)->duringHandleException($exception);
        $this->tracker->report($exception)->shouldNotHaveBeenCalled();
    }

}
