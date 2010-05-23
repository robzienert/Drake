<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     UnitTests
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'TestHelper.php';

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Drake_AllTests::main');
}

/**
 * Test
 *
 * @category    Drake
 * @package     UnitTests
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Event_DispatcherTest extends PHPUnit_Framework_TestCase
{
    protected $_dispatcher;

    public $dispatched;

    public function setUp()
    {
        $this->_dispatcher = new Drake_Event_Dispatcher();
        $this->dispatched = false;
    }

    public function testSubscribedListenersReceiveEvents()
    {
        $this->_dispatcher->dispatch(new Drake_Event($this, 'test.event'));
        $this->assertFalse($this->dispatched);
        $this->_dispatcher->subscribe('test.event', array($this, 'callback'));
        $this->_dispatcher->dispatch(new Drake_Event($this, 'test.event'));
        $this->assertTrue($this->dispatched);
    }
    
    public function testUnsubscribedListenersNoLongerReceieveEvents()
    {
        $this->testSubscribedListenersReceiveEvents();
        $this->dispatched = false;
        $this->_dispatcher->unsubscribe('test.event', array($this, 'callback'));
        $this->_dispatcher->dispatch(new Drake_Event($this, 'test.event'));
        $this->assertFalse($this->dispatched);
    }

    public function callback(Drake_Event $event)
    {
        $this->dispatched = true;
    }
}