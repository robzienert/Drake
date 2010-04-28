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
 * Default form
 *
 * @category    Drake
 * @package     Drake_Form
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Form extends Zend_Form
{
    /**
     * Init
     */
    public function init()
    {
        $this->addPrefixPath('Drake_Form', 'Drake/Form');
        $this->addElementPrefixPath('Drake', 'Drake');
        $this->addElementPrefixPath('Drake_Validate', 
                                    'Drake/Validate',
                                    Zend_Form_Element::VALIDATE);
        $this->setDefaultDisplayGroupClass('Drake_Form_DisplayGroup');

        $this->setDecorators(array(
            'FormElements',
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }

    /**
     * Add a new element to the form
     *
     * Appends the DlWrapper decorator onto the form element as well
     *
     * @param string|Zend_Form_Element $element
     * @param string $name
     * @param array $options
     * @return Zend_Form
     */
    public function addElement($element, $name = null, $options = null)
    {
        parent::addElement($element, $name, $options);

        if (!$element instanceof Zend_Form_Element) {
            $element = $this->getElement($name);
        }
        if (!$element->loadDefaultDecoratorsIsDisabled()) {
            $element->addDecorator('DlWrapper');
        }

        return $this;
    }
}