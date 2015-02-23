<?php namespace Morrislaptop\ErrorTracker\Adapter;

use Exception;
use Exceptiontrap as Exceptiontrap_Client;

class Exceptiontrap extends AbstractAdapter
{
    /**
     * @var Exceptiontrap_Client
     */
    protected $exceptiontrap;

    /**
     * @param Exceptiontrap_Client $exceptiontrap
     */
    public function __construct(Exceptiontrap_Client $exceptiontrap)
    {
        $this->exceptiontrap = $exceptiontrap;
    }

    /**
     * @param Exception $e
     * @param array $extra
     */
    public function report(Exception $e, array $extra = [])
    {
        if ($extra) {
            throw new \InvalidArgumentException('Exceptiontrap doesn\'t support context for exceptions');
        }

        $this->exceptiontrap->handleException($e);
    }
}
