<?php namespace Morrislaptop\ErrorTracker\Adapter;

use Exception;
use RollbarNotifier;

class Rollbar extends AbstractAdapter
{
    /**
     * @var RollbarNotifier
     */
    protected $rollbar;

    /**
     * @param RollbarNotifier $rollbar
     */
    function __construct(RollbarNotifier $rollbar)
    {
        $this->rollbar = $rollbar;
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
        if ($extra) {
            throw new \InvalidArgumentException('Rollbar doesn\'t support context for exceptions');
        }

        $this->rollbar->report_exception($e);
    }
}
