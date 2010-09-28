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

/**
 * Test
 *
 * @category    Drake
 * @package     UnitTests
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Util_ArrayTest extends PHPUnit_Framework_TestCase
{
    protected $_util;

    public function setUp()
    {
        $this->_util = new Drake_Util_Array();
    }

    public function testShouldRecursivelyConvertObjects()
    {
        $data = new stdClass();
        $data->one = new stdClass();
        $data->one->foo = 'bar';
        $data->two = new stdClass();
        $data->two->bar = 'food';

        $target = array(
            'one' => array(
                'foo' => 'bar',
            ),
            'two' => array(
                'bar' => 'food',
            ),
        );

        $this->assertSame($this->_util->convertRecursive($data), $target);
    }
}