<?php namespace Morrislaptop\ErrorTracker\Adapter;

use Exception;
use Raygun4php\RaygunClient;

class Raygun extends AbstractAdapter
{
    /**
     * @var RaygunClient
     */
    protected $raygun;

    /**
     * @param RaygunClient $raygun
     */
    function __construct(RaygunClient $raygun)
    {
        $this->raygun = $raygun;
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
        return $this->raygun->SendException($e, null, $extra);
    }
}
