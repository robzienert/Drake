<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * A push Google Analytics view helper; based off of the view helper originally
 * developed by Harold Thétiot (http://hthetiot.blogspot.com/).
 *
 * @category    Drake
 * @package     Drake_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_View_Helper_Textile extends Zend_View_Helper_Abstract
{
    /**
     * @var Textile
     */
    protected static $_textile;

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
        if (null === self::$_textile) {
            require_once 'Textile.php';
            self::$_textile = new Textile();
        }
        return self::$_textile;
    }
}