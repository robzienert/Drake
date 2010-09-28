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
        
        $this->setDefaultDisplayGroupClass('Drake_Form_DisplayGroup');

        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'decorator' => 'Description',
                'options' => array(
                    'tag' => 'p',
                    'class' => 'description',
                ),
            ),
            array(
                'decorator' => 'HtmlTag',
                'options' => array('tag' => 'dd'),
            ),
            array(
                'decorator' => 'Label',
                'options' => array('tag' => 'dt')
            ),
            'DlWrapper'
        ));

        $this->setDecorators(array(
            'FormElements',
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }
}