<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Di
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Abstract injection container
 *
 * @category    Drake
 * @package     Drake_Di
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
*/
abstract class Drake_Di_ContainerAbstract implements Drake_Di_ContainerInterface
{
    /**
     * Cache object
     * @var Zend_Cache
     */
    protected $_cache;

    /**
     * Default cache object
     * @var Zend_Cache
     */
    protected static $_defaultCache;

    /**
     * Flag for enabling caching
     * @var boolean
     */
    protected $_cacheEnabled = true;

    /**
     * Container array
     *
     * @var array
     */
    protected $_container = array();

    /**
     * Singleton objects
     *
     * @var array
     */
    protected static $_singletons = array();

    /**
     * Constructor
     *
     * @param string $filepath
     * @return void
     */
    public function __construct($filepath, array $options = array())
    {
        $this->setOptions($options);
        $this->load($filepath);
        $this->init();
    }

    /**
     * Set the container options
     *
     * @param array $options
     * @return void
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . $this->_normalizeKey($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Initialize hook
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Get an object from the container
     *
     * @param string $id
     * @return object
     */
    public function getObject($id, array $args = array())
    {
        if (!isset($this->_container[$id])) {
            throw new Exception("Object with id '$id' doesn't exist");
        }

        $component = $this->_container[$id];
        
        if ($component['singleton']) {
            if (!isset(self::$_singletons[$id])) {
                self::$_singletons[$id] = $this->_initObject($component, $args);
            }
            $object = self::$_singletons[$id];
        } else {
            $object = $this->_initObject($component, $args);
        }

        return $object;
    }

    /**
     * Initializes a class either by evaluation or setter methods
     *
     * @param array $component
     * @param array $args
     * @return object
     */
    protected function _initObject($component, array $args = array())
    {
        $object = new $component['class']();
        foreach ($args as $key => $value) {
            $method = 'set' . $this->_normalizeKey($key);
            $object->$method($value);
        }

        return $object;
    }

    /**
     * Normalizes an init key
     *
     * @param string $key
     * @return string
     */
    protected function _normalizeKey($key)
    {
        $option = str_replace('_', ' ', strtolower($key));
        $option = str_replace(' ', '', ucwords($option));
        return $option;
    }

    /**
     * Load the container components
     *
     * @todo Convert to simplexml.
     *
     * @param string $filepath
     * @return void
     */
    public function load($filepath)
    {
        if ($this->getCacheEnabled()) {
            $cacheId = 'Container_' . md5($filepath);
            $result = $this->getCache()->load($cacheId);
            if ($result) {
                return $this->_container = unserialize($result);
            }
        }
        
        $xml = new DOMDocument();
        $xml->load($filepath);

        foreach ($xml->getElementsByTagName('component') as $component) {
            if (!$component->hasAttribute('id')) {
                throw new Drake_Di_RuntimeException(
                    'Injection component does not have an ID!');
            }
            $id = $component->getAttribute('id');

            if (!$component->hasChildNodes()) {
                throw new Drake_Di_RuntimeException(
                    "Component '$id' does not have a class mapped to it.");
            }

            $class = $component->getElementsByTagName('invoke')->item(0)->nodeValue;

            $singleton = false;
            if ($component->hasAttribute('singleton')) {
                $attr = $component->getAttribute('singleton');
                $singleton = (1 == $attr || 'true' == $attr);
            }

            $this->_container[$id] = array(
                'class' => $class,
                'singleton' => $singleton,
            );
        }

        if ($this->getCacheEnabled()) {
            $this->getCache()->save(serialize($this->_container), $cacheId);
        }
    }

    /**
     * Enable or disable caching
     *
     * @param boolean $flag
     * @return Drake_Di_ContainerAbstract
     */
    public function setCacheEnabled($flag = true)
    {
        $this->_cacheEnabled = (boolean) $flag;
        return $this;
    }

    /**
     * Get the cache enable flag
     *
     * @return boolean
     */
    public function getCacheEnabled()
    {
        return $this->_cacheEnabled;
    }

    /**
     * Set the cache
     *
     * @param Zend_Cache $cache
     * @return Drake_Di_ContainerAbstract
     */
    public function setCache(Zend_Cache $cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    /**
     * Get the cache
     *
     * @return Zend_Cache
     */
    public function getCache()
    {
        if (null === $this->_cache) {
            if (null === ($this->_cache = self::getDefaultCache())) {
                throw new Drake_Di_RuntimeException('Cache object was not defined!');
            }
        }
        return $this->_cache;
    }

    /**
     * Normalize keys coming in
     *
     * @param string $value
     * @return void
     */
    protected function _normalize($value)
    {
        return strtolower($value);
    }

    /**
     * Set the default values for attributes
     *
     *
     */
    public static function setAttributeDefault($attribute, $value)
    {
        self::$_attributeDefaults[$attribute] = $value;
    }

    /**
     * Set the default cache object
     *
     * @param Zend_Cache $cache
     * @return void
     */
    public static function setDefaultCache(Zend_Cache $cache)
    {
        self::$_defaultCache = $cache;
    }

    /**
     * Get the default cache object
     *
     * @return Zend_Cache
     */
    public static function getDefaultCache()
    {
        return self::$_defaultCache;
    }
}