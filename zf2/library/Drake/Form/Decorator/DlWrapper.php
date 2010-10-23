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
 * @package     Drake_Form
 * @subpackage  Decorators
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Form\Decorator;

/**
 * @category    Drake
 * @package     Drake_Form
 * @subpackage  Decorators
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class DlWrapper extends \Zend\Form\Decorator\AbstractDecorator
{
    /**
     * Default placement; surround content
     *
     * @var string
     */
    protected $placement = null;

    /**
     * A DL around an element
     *
     * @param string $content
     * @return string
     */
    public function render($content)
    {
        $elementName = $this->getElement()->getName();
        return '<dl id="' . $elementName . '-wrapper">' . $content . '</dl>';
    }
}