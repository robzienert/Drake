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
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class RenameHash extends \Zend\Filter\File\Rename
{
    /**
     * Hashes a filename by timestamp.
     *
     * @param <type> $file
     * @return <type>
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