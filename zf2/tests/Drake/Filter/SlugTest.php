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
class Drake_Filter_SlugTest extends PHPUnit_Framework_TestCase
{
    protected $_filter;

    public function setUp()
    {
        $this->_filter = new Drake_Filter_Slug();
    }

    /**
     * @dataProvider slugProvider
     */
    public function testStringGetsConvertedToAUrlSlug($string, $expected)
    {
        $this->assertSame($expected, $this->_filter->filter($string));
    }

    public function slugProvider()
    {
        return array(
            array('LOWERcase', 'lowercase'),
            array('phrase with words', 'phrase-with-words'),
            array('multiple  spaces corrected', 'multiple-spaces-corrected'),
            array('underscores_and_$p3cial char[s]', 'underscores_and_-p3cial-char-s-'),
        );
    }
}