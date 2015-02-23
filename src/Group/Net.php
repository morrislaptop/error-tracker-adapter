<?php namespace Morrislaptop\ErrorTracker\Group;

use Exception;
use Morrislaptop\ErrorTracker\Exception\GroupNotReported;

class Net extends AbstractGroup
{

    /**
     * Reports the exception to the SaaS platform
     *
     * @param Exception $e
     * @param array $extra
     * @return mixed
     */
    public function report(Exception $e, array $extra = [])
    {
        $exceptions = [];
        $results = [];

        foreach ($this->trackers as $tracker) {
            try {
                $results[get_class($tracker)] = $tracker->report($e, $extra);
            } catch (Exception $exception) {
                $exceptions[] = $exception;
            }
        }

        if (!$results) {
            throw new GroupNotReported(sprintf('Could not report: "%s".', $e->getMessage()), $exceptions);
        }

        return $results;
    }
}
