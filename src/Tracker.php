<?php namespace Morrislaptop\ErrorTracker;

use Exception;

interface Tracker
{
    /**
     * Reports the exception to the SaaS platform
     *
     * @param Exception $e
     * @param array $extra
     * @return mixed
     */
    public function report(Exception $e, array $extra = []);

}