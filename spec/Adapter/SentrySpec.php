<?php namespace spec\Morrislaptop\ErrorTracker\Adapter;

use Doctrine\Instantiator\Exception\UnexpectedValueException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Raven_Client;

class SentrySpec extends ObjectBehavior
{
    /**
     * @var Raven_Client
     */
    var $raven;

    function let(Raven_Client $raven)
    {
        $this->raven = $raven;
        $this->beConstructedWith($raven);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Adapter\Sentry');
    }

    function it_reports_exceptions()
    {
        // Arrange.
        $exception = new UnexpectedValueException();
        $event_id = 'hello, mum';
        $extra = ['php' => 'wins'];
        $this->raven->captureException($exception, ['extra' => $extra])->willReturn($event_id);
        $this->raven->getIdent($event_id)->willReturn($event_id);

        // Act.
        $this->report($exception, $extra);

        // Assert.
        $this->raven->captureException($exception, ['extra' => $extra])->shouldHaveBeenCalled();
        $this->raven->getIdent('hello, mum')->shouldHaveBeenCalled();
    }
}
