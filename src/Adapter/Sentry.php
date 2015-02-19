<?php namespace Morrislaptop\ErrorTracker\Adapter;

use Exception;
use Morrislaptop\ErrorTracker\ShutdownAwareTracker;
use Raven_Client;
use Raven_ErrorHandler;

class Sentry extends AbstractAdapter implements ShutdownAwareTracker {

    /**
     * @var Raven_Client
     */
    private $raven;

    /**
     * @var Raven_ErrorHandler
     */
    private $errorHandler;

    /**
     * @param Raven_Client $raven
     * @param Raven_ErrorHandler $errorHandler
     */
    function __construct(Raven_Client $raven, Raven_ErrorHandler $errorHandler = null)
    {
        $this->raven = $raven;
        if (!$this->errorHandler = $errorHandler) {
            $this->errorHandler = new Raven_ErrorHandler($raven);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function report(Exception $e, array $extra = [])
    {
        return $this->raven->getIdent($this->raven->captureException($e, ['extra' => $extra]));
    }

    /**
     * {@inheritDoc}
     */
    public function registerExceptionHandler()
    {
        $this->errorHandler->registerExceptionHandler();
    }

    /**
     * {@inheritDoc}
     */
    public function registerErrorHandler()
    {
        $this->errorHandler->registerErrorHandler();
    }

    /**
     * {@inheritDoc}
     */
    public function registerShutdownHandler()
    {
        $this->errorHandler->registerShutdownFunction();
    }
}