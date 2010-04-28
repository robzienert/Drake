<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Trait
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Trait-like functionality for PHP.
 *
 * Originally developed by Steve Hollis (steve@hlmenterprises.co.uk).
 *
 * @category    Drake
 * @package     Drake_Trait
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class Drake_Trait_TraitAbstract
{
    /**
     * Array of methods offering extended functionality
     *
     * @var array
     */
    protected $_methods;

    /**
     * Array of additionally exluded methods from the trait
     *
     * @var array
     */
    protected $_excludeMethods = array();

    /**
     * Constructor
     *
     * @param array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->_setOptions($options);
        $this->_init();
    }

    /**
     * Set trait options
     *
     * @param array $options
     * @return void
     */
    protected function _setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = '_set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Get the methods
     *
     * @return array
     */
    public function getMethods()
    {
        if (null === $this->_methods) {
            $this->_methods = array();
            $this->_retrieveMethods();
        }
        return $this->_methods;
    }

    /**
     * Retrieve the trait methods
     *
     * @return void
     */
    private function _retrieveMethods()
    {
        $exclude = array_merge(array(
            '__construct',
            'getMethods',
        ), $this->_excludeMethods);

        $ro = new ReflectionObject($this);

        foreach ($ro->getMethods() as $method) {
            if ($method->isPublic()) {
                $name = $method->getName();
                if (!in_array($name, $exclude)) {
                    $this->_addMethod($name);
                }
            }
        }
    }

    /**
     * Add a method to the methods list
     *
     * @param string $method
     * @return void
     * @throws Drake_Trait_LogicException When a method does not exist in the trait
     * @throws Drake_Trait_LogicException When a method has already been added
     */
    private function _addMethod($method)
    {
        if (!method_exist($this, $method)) {
            throw new Drake_Trait_LogicException("Method '$method' does not exist.");
        }
        if (array_search($method, $this->_methods)) {
            throw new Drake_Trait_LogicException("Duplicate method '$method'.");
        }
        $this->_methods[] = $method;
    }
}
