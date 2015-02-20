<?php namespace Morrislaptop\ErrorTracker\Adapter;

use Raven_Client;
use Exception;

class Sentry extends AbstractAdapter
{

    /**
     * @var Raven_Client
     */
    private $raven;

    /**
     * @param Raven_Client $raven
     */
    public function __construct(Raven_Client $raven)
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
