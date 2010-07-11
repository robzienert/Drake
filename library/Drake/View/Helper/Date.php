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
 * The Date helper assists in rendering dates to the view.
 *
 * @category    Drake
 * @package     Drake_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_View_Helper_Date extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_Date
     */
    private $_date;

    /**
     * Direct access; fluent interface
     *
     * @param null|integer|string|Zend_Date $date
     * @return Drake_View_Helper_Date
     */
    public function date($date = null)
    {
        if (null === $date) {
            $date = new Zend_Date();
        }
        $this->setDate($date);
        return $this;
    }

    /**
     * Returns the Zend_Date object
     *
     * @return Zend_Date
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * Sets the date that the view helper will be operating with
     *
     * @param integer|string|Zend_Date $date
     * @return Zend_Date
     */
    public function setDate($date)
    {
        if ($date instanceof Zend_Date) {
            $this->_date = $date;
        } else {
            if ($date instanceof DateTime) {
                $date = $date->getTimestamp();
            }

            $part = is_numeric($date) ? Zend_Date::TIMESTAMP : Zend_Date::ISO_8601;
            $this->_date = new Zend_Date($date, $part);
        }
        return $this;
    }

    /**
     * Returns a relative time difference between the current time and provided date.
     * $format can be provided as either an integer or string. If an integer is provided,
     * it will automatically format the output based on accuracy (higher numbers are more
     * accurate) whereas a string can be provided for a specific output.
     *
     * Available variables:
     *      %years%     Integer, %yearsString%      Value label
     *      %months%    Integer, %monthsString%     Value label
     *      %weeks%     Integer, %weeksString%      Value label
     *      %days%      Integer, %daysString%       Value label
     *      %hours%     Integer, %hoursString%      Value label
     *      %minutes%   Integer, %minutesString%    Value label
     *      %seconds%   Integer, %secondsString%    Value label
     *
     * @todo Add Zend_Translate support
     *
     * @param integer|string $format
     * @return string
     */
    public function timeSince($format = 1)
    {
        $second = 1;
        $minute = $second * 60;
        $hour   = $minute * 60;
        $day    = $hour * 24;
        $week   = $day * 7;
        $month  = $week * 4;
        $year   = $month * 12;

        $thenTs = $this->_date->toString(Zend_Date::TIMESTAMP);
        $nowTs = time();

        $difference = $nowTs - $thenTs;

        $years = floor($difference / $year);
        $remainingMonths = $difference - ($years * $year);

        $months = floor($remainingMonths / $month);
        $remainingWeeks = $remainingMonths - ($months * $month);

        $weeks = floor($remainingWeeks / $week);
        $remainingDays = $remainingWeeks - ($weeks * $week);

        $days = floor($remainingDays / $day);
        $remainingHours = $remainingDays - ($days * $day);

        $hours = floor($remainingHours / $hour);
        $remainingMinutes = $remainingHours - ($hours * $hour);

        $minutes = floor($remainingMinutes / $minute);
        $seconds = $remainingMinutes - ($minutes * $minute);

        $variables = array('years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds');

        // If the format is provided as an integer, it will interpret it as an
        // auto-format accuracy. It will then work its way down appending times
        // based on accuracy from the highest time over 0.
        if (is_integer($format)) {
            $accuracy = $format;
            $format   = array();

            if ($years > 0) {
                $formatStart = 'years';
            } elseif ($months > 0) {
                $formatStart = 'months';
            } elseif ($weeks > 0) {
                $formatStart = 'weeks';
            } elseif ($days > 0) {
                $formatStart = 'days';
            } elseif ($hours > 0) {
                $formatStart = 'hours';
            } else {
                $formatStart = 'minutes';
            }

            $accuracyCount = 0;
            for ($i = 0; $i < 7; $i++) {
                $variable = $variables[$i];

                if ($accuracyCount == 0 && $variable !== $formatStart) {
                    continue;
                }

                // Hack for accuracy level 2 and the second accuracy level is 0
                if ($accuracy > 1 && $accuracy - $accuracyCount == 1 && $$variable == 0) {
                    break;
                }

                $format[] = "%{$variable}% %{$variable}String%";

                ++$accuracyCount;
                if ($accuracyCount == $accuracy) {
                    break;
                }
            }

            $format = implode(', ', $format);
        }

        // Parses the format provided by the user or automatically generated
        foreach ($variables as $variable) {
            $format = str_replace("%{$variable}%", $$variable, $format);

            $string = $variable;
            if ($$variable == 1) {
                $string = substr($variable, 0, -1);
            }

            $format = str_replace("%{$variable}String%", $string, $format);
        }

        return $format;
    }

    /**
     * Pass through to Zend_Date method calls.
     *
     * @throws Drake_Exception Exception thrown by Zend_Date
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        try {
            return call_user_func_array(array($this->_date, $method), $args);
        } catch (Exception $e) {
            $message = "Invalid method '{$method}' called on Zend_Date object. Exception: ";
            $message .= $e->getMessage();
            throw new Drake_Exception($message);
        }
    }

}