<?php namespace Morrislaptop\ErrorTracker\Adapter;

use Airbrake\Client;
use Exception;

class Airbrake extends AbstractAdapter
{
    /**
     * @var Client
     */
    private $airbrake;

    /**
     * @param Client $airbrake
     */
    function __construct(Client $airbrake)
    {
        $this->airbrake = $airbrake;
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
        return $this->airbrake->notifyOnException($e, $extra);
    }
}
