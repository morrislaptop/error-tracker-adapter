<?php namespace Morrislaptop\ErrorTracker\Group;

use Exception;
use Morrislaptop\ErrorTracker\Exception\GroupNotReported;

class Chain extends AbstractGroup
{

    /**
     * Reports the exception to the SaaS platform
     *
     * @param Exception $e
     * @param array $extra
     * @return mixed
     * @throws ChainNoResult
     */
    public function report(Exception $e, array $extra = [])
    {
        $exceptions = [];
        foreach ($this->trackers as $tracker) {
            try {
                return $tracker->report($e, $extra);
            } catch (Exception $exception) {
                $exceptions[] = $exception;
            }
        }

        throw new GroupNotReported(sprintf('Could not report: "%s".', $e->getMessage()), $exceptions);
    }
}