<?php namespace tests\Morrislaptop\ErrorTracker;

use VCR\VCR;

class TrackerTestCase extends \PHPUnit_Framework_TestCase {

    /**
     * If you have made a change and VCR is complaining that the request doesn't match,
     * delete the file in tests/fixtures/{request}.yml so that a new version can be
     * generated to match against.
     */
    public function setUp() {
        VCR::configure()->setCassettePath('tests/fixtures')->setMode(getenv('VCR_MODE'));
        $this->setMatchers();
    }

    /**
     * Configure the VCR on what properties of the request to match with a previous one.
     *
     * Use to remove properties we can't match (e.g. header with a timestamp).
     *
     * Built in matchers are 'method', 'url', 'host', 'headers', 'body', 'post_fields'
     *
     * Use to add matchers, e.g. ensuring the exception name is the same in the body.
     */
    public function setMatchers() {

    }

}