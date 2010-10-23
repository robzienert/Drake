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
 * @package     Drake_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\View\Helper;

/**
 * @category    Drake
 * @package     Drake_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class Textile extends \Zend\View\Helper\AbstractHelper
{
    /**
     * @var Textile
     */
    protected static $textile;

    /**
     * Will render textile input into HTML
     *
     * @param string $content
     * @param bool $restricted
     * @return string
     */
    public function textile($content, $restricted = false)
    {
        $content = $this->view->escape($content);
        if ($restricted) {
            return $this->getTextile()->TextileRestricted($content);
        }
        return $this->getTextile()->TextileThis($content);
    }

    /**
     * Lazy-load the textile class
     *
     * @return Textile
     */
    public function getTextile()
    {
        if (null === self::$textile) {
            require_once 'Textile.php';
            self::$textile = new \Textile();
        }
        return self::$textile;
    }
}