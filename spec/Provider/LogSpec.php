<?php

namespace spec\Morrislaptop\ErrorTracker\Provider;

use LogicException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class LogSpec extends ObjectBehavior
{
    /**
     * @var LoggerInterface
     */
    var $log;

    function let(LoggerInterface $log)
    {
        $this->log = $log;
        $this->beConstructedWith($log);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Provider\Log');
    }

    function it_logs_errors()
    {
        // Arrange.
        $exception = new LogicException('Test logging exceptions');
        $extra = ['only' => 'a test'];

        // Act.
        $this->report($exception, $extra);

        // Assert.
        $this->log->error((string) $exception, $extra)->shouldHaveBeenCalled();
    }
}
