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
 * Application language and locale detection by request
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Controller_Plugin_Locale extends Zend_Controller_Plugin_Abstract
{
    /**
     * Sets the application locale and translation based on the lang param.
     *
     * If a lang param is not provided, it will default to english.
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $registry = Zend_Registry::getInstance();

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

        Zend_Form::setDefaultTranslator($translate);

        setcookie('lang', $locale->getLanguage(), null, '/');
    }
}