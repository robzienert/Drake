<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Controller
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake\Controller\Plugin;

/**
 * Application language and locale detection by request
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Locale extends \Zend\Controller\Plugin\AbstractHelper
{
    /**
     * Sets the application locale and translation based on the lang param.
     *
     * If a lang param is not provided, it will default to english.
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeShutdown(\Zend\Controller\Request\AbstractHelper $request)
    {
        $registry = \Zend\Registry::getInstance();

        $locale = $registry->get('Zend_Locale');
        $translate = $registry->get('Zend_Translate');

        $params = $this->getRequest()->getParams();
        $localeParam = isset($params['lang']) ? $param['lang'] : false;

        if (false === $localeParam) {
            $localeParam = $locale->getLanguage();
        }

        if (!$translate->isAvailable($localeParam)) {
            $localeParam = 'en';
            $this->getRequest()->setParam('lang', 'en');
        }

        $locale->setLocale($localeParam);
        $translate->setLocale($locale);

        \Zend\Form::setDefaultTranslator($translate);

        setcookie('lang', $locale->getLanguage(), null, '/');
    }
}