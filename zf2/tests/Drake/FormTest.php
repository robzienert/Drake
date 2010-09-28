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
class Drake_FormTest extends PHPUnit_Framework_TestCase
{
    protected $_form;

    public function setUp()
    {
        $this->_form = new Drake_Form();
        $this->_form->addElement('text', 'test');
    }

    public function testElementHasDlWrapperDecorator()
    {
        $element = $this->_form->getElement('test');
        $this->assertType('Drake_Form_Decorator_DlWrapper', $element->getDecorator('DlWrapper'));
    }

    public function testFormUsesDrakeDisplayGroupObject()
    {
        $this->_form->addDisplayGroup(array('test'), 'test_group');
        $this->assertType('Drake_Form_DisplayGroup', $this->_form->getDisplayGroup('test_group'));
    }
}