<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid_Column_Renderer
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Basic text column renderer
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid_Column_Renderer
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Data_Grid_Column_Renderer_Text
    extends Drake_Data_Grid_Column_Renderer_RendererAbstract
{
    /**
     * @var string
     */
    protected $type = 'text';

    /**
     * Renders the column value
     *
     * @return string
     */
    public function render()
    {
        return $this->getValue();
    }
}