<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Object
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Base object class
 *
 * @category    Drake
 * @package     Drake_Object
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Object
{
    /**
     * The trait broker object
     *
     * @var Drake_Trait_TraitBroker
     */
    private static $_traitBroker;

    /**
     * Trait magic caller
     *
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws BadMethodCallException When an invalid method is called
     */
    public function __call($method, array $args = array())
    {
        $class = get_class($this);
        
        $traitBroker = $this->getTraitBroker();

        if (!$traitBroker->isClassRegistered($class)) {
            $this->_initTraits();
            $traitBroker->registerClass($class);
        }

        if ($traitBroker->hasMethod($method, $class)) {
            return $traitBroker->callMethod($method, $this, $args);
        }

        throw new BadMethodCallException('Invalid method called: ' 
            . get_class($this) . '::' . $method . '(' . print_r($args, 1) . ')');
    }

    /**
     * Get the trait broker
     *
     * @return Drake_Trait_TraitBroker
     */
    public function getTraitBroker()
    {
        if (!isset(self::$_traitBroker)) {
            self::$_traitBroker = new Drake_Trait_TraitBroker();
        }
        return self::$_traitBroker;
    }

    /**
     * Traits initialization
     *
     * @return void
     */
    protected function _initTraits()
    {
    }

    /**
     * Register a trait with the broker
     *
     * @param Drake_Trait_TraitAbstract $trait
     * @return void
     */
    protected function _registerTrait(Drake_Trait_TraitAbstract $trait)
    {
        $this->getTraitBroker()->registerTrait($trait, get_class($this));
    }
}