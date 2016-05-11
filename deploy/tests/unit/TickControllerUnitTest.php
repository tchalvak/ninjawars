<?php
namespace NinjaWars\tests\unit;

use \PHPUnit_Framework_TestCase;
use NinjaWars\core\control\TickController;
use NinjaWars\tests\MockGameLog;

class TickControllerUnitTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
    }

	protected function tearDown() {
    }

    function testTickControllerInstantiates() {
        $tick = new TickController(new MockGameLog());
        $this->assertTrue($tick instanceof TickController);
    }

    function testTickRunsVariousTicksWithoutErrors() {

        $logger = new MockGameLog();
        $tick = new TickController($logger);
        $tick->atomic();
        $tick->tiny();
        $tick->minor();
        $tick->major();
        $tick->nightly();
        $this->assertTrue(true); // Just has to be reached in terms of a sanity check
    }
}
