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
 * Application plugin resource
 *
 * @category    Drake
 * @package     Drake_Application
 * @subpackage  Resources
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Application_Resource_Plugin extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initialize the resource
     *
     * @return Drake_Event_Dispatcher
     */
    public function init()
    {
        $dispatcher = new Drake_Event_Dispatcher();
        Drake_Plugin::setDefaultDispatcher($dispatcher);

        $options = $this->getOptions();
        if (!isset($options['path'])) {
            throw new Drake_Application_Resource_InvalidArgumentException('Plugin path must be provided');
        }

        $namespace = 'Plugin';
        if (isset($options['namespace'])) {
            $namespace = $options['namespace'];
        }

        $dir = new DirectoryIterator($options['path']);
        foreach ($dir as $file) {
            if ($file->isFile() && (substr($file->getFilename(), -4, 4) == '.php')) {
                $class = $namespace . '_' . $this->_formatPluginName(substr($file->getFilename(), 0, -4));

                include_once $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename();

                if (!class_exists($class)) {
                    throw new Drake_Application_Resource_RuntimeException("Plugin file '{$file->getFilename()}' is found, but '{$class}' does not exist.");
                }

                // Initialize the plugin
                new $class;
            }
        }

        return $dispatcher;
    }

    /**
     * Format a plugin name
     *
     * @param string $name
     * @return string
     */
    protected function _formatPluginName($name)
    {
        return ucfirst(strtolower($name));
    }
}