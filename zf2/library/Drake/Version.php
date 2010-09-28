<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Version
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake;

/**
 * Drake version
 *
 * @category    Drake
 * @package     Drake_Version
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
final class Version
{
    const VERSION = '2.0.0dev1';

    /**
     * Compare the specified Drake version string $version  with the current
     * Drake\Version::VERSION of Drake.
     *
     * @param  string  $version  A version string (e.g. "0.7.1").
     * @return boolean           -1 if the $version is older,
     *                           0 if they are the same,
     *                           and +1 if $version is newer.
     */
    public static function compareVersion($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
        return version_compare($version, strtolower(self::VERSION));
    }
}