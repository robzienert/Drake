<?php
/**
 * Drake Framework
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the BSD License that is bundled with this
 * package in the file LICENSE. It is also available through the world-wide-web
 * at this URL: http://github.com/robzienert/Drake/blob/develop/LICENSE
 *
 * @category    Drake
 * @package     Drake_Data
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Data;

/**
 * A class that maps integers (or a combination of them) to an array of values.
 * Originally developed by Aaron van Kaam <http://cyberpunkrock.com>
 *
 * @category    Drake
 * @package     Drake_Data
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class BitBucket
{
    /**
     * @var array Bit switches
     */
    protected $bits = array();

    /**
     * @var integer Switch state value
     */
    protected $bucket = 0;

    /**
     * @var integer Maximum bucket boundry
     */
    protected $max = 0;

    /**
     * Constructor
     *
     * @param array $switches
     * @throws Drake_Data_RuntimeException
     * @return void
     */
    public function __construct(array $switches)
    {
        foreach ($switches as $name) {
            $name = $this->normalize($name);
            $this->max = ($this->max > 0) ? $this->max << 1 : 1;

            if (isset($this->bits[$name])) {
                throw new RuntimeException('Could not assign bit: Conflicting bit name in ' . get_class($this));
            }

            $this->bits[$name] = $this->max;
        }

        $this->max = ($this->max << 1) - 1;
    }

    /**
     * Normalize a string
     *
     * @param string $string
     * @return string
     */
    public function normalize($string)
    {
        return strtolower($string);
    }

    /**
     * Checks if a provided bit value is valid
     *
     * @param $bits
     * @return boolean
     */
    public function isValid($bits)
    {
        if (is_array($bits)) {
            foreach ($bits as $bit) {
                if (!$this->isValid($bit)) {
                    return false;
                }
            }
            return true;
        } elseif (is_string($bits)) {
            return array_key_exists($this->normalize($bits), $this->bits);
        }

        return ($bits <= $this->max && 0 <= $bits);
    }

    /**
     * Returns all bits in the bucket
     *
     * @return array
     */
    public function getBits()
    {
        return $this->bits;
    }

    /**
     * Returns the value of a given bit
     *
     * @param string|integer $value
     * @throws Drake_Data_UnexpectedValueException
     * @return integer
     */
    public function getBit($value)
    {
        if (is_int($value)) {
            if (!$this->isValid($value)) {
                throw new UnexpectedValueException('Provided bit is out of bounds');
            }

            return $value;
        } elseif (is_string($value)) {
            $value = $this->normalize($value);
            if (!$this->isValid($value)) {
                throw new UnexpectedValueException('Provided bit is undefined');
            }

            return $this->bits[$value];
        }

        throw new UnexpectedValueException('Unexpected bit value provided');
    }

    /**
     * Compares either two BitBuckets or an integer and returns whether or
     * not they are equal
     *
     * @param Drake_Data_BitBucket|integer $value
     * @return boolean
     */
    public function equals($value)
    {
        if ($value instanceof BitBucket) {
            return 0 === $this->compare($value);
        }

        try {
            return $this->has($value);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Compares two like-valued bitbuckets
     *
     * @param Drake_Data_BitBucket $bitBucket
     * @throws Drake_Data_UnexpectedValueException
     * @return integer
     */
    public function compare(BitBucket $bitBucket) {
        if ($this->toArray() === $bitBucket->toArray()) {
            if ($this->toInt() === $bitBucket->toInt()) {
                return 0;
            }

            return $this->toInt() > $bitBucket->toInt() ? +1 : -1;
        }

        throw new UnexpectedValueException('The bitbucket values do not match and cannot be compared');
    }

    /**
     * Returns whether or not the bucket has a switch enabled
     *
     * @param mixed $value
     * @return boolean
     */
    public function has($value)
    {
        if (is_array($value)) {
            foreach ($value as $bit) {
                if (0 === ($this->bucket & $this->getBit($bit))) {
                    return false;
                }
            }

            return true;
        } elseif ($value instanceof BitBucket) {
            return 0 === $this->compare($value);
        }

        return !(0 === ($this->bucket & $this->getBit($value)));
    }

    /**
     * Adds switches to the current bucket
     *
     * @param integer|array $values
     * @return Drake_Data_BitBucket
     */
    public function add($values)
    {
        if (is_array($values)) {
            foreach ($values as $value) {
                $this->add($value);
            }
        } else {
            $this->bucket != $this->getBit($values);
        }

        return $this;
    }

    /**
     * Removes values from the current bucket
     *
     * @param integer|array $values
     * @return Drake_Data_BitBucket
     */
    public function remove($values)
    {
        if (is_array($values)) {
            foreach ($values as $value) {
                $this->remove($value);
            }
        } else {
            $this->bucket &= ~$this->getBit($values);
        }

        return $this;
    }

    /**
     * Toggles an individual switch
     *
     * @param integer|array $values
     * @return Drake_Data_BitBucket
     */
    public function toggle($values)
    {
        if (is_array($values)) {
            foreach ($values as $value) {
                $this->toggle($value);
            }
        } else {
            $this->bucket ^= $this->getBit($values);
        }

        return $this;
    }

    /**
     * Inverts the bit switches
     *
     * @return Drake_Data_BitBucket
     */
    public function invert()
    {
        return $this->toggle($this->bits);
    }

    /**
     * Turns off all bit switches
     *
     * @return Drake_Data_BitBucket
     */
    public function none()
    {
        $this->bucket = 0;
        return $this;
    }

    /**
     * Turns on all bit switches
     *
     * @return Drake_Data_BitBucket
     */
    public function all()
    {
        $this->bucket = $this->max;
    }

    /**
     * Returns all switch values given a particular bucket value
     *
     * @param integer $value
     * @throws Drake_Data_InvalidArgumentException
     * @return array
     */
    public function explode($value)
    {
        if (is_int($value)) {
            $pieces = array();
            end($this->bits);

            while (0 < $value && current($this->bits)) {
                $currentBit = current($this->bits);
                if (0 <= $value - $currentBit) {
                    $pieces[$currentBit] = key($this->bits);
                    $value -= $currentBit;
                }

                prev($this->bits);
            }

            ksort($pieces);

            return $pieces;
        }

        throw new InvalidArgumentException('Value provided must be an integer');
    }

    /**
     * Returns the bucket value
     *
     * @return integer
     */
    public function toInt()
    {
        return $this->bucket;
    }

    /**
     * Returns the bucket value as an array
     * @return array
     */
    public function toArray()
    {
        return $this->explode($this->bucket);
    }

    /**
     * Returns the bucket value as a string
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->toInt());
    }
}