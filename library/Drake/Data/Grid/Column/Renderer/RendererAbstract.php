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
 * Abstract column value renderer
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid_Column_Renderer
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class Drake_Data_Grid_Column_Renderer_RendererAbstract
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $value;

    /**
     * Set the renderer type: this is used for identification throughout the
     * application.
     *
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        $this->type = $type;
    }

    /**
     * Get the renderer type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value that will be rendered
     *
     * @param string $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value that will be rendered
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * The method used to return the rendered column value.
     *
     * @return string
     */
    abstract public function render()
    {
    }
}