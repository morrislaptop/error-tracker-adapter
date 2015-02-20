<?php namespace Morrislaptop\ErrorTracker;

use ErrorException;
use Exception;

class ExceptionHandler
{
    /**
     * @var Tracker
     */
    private $tracker;

    /**
     * @param Tracker $tracker
     */
    function __construct(Tracker $tracker)
    {
        $this->tracker = $tracker;
    }

    /**
     * Bootstrap this class into the runtime
     */
    public function bootstrap() {
        error_reporting(-1);
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param  int $level
     * @param  string $message
     * @param  string $file
     * @param  int $line
     * @param  array $context
     * @throws ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = array())
    {
        if (error_reporting() & $level)
        {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param  \Exception $e
     * @throws Exception
     */
    public function handleException($e)
    {
        if (!getenv('APP_DEBUG')) {
            $this->tracker->report($e);
        }

        throw $e; // throw back to core PHP to render
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown()
    {
        if ( ! is_null($error = error_get_last()) && $this->isFatal($error['type']))
        {
            $this->handleException(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']));
        }
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param  int  $type
     * @return bool
     */
    protected function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

}