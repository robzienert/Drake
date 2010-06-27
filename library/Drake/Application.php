<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Application
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

require_once 'Zend/Application.php';

/**
 * Needs documentation
 *
 * @category    Drake
 * @package     Drake_Application
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Application extends Zend_Application
{
    /**
     * Application configuration cache options
     *
     * @var array
     */
    protected $_cacheOptions = array();

    /**
     * Constructor
     *
     * @param string $environment
     * @param null|string|array $options
     */
    public function __construct($environment, $options = null)
    {
        if (is_array($options) && isset($options['configFile'])) {
            if (isset($options['cacheOptions'])) {
                $this->_cacheOptions = $options['cacheOptions'];
            }
            $options = $options['configFile'];
        }
        parent::__construct($environment, $options);
    }

    /**
     * Loads the configuration file from either cache or file
     *
     * @param string $file
     * @return array
     */
    protected function _loadConfig($file)
    {
        if (isset($this->_cacheOptions['enabled'])
            && false === $this->_cacheOptions['enabled']
        ) {
            return parent::_loadConfig($file);
        }
        
        $frontendType = (isset($this->_cacheOptions['frontendType']))
            ? $this->_cacheOptions['frontendType']
            : 'File';
        $backendType = (isset($this->_cacheOptions['backendType']))
            ? $this->_cacheOptions['backendType']
            : 'File';
        $frontendOptions = (isset($this->_cacheOptions['frontendOptions']))
            ? $this->_cacheOptions['frontendOptions']
            : array();
        $backendOptions = (isset($this->_cacheOptions['backendOptions']))
            ? $this->_cacheOptions['backendOptions']
            : array();

        require_once 'Zend/Cache.php';
        $cache = Zend_Cache::factory(
            $frontendType,
            $backendType,
            array_merge(array(
                'master_file' => $file,
                'automatic_serialization' => true,
            ), $frontendOptions),
            array_merge(array(
                'cache_dir' => sys_get_temp_dir()
            ), $backendOptions)
        );

        $config = $cache->load('Zend_Application_Config');
        if (!$config) {
            $config = parent::_loadConfig($file);
            $cache->save($config, 'Zend_Application_Config');
        }

        return $config;
    }
}