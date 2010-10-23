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
 * @subpackage  File
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Filter\File;

/**
 * Renames files to a reversable hash.
 *
 * This method is an improvement over a straight one-way hash; providing a
 * potential way to relink persistence records with files in during disaster
 * recovery or other use cases.
 *
 * @category    Drake
 * @package     Drake_Filter
 * @subpackage  File
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class RenameBase64 extends \Zend\Filter\File\Rename
{
    /**
     * @var array Seed data
     */
    protected $seedData = array();

    /**
     * @var string Seed separator
     */
    protected $seedSeparator = ',';

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

        $hash = $this->processSeed($targetInfo['filename']);
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
        $hash = $this->encodeBase64($seed);

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
        $this->seedData = $data;
        return $this;
    }

    /**
     * Get the filename seed data
     *
     * @return array
     */
    public function getSeedData()
    {
        return $this->seedData;
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
            throw new \Drake\Filter\InvalidArgumentException("Separator must be a string, '$type' given");
        }
        
        $this->seedSeparator = (string) $separator;
        return $this;
    }

    /**
     * Get the seed separator value
     *
     * @return string
     */
    public function getSeedSeparator()
    {
        return $this->seedSeparator;
    }
}