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
 * Renames uploaded files to a md5 hash.
 *
 * @category    Drake
 * @package     Drake_Filter
 * @subpackage  File
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class RenameHash extends \Zend\Filter\File\Rename
{
    /**
     * Hashes a filename by timestamp.
     *
     * @param string $file
     * @return array
     */
    protected function _getFileName($file)
    {
        $rename = parent::_getFileName($file);

        $targetInfo = pathinfo($rename['target']);
        $hash = md5(time());
        $rename['target'] = strtolower(sprintf('%s/%s.%s', 
                                               $targetInfo['dirname'],
                                               $hash,
                                               $targetInfo['extension']));
        
        return $rename;
    }
}