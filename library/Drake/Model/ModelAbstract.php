<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Model
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Needs documentation
 *
 * @category    Drake
 * @package     Drake_Model
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class Drake_Model_ModelAbstract
{
    /**
     * Map a call to set a property to its corresponding mutator if it exists.
     * Otherwise, set the property directly.
     *
     * Ignore any properties that begin with an underscore so not all of our
     * protected properties are exposed.
     *
     * @author Court Ewing <www.epixa.com>
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        if ('_' != $name[0]) {
            $method = 'set' . ucfirst($name);
            if (method_exists($this, $method)) {
                return $this->$method($value);
            }

            if (property_exists($this, $name)) {
                $this->$name = $value;
                return;
            }
        }

        throw new LogicException("No property exists by `$name`");
    }

    /**
     * Map a call to get a property to its corresponding accessor if it exists.
     * Otherwise, get the property directly.
     *
     * Ignore any properties that begin with an underscore so not all of our
     * protected properties are exposed.
     *
     * @author Court Ewing <www.epixa.com>
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ('_' != $name[0]) {
            $method = 'get'. ucfirst($name);
            if (method_exists($this, $method)) {
                return $this->$method();
            }

            if (property_exists($this, $name)) {
                return $this->$name;
            }
        }

        throw new LogicException("No property exists by `$name`");
    }

    /**
     * Map a call to a non-existent mutator or accessor directly to its
     * corresponding property
     *
     * @author Court Ewing <www.epixa.com>
     * @param string $name
     * @param array $arguments
     * @return Drake_Model_ModelAbstract
     * @throws BadMethodCallException If no mutator/accessor can be found
     */
    public function  __call($name, $arguments)
    {
        if (strlen($name) > 3) {
            if (0 === strpos($name, 'set')) {
                $property = lcfirst(substr($name, 3));
                $this->$property = array_shift($arguments);
                return $this;
            }

            if (0 === strpos($name, 'get')) {
                $property = lcfirst(substr($name, 3));
                return $this->$property;
            }
        }

        throw new BadMethodCallException("No method named `$name` exists");
    }
}