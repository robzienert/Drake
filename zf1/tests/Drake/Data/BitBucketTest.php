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
class Drake_Data_BitBucketTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidSwitches
     */
    public function testSwitchesProperlyConstructed($switches, $bits, $max)
    {
        $bb = new Drake_Data_BitBucket($switches);
        $bb->all();
        $this->assertSame($switches, array_values($bb->toArray()));
        $this->assertSame($max, $bb->toInt());
    }

    /**
     * @expectedException Drake_Data_RuntimeException
     */
    public function testExceptionThrownOnConflictingBitNames()
    {
        $bb = new Drake_Data_BitBucket(array('one', 'one'));
    }

    /**
     * @dataProvider provideValidSwitches
     */
    public function testNormalizeConvertsStringsToLower($switches)
    {
        $bb = new Drake_Data_BitBucket($switches);
        $this->assertSame('test', $bb->normalize('TEST'));
    }

    /**
     * @dataProvider provideValidSwitches
     */
    public function testIsValid($switches, $bits, $max)
    {
        $bb = new Drake_Data_BitBucket($switches);
        $this->assertTrue($bb->isValid(1));
        $this->assertTrue($bb->isValid($max - 1));
        $this->assertTrue($bb->isValid($max));
        $this->assertFalse($bb->isValid($max + 1));
    }

    /**
     * @dataProvider provideValidSwitches
     */
    public function testGetBits($switches, $bits)
    {
        $bb = new Drake_Data_BitBucket($switches);
        $this->assertSame($switches, array_keys($bb->getBits()));
        $this->assertSame($bits, array_values($bb->getBits()));
    }
    
    /**
     * @dataProvider provideValidSwitches
     */
    public function testGetBitWithValidNames($switches, $bits)
    {
        $bb = new Drake_Data_BitBucket($switches);
        for ($i = 0, $cnt = count($switches); $i < $cnt; $i++) {
            $this->assertSame($bits[$i], $bb->getBit($switches[$i]));
        }
    }

    /**
     * @dataProvider provideValidSwitches
     * @expectedException Drake_Data_UnexpectedValueException
     */
    public function testGetBitWithInvalidNameThrowsException($switches)
    {
        $bb = new Drake_Data_BitBucket($switches);
        $bb->getBit('invalid name');
    }

    /**
     * @dataProvider provideValidSwitches
     */
    public function testEqualsWithBitBucketObjects($switches)
    {
        $bb = new Drake_Data_BitBucket($switches);
        $bb2 = clone $bb;
        $this->assertTrue($bb->equals($bb2));

        $bb2->all();
        $this->setExpectedException('Drake_Data_UnexpectedValueException');
        $bb->equals($bb2);
    }

    /**
     * @expectedException Drake_Data_InvalidArgumentException
     */
    public function testExplodeThrowsExceptionOnInvalidArgument()
    {
        $bb = new Drake_Data_BitBucket(array('one', 'two'));
        $bb->explode('one');
    }

    public function testMassManipulatorMethods()
    {
        $bb = new Drake_Data_BitBucket(array('one', 'two'));
        $bb->all();
        $this->assertSame('3', (string) $bb);
        $bb->none();
        $this->assertSame('0', (string) $bb);
        $bb->invert();
        $this->assertSame('3', (string) $bb);
    }

    public function provideValidSwitches()
    {
        // Bit names, bit values, max
        return array(
            array(
                array(
                    'one', 'two', 'four', 'eight', 'sixteen', 'thirtytwo',
                    'sixtyfour', 'onetwentyeight', 'twofiftysix', 'fivetwelve',
                    'tentwentyfour'
                ),
                array(1, 2, 4, 8, 16, 32, 64, 128, 256, 512, 1024),
                2047
            )
        );
    }
}