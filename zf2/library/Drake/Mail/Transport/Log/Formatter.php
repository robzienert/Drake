<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Mail
 * @subpackage  Transport
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake\Mail\Transport\Log;

/**
 * Sends mail to a log instead of actually sending through a mail transport.
 *
 * Originally developed by Aaron van Kaam (http://twitter.com/rabbyte).
 *
 * @category    Drake
 * @package     Drake_Mail
 * @subpackage  Transport
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Formatter extends \Zend\Log\Formatter\Xml
{
    /**
     * Formats data into a single line to be written by the writer
     *
     * @param array $event
     * @return string
     */
    public function format($event)
    {
        if ($this->_elementMap === null) {
            $dataToInsert = $event;
        } else {
            $dataToInsert = array();
            foreach ($this->_elementMap as $elementName => $fieldKey) {
                $dataToInsert[$elementName] = $event[$fieldKey];
            }
        }

        $dom = new \DOMDocument;
        $elt = $dom->appendChild(new \DOMElement($this->_rootElement));

        foreach ($dataToInsert as $key => $value) {
            if ($key == "message") {
                $value = $value['message'];
                $message = $elt->appendChild(new \DOMElement('email'));

                foreach (array('headers', 'body') as $messageKey) {
                    $value[$messageKey] = str_replace('=3D', '=', $value[$messageKey]);
                    $value[$messageKey] = str_replace(
                        \Zend\Mime::$qpReplaceValues,
                        \Zend\Mime::$qpKeys,
                        $value[$messageKey]
                    );

                    $elem = $message->appendChild(new \DomElement($messageKey));
                    $elem->appendChild( $dom->createCDataSection($value[$messageKey]));
                }

                $elt->appendChild($message);
            } else {
                $elt->appendChild(new \DOMElement($key, $value));
            }
        }

        $dom->formatOutput = true;
        $xml = $dom->saveXML();
        $xml = preg_replace('/<\?xml version="1.0"( encoding="[^\"]*")?\?>\n/u', '', $xml);

        return $xml . PHP_EOL;
    }
}