<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Form
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * DlWrapper for elements
 *
 * @category    Drake
 * @package     Drake_Form
 * @subpackage  Decorators
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Form_Decorator_DlWrapper extends Zend_Form_Decorator_Abstract
{
    /**
     * Default placement; surround content
     *
     * @var string
     */
    protected $_placement = null;

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