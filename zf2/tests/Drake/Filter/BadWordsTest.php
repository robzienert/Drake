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
class Drake_Filter_BadWordsTest extends PHPUnit_Framework_TestCase
{
    protected $_filter;

    public function setUp()
    {
        $this->_filter = new Drake_Filter_BadWords();
    }

    /**
     * @dataProvider badwordsProvider
     */
    public function testFitlerRemovesWordsFoundInSourceFile($string, $expected)
    {
        $this->assertSame($expected, $this->_filter->filter($string));
    }

    public function badwordsProvider()
    {
        return array(
            array('Sentence without any bad words', 'Sentence without any bad words'),
            array('Sentence with a bad fucking word', 'Sentence with a bad word'),
            array('RAWR SHIT IM MAD', 'RAWR IM MAD'),
        );
    }
}