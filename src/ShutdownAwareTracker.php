<?php namespace Morrislaptop\ErrorTracker;

interface ShutdownAwareTracker extends Tracker
{
    /**
     * Registers this tracker as THE exception handler
     */
    public function registerShutdownHandler();

}