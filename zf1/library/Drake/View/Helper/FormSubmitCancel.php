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
 * Adds a simple cancel link next to the form submit button
 *
 * @category    Drake
 * @package     Drake_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_View_Helper_FormSubmitCancel extends Zend_View_Helper_FormSubmit
{
    public function formSubmitCancel($name, $value = null, $attribs = null)
    {
        if (!isset($attribs['cancel_url'])) {
            throw new Zend_View_Exception("Cancel URL was not provivded");
        }

        $url = $attribs['cancel_url'];
        unset($attribs['cancel_url']);

        $xhtml = parent::formSubmit($name, $value, $attribs)
               . ' <span class="cancel">or <a href="' . $url
               . '" title="Click to cancel and go back">cancel</a></span>';

        return $xhtml;
    }
}
