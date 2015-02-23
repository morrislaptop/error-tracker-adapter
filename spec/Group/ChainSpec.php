<?php

namespace spec\Morrislaptop\ErrorTracker\Group;

use Exception;
use InvalidArgumentException;
use Morrislaptop\ErrorTracker\Exception\GroupNotReported;
use Morrislaptop\ErrorTracker\Tracker;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChainSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Group\Chain');
    }

    function it_should_only_report_once(Tracker $t1, Tracker $t2, Tracker $t3)
    {
        // Arrange.
        $exception = new InvalidArgumentException('error in app');
        $extra = ['phpspec' => 'test'];
        $t1->report($exception, $extra)->willThrow(new Exception('t1 throw'));
        $t2->report($exception, $extra)->willReturn('t2 reported');
        $this->add($t1);
        $this->add($t2);
        $this->add($t3);

        // Act.
        $report = $this->report($exception, $extra);

        // Assert.
        $report->shouldBe('t2 reported');
        $t1->report($exception, $extra)->shouldHaveBeenCalled();
        $t2->report($exception, $extra)->shouldHaveBeenCalled();
        $t3->report($exception, $extra)->shouldNotHaveBeenCalled();
    }

    function it_should_throw_an_exception_on_no_reports(Tracker $t1, Tracker $t2, Tracker $t3)
    {
        // Arrange.
        $exception = new InvalidArgumentException('error in app');
        $extra = ['phpspec' => 'test'];
        $t1->report($exception, $extra)->willThrow(new Exception('t1 throw'));
        $t2->report($exception, $extra)->willThrow(new Exception('t2 throw'));
        $t3->report($exception, $extra)->willThrow(new Exception('t3 throw'));
        $this->add($t1);
        $this->add($t2);
        $this->add($t3);

        // Act & Assert..
        $this->shouldThrow(GroupNotReported::class)->duringReport($exception, $extra);
    }
}
