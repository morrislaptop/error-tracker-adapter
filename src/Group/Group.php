<?php  namespace Morrislaptop\ErrorTracker\Group;

use Morrislaptop\ErrorTracker\Tracker;

interface Group extends Tracker
{

    /**
     * Adds a tracker.
     *
     * @param Tracker $tracker
     *
     * @return Group
     */
    public function add(Tracker $tracker);
}
