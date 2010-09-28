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
 * @namespace
 */
namespace Drake\View\Helper;

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
class GoogleAnalytics extends \Zend\View\Helper\AbstractHelper
{
    const STACK_PAGEVIEW = 'pageview';
    const STACK_TRANS = 'trans';

    /**
     * Default GA tracker id
     *
     * @var string
     */
    protected static $defaultTrackerId;

    /**
     * Set tracker id
     *
     * @var string
     */
    protected $trackerId;

    /**
     * The pageview options stack
     *
     * @var array
     */
    protected $pageviewStack = array();

    /**
     * The transaction options stack
     *
     * @var array
     */
    protected $transStack = array();

    /**
     * Valid tracker options
     *
     * @var array
     */
    protected $validOptions = array(
        // Client detection
        '_setClientInfo',
        '_setAllowHash',
        '_setDetectFlash',
        '_setDetectTitle',

        // Campaigns
        '_setCampaignTrack',
        '_setCampaignCookieTimeout',
        '_setCampNameKey',
        '_setCampMediumKey',
        '_setCampSourceKey',
        '_setCampTermKey',
        '_setCampContentKey',
        '_setCampNOKey',

        // Cross Domain
        '_setDomainName',
        '_setAllowLinker',

        // ECommerce
        '_addTrans',
        '_addItem',
        '_trackTrans',

        // Events
        '_trackEvent',

        // Sessions
        '_setSessionCookieTimeout',

        // Sources
        '_addIgnoredOrganic',
        '_addIgnoredRef',
        '_addOrganic',

        // Local Server (Urchin compatibility)
        '_setLocalRemoteServerMode',
    );

    /**
     * Direct method
     *
     * Fluent interface to other methods
     *
     * @param string $trackerId
     * @return Drake_View_Helper_GoogleAnalytics
     */
    public function googleAnalytics($trackerId = null, array $options = array())
    {
        if (null !== $trackerId) {
            $this->setTrackerId($trackerId);
        }

        if (!empty($options)) {
            $this->setOptions($options);
        }

        return $this;
    }

    /**
     * Render the GA code snippet
     *
     * @return string
     */
    public function __toString()
    {
        $xhtml = array();
        $xhtml[] = '<script type="text/javascript">';
        $xhtml[] = 'var _gaq = _gaq || [];';
        $xhtml[] = "_gaq.push(['_setAccount', '" . $this->getTrackerId() . "']);";

        foreach ($this->pageviewStack as $option) {
            $xhtml[] = $option;
        }

        $xhtml[] = "_gaq.push(['_trackPageview']);";
        $xhtml[] = '';

        if (!empty($this->transStack)) {
            foreach ($this->transStack as $option) {
                $xhtml[] = $option;
            }
            $xhtml[] = "_gaq.push(['_trackTrans']);";
        }

        $xhtml[] = '';
        $xhtml[] = '(function() {';
        $xhtml[] = "var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;";
        $xhtml[] = "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
        $xhtml[] = "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);";
        $xhtml[] = '})();';
        $xhtml[] = '</script>';

        $xhtml = implode(PHP_EOL, $xhtml);

        return $xhtml;
    }

    /**
     * Push an option into the stack
     *
     * @param string $name
     * @param string|array|null $args
     * @throws Drake_View_Helper_RuntimeException
     * @return Drake_View_Helper_GoogleAnalytics
     */
    public function setOption($name, $args = null, $stack = self::STACK_PAGEVIEW)
    {
        if (!in_array($name, $this->validOptions)) {
            throw new RuntimeException("Invalid GA option, '$name' provided!");
        }

        $push = "_gaq.push(['$name'";

        if (null !== $args) {
            if (!is_array($args)) {
                $args = array($args);
            }

            foreach ($args as &$arg) {
                $arg = $this->quoteArg($arg);
            }

            $args = ', ' . implode(', ', $args);
        }

        $push .= $args . ']);';

        $stackVar = '_' . $stack . 'Stack';

        array_push($this->$stackVar, $push);
    }

    /**
     * Handles quoting different datatypes
     *
     * @param mixed $arg
     * @return string
     */
    protected function _quoteArg($arg)
    {
        if (null === $arg || '' === $arg) {
            $arg = '""';
        } elseif (is_bool($arg)) {
            $arg = ($arg) ? 'true' : 'false';
        } else {
            $arg = is_numeric($arg) ? $arg : addslashes($arg);
            $arg = "'$arg'";
        }
        return $arg;
    }

    /**
     * Set multiple options
     *
     * @param array $options
     * @param string $stack
     * @return Drake_View_Helper_GoogleAnalytics
     */
    public function setOptions(array $options, $stack = self::STACK_PAGEVIEW)
    {
        foreach ($options as $name => $args) {
            $this->setOption($name, $args, $stack);
        }
        return $this;
    }

    /**
     * Set the tracker id
     *
     * @param string $trackerId
     */
    public function setTrackerId($trackerId)
    {
        $this->trackerId = $trackerId;
    }

    /**
     * Get the tracker id
     *
     * @return string
     * @throws Drake_View_Helper_RuntimeException when A tracker ID has not been set
     */
    public function getTrackerId()
    {
        if (null === ($tracker = $this->trackerId)) {
            $tracker = self::getDefaultTrackerId();
            if (null === $tracker) {
                throw new RuntimeException('GA Tracker ID has not been set!');
            }
        }
        return $tracker;
    }

    /**
     * Set the default tracker id
     *
     * @param string $trackerId
     */
    public static function setDefaultTrackerId($trackerId)
    {
        self::$defaultTrackerId = $trackerId;
    }

    /**
     * Get the default tracker id
     *
     * @return string
     */
    public static function getDefaultTrackerId()
    {
        return self::$defaultTrackerId;
    }
}