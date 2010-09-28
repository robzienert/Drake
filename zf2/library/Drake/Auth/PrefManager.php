<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Auth
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Preference management system, based off PEAR's PrefManager package.
 *
 * PreferenceManager can be used for storing user and application preferences,
 * and most other forms of key/value pairs. If required, it can also fall
 * back to default values if a value is not defined.
 *
 * Uses a table with the following spec:
 *
 * CREATE TABLE `preferences` (
 *   `user_id` varchar( 255 ) NOT null default '',
 *   `pref_key` varchar( 32 ) NOT null default '',
 *   `pref_value` longtext NOT null ,
 *   PRIMARY KEY ( `user_id` , `pref_key` )
 * )
 *
 * @category    Drake
 * @package     Drake_Auth
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Auth_PrefManager
{
    /**
     * Database adapter
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private $_adapter;

    /**
     * Default database adapter
     *
     * @var Zend_Db_Adapter_Abstract
     */
    private static $_defaultAdapter;

    /**
     * The username to get preferences from if the user specified doesn't have
     * that preference set.
     *
     * @var string
     */
    private $_defaultUser = '__default__';

    /**
     * Switch to search for default values, or just fail when a specified user
     * does not have a preference set.
     *
     * @var boolean
     */
    private $_returnDefaults = true;

    /**
     * The table containing preferences
     *
     * @var string
     */
    private $_table = 'preferences';

    /**
     * The column containing user ids
     *
     * @var string
     */
    private $_userColumn = 'user_id';

    /**
     * The column containing preference keys
     *
     * @var string
     */
    private $_keyColumn = 'pref_key';

    /**
     * The column containing preference values
     *
     * @var string
     */
    private $_valueColumn = 'pref_value';

    /**
     * The default session cache namespace
     *
     * @var string
     */
    private $_defaultNamespace = __CLASS__;

    /**
     * The session cache namespace
     *
     * @var string
     */
    private $_namespace;

    /**
     * Whether to use the cache
     *
     * @var boolean
     */
    private $_cacheEnabled = true;

    /**
     * Defines whether values should be serialized before saving.
     *
     * @var boolean
     */
    private $_automaticSerialization = false;

    /**
     * Constructor
     *
     * Options:
     * - table: The table to get preferences from
     * - user_column: The field name for user ids
     * - key_column: The field name for preference keys
     * - value_column: The field name for preference values
     * - default_user: The user id assigned to default values
     * - namespace: The session namespace used for caching
     * - cache_enabled: Whether or not values should be cached
     * - automatic_serialization: Whether or not values should be automatically serialized
     *
     * @param array $options
     */
    public function __construct($options = null)
    {
        if (is_array($options)) {
            if (isset($options['table'])) {
                $this->_table = $options['table'];
            }
            if (isset($options['user_column'])) {
                $this->_userColumn = $options['user_column'];
            }
            if (isset($options['key_column'])) {
                $this->_keyColumn = $options['key_column'];
            }
            if (isset($options['value_column'])) {
                $this->_valueColumn = $options['value_column'];
            }
            if (isset($options['default_user'])) {
                $this->_defaultUser = $options['default_user'];
            }
            if (isset($options['namespace'])) {
                $this->_namespace = $options['namespace'];
            }
            if (isset($options['cache_enabled'])) {
                $this->_cacheEnabled = $options['cache_enabled'];
            }
            if (isset($options['automatic_serialization'])) {
                $this->_automaticSerialization = $options['automatic_serialization'];
            }
        }
    }

    /**
     * Cleans out the cache
     *
     */
    public function clearCache()
    {
        unset($_SESSION[$this->_namespace]);
    }

    /**
     * Sets whether the cache is enabled
     *
     * @param boolean $flag
     */
    public function setCacheEnabled($flag = true)
    {
        $this->_cacheEnabled = (boolean) $flag;
        if ($this->_cacheEnabled) {
            if (!isset($_SESSION[$this->getNamespace()])
                || !is_array($_SESSION[$this->getNamespace()])
            ) {
                $_SESSION[$this->getNamespace()] = array();
            }
        }
    }

    /**
     * Get the session namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        if (null === $this->_namespace) {
            $this->_namespace = $this->_defaultNamespace;
        }
        return $this->_namespace;
    }

    /**
     * Sets whether defaults should be returned if a user doesn't have a
     * specified value set.
     *
     * @param boolean $returnDefaults
     */
    public function setReturnDefaults($returnDefaults = true)
    {
        $this->_returnDefaults = (boolean) $returnDefaults;
    }

    /**
     * Get a preference for the specified user, or, if returning default values
     * is enabled, the default.
     *
     * @param string $userId
     * @param string $prefKey
     * @param boolean $showDefaults
     * @return mixed
     */
    public function get($userId, $prefKey, $showDefaults = true)
    {
        if ($this->_cacheEnabled
            && isset($_SESSION[$this->getNamespace()][$userId][$prefKey])
        ) {
            return $_SESSION[$this->getNamespace()][$userId][$prefKey];
        }

        $select = $this->getAdpater()->select();
        $select->where($this->_userColumn . ' = ?', $userId)
               ->where($this->_keyColumn . ' = ?', $prefKey);
        $result = $this->getAdapter()->fetchRow($select);

        if (count($result) > 0) {
            $value = $this->_unserialize($result[$this->_valueColumn]);
            if ($this->_cacheEnabled) {
                $_SESSION[$this->getNamespace()][$userId][$prefKey] = $value;
            }
        } elseif ($this->_returnDefaults && $showDefaults) {
            $value = $this->getDefault($prefKey);
        } else {
            $value = null;
        }

        return $value;
    }

    /**
     * Get the default value for a preference
     *
     * @param string $prefKey
     */
    public function getDefault($prefKey)
    {
        return $this->get($this->getDefaultUser(), $prefKey, false);
    }

    /**
     * Set a preference for a user
     *
     * @param string $userId
     * @param string $prefKey
     * @param mixed $value
     */
    public function set($userId, $prefKey, $value)
    {
        $exists = $this->_exists($userId, $prefKey);
        if ($exists) {
            $select = $this->getAdapter()->select();
            $select->where($this->_userColumn . ' = ?', $userId)
                   ->where($this->_keyColumn . ' = ?', $prefKey);

            $result = $this->getAdpater()->update($this->_table, array(
                $this->_valueColumn => $this->_serialize($value),
            ), $select);
        } else {
            $result = $this->getAdapter()->insert($this->_table, array(
                $this->_valueColumn => $this->_serialize($value),
            ));
        }

        if ($result) {
            $_SESSION[$this->getNamespace()][$userId][$prefId] = $value;
        }

        return ($result > 0);
    }

    /**
     * Set a preference default
     *
     * @param string $prefKey
     * @param string $value
     */
    public function setDefault($prefKey, $value)
    {
        $this->set($this->getDefaultUser(), $prefKey, $value);
    }

    /**
     * Delete a preference for a user
     *
     * @param string $userId
     * @param string $prefKey
     */
    public function delete($userId, $prefKey)
    {
        $exists = $this->_exists($userId, $prefKey);
        if ($exists) {
            $select = $this->getAdapter()->select();
            $select->where($this->_userColumn . ' = ?', $userId)
                   ->where($this->_keyColumn . ' = ?', $prefKey);

            $result = $this->getAdpater()->delete($this->_table, $select);
            if (0 === $result) {
                return false;
            }
        }
        return true;
    }

    /**
     * Delete a default preference
     *
     * @param string $prefKey
     */
    public function deleteDefault($prefKey)
    {
        $this->delete($this->getDefaultUser(), $prefKey);
    }

    /**
     * Returns whether or not a userid/pref key match exists in the database.
     *
     * @param string $userId
     * @param string $prefKey
     * @return boolean
     */
    private function _exists($userId, $prefKey)
    {
        $select = $this->getAdapter()->select();
        $select->from($this->_table, array('COUNT(1)'))
               ->where($this->_userColumn . ' = ?', $userId)
               ->where($this->_keyColumn . ' = ?', $prefKey);
        
        $result = $this->getAdapter()->fetchCol($select);

        $count = current($result);

        return ($count > 0);
    }

    /**
     * Get the default user
     *
     * @return string
     */
    public function getDefaultUser()
    {
        return $this->_defaultUser;
    }

    /**
     * Optionally prepares values for saving into the database
     *
     * @param mixed $value
     * @return string
     */
    private function _serialize($value)
    {
        if ($this->_automaticSerialization) {
            return serialize($value);
        }
        return $value;
    }

    /**
     * Optionally unserializes values from the database; returning them to their
     * original state.
     *
     * @param string $value
     * @return mixed
     */
    private function _unserialize($value)
    {
        if ($this->_automaticSerialization) {
            return unserialize($value);
        }
        return $value;
    }

    /**
     * Get the database adapter
     *
     * @throws Drake_Auth_RuntimeException When no adapter has been set
     * @return Zend_Db_Adapter_Abstract
     */
    public function getAdapter()
    {
        if (null === $this->_adapter) {
            $adapter = self::getDefaultAdapter();
            if (null === $adapter) {
                throw new Drake_Auth_RuntimeException('Adapter and default adapter have not been set!');
            }
            $this->_adapter = $adapter;
        }
        
        return $this->_adapter;
    }

    /**
     * Set the database adapter
     *
     * @param Zend_Db_Adapter_Abstract $adapter
     */
    public function setAdapter(Zend_Db_Adapter_Abstract $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Get the default database adapter
     *
     * @return Zend_Db_Adpater_Abstract
     */
    public static function getDefaultAdapter()
    {
        return self::$_defaultAdapter;
    }

    /**
     * Set the default database adapter
     *
     * @param Zend_Db_Adapter_Abstract $adapter
     */
    public static function setDefaultAdapter(Zend_Db_Adapter_Abstract $adapter)
    {
        self::$_defaultAdapter = $adapter;
    }
}
