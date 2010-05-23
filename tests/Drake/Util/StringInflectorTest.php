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
class Drake_Util_StringInflectorTest extends PHPUnit_Framework_TestCase
{
    protected $_util;

    public function setUp()
    {
        $this->_util = new Drake_Util_StringInflector();
    }

    /**
     * @dataProvider camelizeProvider
     */
    public function testCamelizeStrings($string, $expected)
    {
        $this->assertSame($expected, $this->_util->camelize($string));
    }

    /**
     * @dataProvider underscoreProvider
     */
    public function testUnderscore($string, $expected)
    {
        $this->assertSame($expected, $this->_util->underscore($string));
    }

    /**
     * @dataProvider sluggifyProvider
     */
    public function testSluggify($string, $expected)
    {
        $this->assertSame($expected, $this->_util->sluggify($string));
    }

    public function camelizeProvider()
    {
        return array(
            array('lowercase', 'Lowercase'),
            array('SomethingInterface', 'SomethingInterface'),
            array('Some_Class', 'SomeClass'),
            array('Some\Namespace', 'SomeNamespace'),
            array('TWO.HUNDRED', 'TWOHUNDRED'),
            array('phrase with words', 'PhraseWithWords'),
        );
    }

    public function underscoreProvider()
    {
        return array(
            array('lowercase', 'lowercase'),
            array('SomethingInterface', 'something_interface'),
            array('someVariable', 'some_variable'),
            array('phrase with words', 'phrase with words')
        );
    }

    public function sluggifyProvider()
    {
        return array(
            array('LOWERcase', 'lowercase'),
            array('phrase with words', 'phrase-with-words'),
            array('multiple  spaces corrected', 'multiple-spaces-corrected'),
            array('underscores_and_$p3cial char[s]', 'underscores_and_-p3cial-char-s-'),
        );
    }

}