<?php namespace Morrislaptop\ErrorTracker\Exception;

class GroupNotReported extends NotReported
{
    /**
     * Exceptions from chained providers
     *
     * @var array
     */
    private $exceptions = [];

    /**
     * Constructor
     *
     * @param string $message
     * @param array $exceptions Array of Exception instances
     */
    public function __construct($message = '', array $exceptions = [])
    {
        parent::__construct($message);
        $this->exceptions = $exceptions;
    }

    /**
     * Get the exceptions from chained providers
     *
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
