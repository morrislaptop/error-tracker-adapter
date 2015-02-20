<?php namespace Morrislaptop\ErrorTracker\Adapter;

use Bugsnag_Client;
use Exception;

class Bugsnag extends AbstractAdapter {

    /**
     * @var Bugsnag_Client
     */
    private $bugsnag;

    /**
     * @param Bugsnag_Client $bugsnag
     */
    function __construct(Bugsnag_Client $bugsnag)
    {
        $this->bugsnag = $bugsnag;
    }

    /**
     * {@inheritDoc}
     */
    public function report(Exception $e, array $extra = [])
    {
        $this->bugsnag->setMetaData($extra);
        return $this->bugsnag->exceptionHandler($e);
    }

}