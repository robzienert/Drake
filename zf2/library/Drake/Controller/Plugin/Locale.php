<?php
/**
 * Drake Framework
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the BSD License that is bundled with this
 * package in the file LICENSE. It is also available through the world-wide-web
 * at this URL: http://github.com/robzienert/Drake/blob/develop/LICENSE
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Controller\Plugin;

use \Zend\Registry,
    \Zend\Form\Form;

/**
 * @todo This component needs to be refactored for ZF2.
 *
 * @uses        \Zend\Registry
 * @uses        \Zend\Locale\Locale
 * @uses        \Zend\Translator\Translator
 * @uses        \Zend\Form\Form
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
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
        $registry = Registry::getInstance();

        /** @var $locale \Zend\Locale\Locale */
        /** @var $translator \Zend\Translator\Translator */
        $locale = $registry->get('Zend\\Locale\\Locale');
        $translator = $registry->get('Zend\\Translator\\Translator');

        $params = $this->getRequest()->getParams();
        $localeParam = isset($params['lang']) ? $param['lang'] : false;

        if (false === $localeParam) {
            $localeParam = $locale->getLanguage();
        }

        if (!$translator->isAvailable($localeParam)) {
            $localeParam = 'en';
            $this->getRequest()->setParam('lang', 'en');
        }

        $locale->setLocale($localeParam);
        $translator->setLocale($locale);

        Form::setDefaultTranslator($translator);

        setcookie('lang', $locale->getLanguage(), null, '/');
    }
}