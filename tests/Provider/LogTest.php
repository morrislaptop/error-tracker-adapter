<?php namespace tests\Morrislaptop\ErrorTracker\Provider;

use LogicException;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Morrislaptop\ErrorTracker\Provider\Log;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;

class LogTest extends TrackerTestCase
{

    function testReport()
    {
        // Arrange.
        $log = new Logger('test');
        $handler = new TestHandler();
        $log->pushHandler($handler);
        $log = new Log($log);
        $exception = new LogicException('Testing logic exception');
        $extra = ['only', 'a', 'test'];

        // Act.
        $log->report($exception, $extra);

        // Assert.
        $record = $handler->getRecords()[0];
        $this->assertContains('LogicException', $record['message']);
        $this->assertContains('Testing logic exception', $record['message']);
        $this->assertContains('Stack trace', $record['message']);
        $this->assertSame($extra, $record['context']);
    }

}