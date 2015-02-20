<?php namespace Morrislaptop\ErrorTracker\Adapter;

use Exception;
use Morrislaptop\ErrorTracker\ShutdownAwareTracker;
use Raven_Client;
use Raven_ErrorHandler;

class Sentry extends AbstractAdapter {

    /**
     * @var Raven_Client
     */
    private $raven;

    /**
     * @param Raven_Client $raven
     */
    function __construct(Raven_Client $raven)
    {
        $this->raven = $raven;
    }

    /**
     * {@inheritDoc}
     */
    public function report(Exception $e, array $extra = [])
    {
        return $this->raven->getIdent($this->raven->captureException($e, ['extra' => $extra]));
    }

}