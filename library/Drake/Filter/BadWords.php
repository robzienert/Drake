<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * A class that maps integers (or a combination of them) to an array of values.
 *
 * @todo Allow different bad word handling strategies; such as string replacement.
 *
 * @category    Drake
 * @package     Drake_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Filter_BadWords implements Zend_Filter_Interface
{
    /**
     * A nested comma-delimited array of blacklisted words
     *
     * @var array
     */
    protected $_stopwords = array();

    /**
     * Path to the stopwords file
     *
     * @var string
     */
    protected static $_stopwordsFile;

    /**
     * Filter a value
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        if (is_string($value)) {
            $this->_getStopwords();
            
            $value = explode(' ', $value);
            foreach ($value as $key => $part) {
                if (in_array(strtolower($part), $this->_stopwords)) {
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
        $this->_stopwords = preg_split("/[\n\r]+/", file_get_contents($file));
        
        return $this->_stopwords;
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
            throw new Drake_Filter_InvalidArgumentException("'$file' does not exist!");
        }

        self::$_stopwordsFile = $file;
    }

    /**
     * Get the stopwords filepath
     *
     * @throws Drake_Filter_RuntimeException
     * @return string
     */
    public static function getStopwordsFile()
    {
        if (null === self::$_stopwordsFile) {
            $file = dirname(__FILE__) . '/_files/badwords.txt';
            if (!file_exists($file)) {
                throw new Drake_Filter_RuntimeException(
                    'Stopwords file was not set and the default file could not be found');
            }
            self::$_stopwordsFile = $file;
        }

        return self::$_stopwordsFile;
    }
}