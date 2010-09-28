<?php
class Drake_Controller_Plugin_PageHistory extends Zend_Controller_Plugin_Abstract
{
    protected $_storage;

    protected $_pages = array();

    protected static $_namespace = __CLASS__;

    protected $_ignoreCurrentPage = false;

    public function __construct()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->setParam('plugins', array((array) $front->getParams('plugins'), array(
            'pageHistory' => $this
        )));
    }

    public function getNamespace()
    {
        return self::$_namespace;
    }

    public function setNamespace($namespace)
    {
        self::$_namespace = $namespace;
    }

    public function resetNamespace()
    {
        self::$_namespace = __CLASS__;
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $this->setRequest($request);

        if (false !== strpos($this->getRequest()->getControllerName(), 'admin')) {
            $this->setNamespace(__CLASS__ . '_Admin');
        }

        $this->_storage = new Zend_Session_Namespace(self::getNamespace());

        if (!isset($this->_storage->pages)) {
            $this->_storage->pages = array();
        }
    }

    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if ($this->getIgnorePage()) {
            return;
        }

        $requestUri = $_SERVER['REQUEST_URI'];
        $pathinfo = pathinfo($requestUri);
        if (isset($pathinfo['extension'])) {
            return;
        }

        $pages = $this->_storage->pages;

        if (!isset($pages[0])) {
            $pages[0] = $requestUri;
        }

        if ($requestUri != $this->getReferrer()) {
            $pages = array_reverse($pages);
            $pages[] = $requestUri;

            if (count($pages) > 10) {
                array_shift($pages);
            }

            $this->_storage->pages = array_reverse($pages);
        }
    }

    public function getReferrer()
    {
        return $this->getPage(0);
    }

    public function getPage($history = 0)
    {
        $storage = $this->_storage;
        return isset($storage->pages[(int) $history]) ? $storage->pages[(int) $history] : false;
    }

    public function getAllPages()
    {
        return $this->_storage->pages;
    }

    public function setReturnPage($page)
    {
        $this->_storage->returnPage = $page;
        return $this;
    }

    public function getReturnPage()
    {
        $storage = $this->_storage;
        $page = (null === $storage->returnPage) ? $this->getReferrer() : $storage->returnPage;

        if (false === strpos($page, $_SERVER['SERVER_NAME'])) {
            $page = 'http://' . $_SERVER['SERVER_NAME'] . $page;
        }

        return $page;
    }

    public function isReturnPageSet()
    {
        return (null !== $this->_storage->returnPage);
    }

    public function resetReturnPage()
    {
        $this->_storage->returnPage = null;
        return $this;
    }

    public function setIgnorePage($flag)
    {
        $this->_ignoreCurrentPage = (boolean) $flag;
        return $this;
    }

    public function getIgnorePage()
    {
        return $this->_ignoreCurrentPage;
    }
}