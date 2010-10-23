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
 * @package     Drake_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Filter;

/**
 * A class that maps integers (or a combination of them) to an array of values.
 *
 * @todo Allow different bad word handling strategies; such as string replacement.
 *
 * @category    Drake
 * @package     Drake_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class BadWords extends \Zend\Filter\AbstractFilter
{
    /**
     * A nested comma-delimited array of blacklisted words
     *
     * @var array
     */
    protected $stopwords = array();

    /**
     * Path to the stopwords file
     *
     * @var string
     */
    protected static $stopwordsFile;

    /**
     * Filter a value
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        if (is_string($value)) {
            $this->getStopwords();
            
            $value = explode(' ', $value);
            foreach ($value as $key => $part) {
                if (in_array(strtolower($part), $this->stopwords)) {
                    unset($value[$key]);
                }
            }
            $value = implode(' ', $value);
        }

        return $value;
    }

    /**
     * Get the stopwords
     *
     * @return array
     */
    protected function _getStopwords()
    {
        $file = $this->getStopwordsFile();
        $this->stopwords = preg_split("/[\n\r]+/", file_get_contents($file));
        
        return $this->stopwords;
    }

    /**
     * Set the stopwords filepath
     *
     * @param string $file
     * @throws Drake_Filter_InvalidArgumentException
     * @return void
     */
    public static function setStopwordsFile($file)
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException("'$file' does not exist!");
        }

        self::$stopwordsFile = $file;
    }

    /**
     * Get the stopwords filepath
     *
     * @throws Drake_Filter_RuntimeException
     * @return string
     */
    public static function getStopwordsFile()
    {
        if (null === self::$stopwordsFile) {
            $file = dirname(__FILE__) . '/_files/badwords.txt';
            if (!file_exists($file)) {
                throw new RuntimeException('Stopwords file was not set and the default file could not be found');
            }
            self::$stopwordsFile = $file;
        }

        return self::$stopwordsFile;
    }
}