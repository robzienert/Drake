<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Filter
 * @subpackage  File
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Renames files to a reversable hash.
 *
 * This method is an improvement over a straight one-way hash; providing a
 * potential way to relink persistence records with files in case of disaster
 * recovery or other use cases.
 *
 * @category    Drake
 * @package     Drake_Filter
 * @subpackage  File
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Filter_File_RenameBase64 extends Zend_Filter_File_Rename
{
    /**
     * @var array Seed data
     */
    protected $_seedData = array();

    /**
     * @var string Seed separator
     */
    protected $_seedSeparator = ',';

    /**
     * Overloaded target file functionality which hashes the filename in a
     * potentially reversable string.
     *
     * @param string $file
     * @return array
     */
    protected function _getFileName($file)
    {
        $rename = parent::_getFileName($file);

        $targetInfo = pathinfo($rename['target']);

        $hash = $this->_processSeed($targetInfo['filename']);
        $rename['target'] = sprintf('%s/%s.%s',
                                    $targetInfo['dirname'],
                                    $hash,
                                    strtolower($targetInfo['extension']));
        return $rename;
    }

    /**
     * Process all seed data provided and hashes the resulting string
     *
     * @param string $filename
     * @return string
     */
    protected function _processSeed($filename)
    {
        $seedData = array_merge(array(
            'filename' => $filename
        ), $this->getSeedData());

        $seed = implode($this->getSeedSeparator(), $seedData);
        $hash = $this->_encodeBase64($seed);

        return $hash;
    }

    /**
     * Encode a string in filename-safe base64.
     *
     * Replaces characters +/= into -_, respectively.
     *
     * @param string $string
     * @return string
     */
    protected function _encodeBase64($string)
    {
        $base64 = base64_encode($string);
        $base64Url = strtr($base64, '+/=', '-_,');
        return $base64Url;
    }
       /**
     * Set the filename seed data
     *
     * @param array $data
     * @return Project_Filter_File_RenameBase64
     */
    public function setSeedData(array $data = array())
    {
        $this->_seedData = $data;
        return $this;
    }

    /**
     * Get the filename seed data
     *
     * @return array
     */
    public function getSeedData()
    {
        return $this->_seedData;
    }

    /**
     * Set the seed separator value
     *
     * @param string $separator
     * @return Project_Filter_File_RenameBase64
     * @throws Drake_Filter_File_InvalidArgumentException If separator is not a string
     */
    public function setSeedSeparator($separator = ',')
    {
        if (!is_string($separator)) {
            $type = gettype($separator);
            throw new Drake_Filter_File_InvalidArgumentException(
                "Separator must be a string, '$type' given");
        }
        
        $this->_seedSeparator = (string) $separator;
        return $this;
    }

    /**
     * Get the seed separator value
     *
     * @return string
     */
    public function getSeedSeparator()
    {
        return $this->_seedSeparator;
    }
}