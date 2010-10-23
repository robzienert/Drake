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

/**
 * @namespace
 */
namespace Drake\Application;

require_once 'Zend/Application.php';

/**
 * Needs documentation
 *
 * @category    Drake
 * @package     Drake_Application
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Application extends \Zend\Application\Application
{
    /**
     * Application configuration cache options
     *
     * @var array
     */
    protected $cacheOptions = array();

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
                $this->cacheOptions = $options['cacheOptions'];
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
    protected function loadConfig($file)
    {
        if (isset($this->cacheOptions['enabled'])
            && false === $this->cacheOptions['enabled']
        ) {
            return parent::loadConfig($file);
        }
        
        $frontendType = (isset($this->cacheOptions['frontendType']))
            ? $this->cacheOptions['frontendType']
            : 'File';
        $backendType = (isset($this->cacheOptions['backendType']))
            ? $this->cacheOptions['backendType']
            : 'File';
        $frontendOptions = (isset($this->cacheOptions['frontendOptions']))
            ? $this->cacheOptions['frontendOptions']
            : array();
        $backendOptions = (isset($this->cacheOptions['backendOptions']))
            ? $this->cacheOptions['backendOptions']
            : array();

        require_once 'Zend/Cache/Cache.php';
        $cache = \Zend\Cache\Cache::factory(
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

        $cacheId = crc32($file) . APPLICATION_ENV;

        $config = $cache->load($cacheId);
        if (!$config) {
            $config = parent::loadConfig($file);
            $cache->save($config, $cacheId);
        }

        return $config;
    }
}