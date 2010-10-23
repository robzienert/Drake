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
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Form;

/**
 * @category    Drake
 * @package     Drake_Form
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class Form extends \Zend\Form\Form
{
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