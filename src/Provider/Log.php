<?php namespace Morrislaptop\ErrorTracker\Provider;

use Exception;
use Psr\Log\LoggerInterface;

class Log extends AbstractProvider
{
    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * @param LoggerInterface $log
     */
    function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * Reports the exception to the SaaS platform
     *
     * @param Exception $e
     * @param array $extra
     * @return mixed
     */
    public function report(Exception $e, array $extra = [])
    {
        $this->log->error((string) $e, $extra);
    }
}