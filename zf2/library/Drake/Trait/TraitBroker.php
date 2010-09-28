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
class Drake_Trait_TraitBroker
{
    /**
     * Array of instance of objects extending Drake_Trait_TraitAbstract, indexed
     * by class.
     *
     * @var array 
     */
    protected $_traits = array();

    /**
     * Array of extended functionality methods provided by traits, indexed by
     * class.
     *
     * @var array
     */
    protected $_methods = array();

    /**
     * Array of object classes which have been initialized
     *
     * @var array
     */
    protected $_classes = array();

    /**
     * Register a trait
     *
     * @param Drake_Trait_TraitAbstract $trait
     * @param string$objectClass
     * @return Drake_Trait_TraitBroker
     */
    public function registerTrait(Drake_Trait_TraitAbstract $trait, $objectClass)
    {
        $traitClass = get_class($trait);
        if (isset($this->_traits[$objectClass])) {
            if (array_key_exists($traitClass, $this->_traits[$objectClass])) {
                throw new Drake_Trait_RuntimeException(
                    "Trait '$traitClass' is already registered on '$objectClass'");
            }
        }

        $this->registerMethods($trait, $objectClass);

        $this->_traits[$objectClass][$traitClass] = $trait;

        return $this;
    }

    /**
     * Unregister a trait
     *
     * @param Drake_Trait_TraitAbstract|string $trait
     * @param string $objectClass
     * @return Drake_Trait_TraitBroker
     * @throws Drake_Trait_RuntimeException When no trait is registered for $objectClass
     * @throws Drake_Trait_RuntimeException When $trait is not registered for $objectClass
     */
    public function unregisterTrait($trait, $objectClass)
    {
        if (!isset($this->_traits[$objectClass])) {
            throw new Drake_Trait_RuntimeException(
                "No traits registered for '$objectClass'");
        }

        if ($trait instanceof Drake_Trait_TraitAbstract) {
            $key = array_search($trait, $this->_traits[$objectClass], true);
            if (false === $key) {
                $traitClass = get_class($trait);
                throw new Drake_Trait_RuntimeException(
                    "Trait '$traitClass' is not registered");
            }
            unset($this->_traits[$objectClass][$key]);
        } elseif (is_string($trait)) {
            foreach ($this->_traits as $key => $result) {
                $type = get_class($trait);
                if ($trait === $type) {
                    unset($this->_traits[$objectClass][$key]);
                }
            }
        }
        
        return $this;
    }

    /**
     * Is a trait of a particular class registered?
     *
     * @param string $traitClass
     * @param string $objectClass
     * @return boolean
     */
    public function hasTrait($traitClass, $objectClass)
    {
        if (!isset($this->_traits[$objectClass])) {
            return false;
        }

        foreach ($this->_traits[$objectClass] as $trait) {
            $type = get_class($trait);
            if ($traitClass === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieve a trait by class
     *
     * @param string $traitClass
     * @param string $objectClass
     * @return Drake_Trait_TraitAbstract
     * @throws Drake_Trait_RuntimeException When $objectClass does not have any registered traits
     * @throws Drake_Trait_RuntimeException When $traitClass is not registered for $objectClass
     */
    public function getTrait($traitClass, $objectClass)
    {
        if (!isset($this->_traits[$objectClass])) {
            throw new Drake_Trait_RuntimeException(
                "No traits are registered for '$objectClass'");
        }

        foreach ($this->_traits[$objectClass] as $trait) {
            $type = get_class($trait);
            if ($traitClass === $type) {
                return $trait;
            }
        }

        throw new Drake_Trait_RuntimeException(
            "'$traitClass' is not registered for '$objectClass'");
    }

    /**
     * Return all traits registered to an object
     *
     * @param string $objectClass
     * @return array
     */
    public function getTraits($objectClass)
    {
        if (!$this->_traits[$objectClass]) {
            $this->_traits[$objectClass] = array();
        }
        return $this->_traits[$objectClass];
    }

    /**
     * Register trait methods with the broker
     *
     * @param Drake_Trait_TraitAbstract $trait
     * @param string $objectClass
     * @return Drake_Trait_TraitBroker
     */
    public function registerMethods(Drake_Trait_TraitAbstract $trait, $objectClass)
    {
        foreach ($trait->getMethods() as $method) {
            $this->registerMethod($trait, $method, $objectClass);
        }

        return $this;
    }

    /**
     * Register a trait method with the broker
     *
     * @param Drake_Trait_TraitAbstract $trait
     * @param string $method
     * @param string $objectClass
     * @return Drake_Trait_TraitBroker
     * @throws Drake_Trait_RuntimeException When $method has already been registered
     */
    public function registerMethod(Drake_Trait_TraitAbstract $trait, $method, $objectClass)
    {
        if (!isset($this->_methods[$objectClass])) {
            $this->_methods[$objectClass] = array();
        } else {
            if ($this->hasMethod($method, $objectClass)) {
                throw new Drake_Trait_RuntimeException(
                    "Method '$method' has already been registered.");
            }
        }

        $this->_methods[$objectClass][$method] = $trait;

        return $this;
    }

    /**
     * Return if the broker has a method for an object
     *
     * @param string $method
     * @param string $objectClass
     * @return boolean
     */
    public function hasMethod($method, $objectClass)
    {
        if (!isset($this->_methods[$objectClass])) {
            return false;
        }
        return array_key_exists($method, $this->_methods[$objectClass]);
    }

    /**
     * Call the trait method
     *
     * @param string $method
     * @param object $object
     * @param array $args
     * @return mixed
     * @throws Drake_Trait_LogicException When $object is not an object
     * @throws Drake_Trait_RuntimeException When $method is unregistered for $object
     */
    public function callMethod($method, $object, array $args = array())
    {
        if (!is_object($object)) {
            throw new Drake_Trait_LogicException(
                "Cannot call '$method' on '$object': Subject is not an object!");
        }
        
        $objectClass = get_class($object);

        if (!$this->hasMethod($method, $objectClass)) {
            throw new Drake_Trait_RuntimeException(
                "Cannot call unregistered method: '$method'.");
        }

        $trait = $this->_methods[$objectClass][$method];
        $args = array_merge(array($object), $args);
        
        $result = call_user_func_array(array($trait, $method), $args);

        return $result;
    }

    /**
     * Return if the provided class is registered with the broker
     *
     * @param string $objectClass
     * @return boolean
     */
    public function isClassRegistered($objectClass)
    {
        return (false !== array_search($objectClass, $this->_classes));
    }

    /**
     * Register a class with the broker
     *
     * @param string $objectClass
     * @return Drake_Trait_TraitBroker
     */
    public function registerClass($objectClass)
    {
        if (!$this->isClassRegistered($objectClass)) {
            $this->_classes[] = $objectClass;
        }

        return $this;
    }
}