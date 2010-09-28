<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Auth
 * @subpackage Zend_Auth_Adapter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: OpenId.php 16200 2009-06-21 18:50:06Z thomas $
 */

/**
 * @namespace
 */
namespace Ak33m\Auth\Adapter;

use Ak33m\OpenId;

/**
 * A Zend_Auth Authentication Adapter allowing the use of OpenID protocol as an
 * authentication mechanism
 *
 * @category   Zend
 * @package    Zend_Auth
 * @subpackage Zend_Auth_Adapter
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class OpenId implements \Zend\Authentication\Adapter
{
    /**
     * The identity value being authenticated
     *
     * @var string
     */
    private $id = null;

    /**
     * Reference to an implementation of a storage object
     *
     * @var Zend_OpenId_Consumer_Storage
     */
    private $storage = null;

    /**
     * The URL to redirect response from server to
     *
     * @var string
     */
    private $returnTo = null;

    /**
     * The HTTP URL to identify consumer on server
     *
     * @var string
     */
    private $root = null;

    /**
     * Extension object or array of extensions objects
     *
     * @var string
     */
    private $extensions = null;

    /**
     * The response object to perform HTTP or HTML form redirection
     *
     * @var Zend_Controller_Response_Abstract
     */
    private $response = null;

    /**
     * Enables or disables interaction with user during authentication on
     * OpenID provider.
     *
     * @var bool
     */
    private $check_immediate = false;

    /**
     * HTTP client to make HTTP requests
     *
     * @var Zend_Http_Client $httpClient
     */
    private $httpClient = null;

    /**
     * Constructor
     *
     * @param string $id the identity value
     * @param Zend_OpenId_Consumer_Storage $storage an optional implementation
     *        of a storage object
     * @param string $returnTo HTTP URL to redirect response from server to
     * @param string $root HTTP URL to identify consumer on server
     * @param mixed $extensions extension object or array of extensions objects
     * @param Zend_Controller_Response_Abstract $response an optional response
     *        object to perform HTTP or HTML form redirection
     * @return void
     */
    public function __construct($id = null,
                                \Zend\OpenId\Consumer\Storage $storage = null,
                                $returnTo = null,
                                $root = null,
                                $extensions = null,
                                \Zend\Controller\Response\AbstractResponse $response = null) {
        $this->id         = $id;
        $this->storage    = $storage;
        $this->returnTo   = $returnTo;
        $this->root       = $root;
        $this->extensions = $extensions;
        $this->response   = $response;
    }

    /**
     * Sets the value to be used as the identity
     *
     * @param  string $id the identity value
     * @return Zend_Auth_Adapter_OpenId Provides a fluent interface
     */
    public function setIdentity($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Sets the storage implementation which will be use by OpenId
     *
     * @param  Zend_OpenId_Consumer_Storage $storage
     * @return Zend_Auth_Adapter_OpenId Provides a fluent interface
     */
    public function setStorage(\Zend\OpenId\Consumer\Storage $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * Sets the HTTP URL to redirect response from server to
     *
     * @param  string $returnTo
     * @return Zend_Auth_Adapter_OpenId Provides a fluent interface
     */
    public function setReturnTo($returnTo)
    {
        $this->returnTo = $returnTo;
        return $this;
    }

    /**
     * Sets HTTP URL to identify consumer on server
     *
     * @param  string $root
     * @return Zend_Auth_Adapter_OpenId Provides a fluent interface
     */
    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
    }

    /**
     * Sets OpenID extension(s)
     *
     * @param  mixed $extensions
     * @return Zend_Auth_Adapter_OpenId Provides a fluent interface
     */
    public function setExtensions($extensions)
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * Sets an optional response object to perform HTTP or HTML form redirection
     *
     * @param  string $root
     * @return Zend_Auth_Adapter_OpenId Provides a fluent interface
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Enables or disables interaction with user during authentication on
     * OpenID provider.
     *
     * @param  bool $check_immediate
     * @return Zend_Auth_Adapter_OpenId Provides a fluent interface
     */
    public function setCheckImmediate($check_immediate)
    {
        $this->check_immediate = $check_immediate;
        return $this;
    }

    /**
     * Sets HTTP client object to make HTTP requests
     *
     * @param Zend_Http_Client $client HTTP client object to be used
     */
    public function setHttpClient($client) {
        $this->httpClient = $client;
    }

    /**
     * Authenticates the given OpenId identity.
     * Defined by Zend_Auth_Adapter_Interface.
     *
     * @throws Zend_Auth_Adapter_Exception If answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        $id = $this->id;
        if (!empty($id)) {
            $consumer = new OpenId\Consumer($this->storage);
            $consumer->setHttpClient($this->httpClient);
            /* login() is never returns on success */
            if (!$this->check_immediate) {
                if (!$consumer->login($id,
                        $this->returnTo,
                        $this->root,
                        $this->extensions,
                        $this->response)) {
                    return new \Zend\Auth\Result(
                        \Zend\Auth\Result::FAILURE,
                        $id,
                        array("Authentication failed", $consumer->getError()));
                }
            } else {
                if (!$consumer->check($id,
                        $this->returnTo,
                        $this->root,
                        $this->extensions,
                        $this->response)) {
                    return new \Zend\Auth\Result(
                        \Zend\Auth\Result::FAILURE,
                        $id,
                        array("Authentication failed", $consumer->getError()));
                }
            }
        } else {
            $params = (isset($SERVER['REQUEST_METHOD']) &&
                       $SERVER['REQUEST_METHOD']=='POST') ? $POST: $GET;
            $consumer = new OpenId\Consumer($this->storage);
            $consumer->setHttpClient($this->httpClient);
            if ($consumer->verify(
                    $params,
                    $id,
                    $this->extensions)) {
                return new \Zend\Auth\Result(
                    \Zend\Auth\Result::SUCCESS,
                    $id,
                    array("Authentication successful"));
            } else {
                return new \Zend\Auth\Result(
                    \Zend\Auth\Result::FAILURE,
                    $id,
                    array("Authentication failed", $consumer->getError()));
            }
        }
    }

}