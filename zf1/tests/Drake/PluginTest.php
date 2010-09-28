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

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'TestHelper.php';

/**
 * Test
 *
 * @category    Drake
 * @package     UnitTests
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_PluginTest extends PHPUnit_Framework_TestCase
{
    protected $_dispatcher;

    public $dispatched;

    public function setUp()
    {
        $this->_dispatcher = new Drake_Event_Dispatcher();
        Drake_Plugin::setDefaultDispatcher($this->_dispatcher);
        $this->dispatched = false;
    }

    public function testListenerIsDispatchedOnEvent()
    {
        $plugin = new Drake_Plugin_MockPlugin();
        $this->assertFalse($this->dispatched);
        $this->_dispatcher->dispatch(new Drake_Event($this, 'test.plugin'));
        $this->assertTrue($this->dispatched);
    }

    public function testManualDispatcherInjection()
    {
        $plugin = new Drake_Plugin_MockPlugin(array(
            'dispatcher' => $this->_dispatcher
        ));

        $this->assertFalse($this->dispatched);
        $this->_dispatcher->dispatch(new Drake_Event($this, 'test.plugin'));
        $this->assertTrue($this->dispatched);
    }
}