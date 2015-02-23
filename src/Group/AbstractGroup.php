<?php namespace Morrislaptop\ErrorTracker\Group;

use Morrislaptop\ErrorTracker\Tracker;

abstract class AbstractGroup implements Group
{

    /**
     * @var Tracker[]
     */
    protected $trackers;

    /**
     * @param array $trackers
     */
    public function __construct(array $trackers = [])
    {
        $this->trackers = $trackers;
    }

    /**
     * Adds a tracker.
     *
     * @param Tracker $tracker
     *
     * @return Group
     */
    public function add(Tracker $tracker)
    {
        $this->trackers[] = $tracker;
        return $this;
    }
}
