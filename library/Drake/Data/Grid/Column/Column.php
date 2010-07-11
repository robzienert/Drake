<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid_Column
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Base column class; can be used as a generic text column
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid_Column
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Data_Grid_Column_Column
{
    /**
     * @var string
     */
    protected $_cssClass;
    
    /**
     * @var Drake_Data_Grid_Column_Filter_FilterAbstract
     */
    protected $_filter;

    /**
     * @var Drake_Data_Grid_Grid
     */
    protected $_grid;

    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var int
     */
    protected $_order = 0;

    /**
     * @var Drake_Data_Grid_Column_Renderer_RendererAbstract
     */
    protected $_renderer;

    /**
     * @var string
     */
    protected $_rowField;

    /**
     * @var string
     */
    protected $_type = 'generic';

    /**
     * @var int
     */
    protected $_width = 50;

    /**
     * Constructor
     *
     * @param array $options
     * @return void
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Set options en masse
     *
     * @param array $options
     * @return Drake_Data_Grid_Column_Column
     */
    public function setOptions($options)
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }
        return $this;
    }

    /**
     * Set an individual option. If the mutator method does not exist, this
     * method will throw an exception.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws LogicException If a mutator does not exist for $name
     */
    public function setOption($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }
        throw new LogicException("`$name` mutator method does not exist!");
    }

    /**
     * Set the column id
     *
     * @param string $id
     * @return Drake_Data_Grid_Column_Column
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * Get the column id
     *
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set the column name; this will be used in the grid header
     *
     * @param string $name
     * @return Drake_Data_Grid_Column_Column
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Get the column name; this will be used in the grid header
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set the row field. Columns values will be mapped to this key inside each
     * data row.
     *
     * @param string $rowField
     * @return Drake_Data_Grid_Column_Column
     */
    public function setRowField($rowField)
    {
        $this->_rowField = $rowField;
        return $this;
    }

    /**
     * Get the row field.
     *
     * @return string
     */
    public function getRowField()
    {
        return $this->_rowField;
    }

    /**
     * Set the column type
     *
     * @param string $type
     * @return Drake_Data_Grid_Column_Column
     */
    public function setType($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        $this->_type = $type;
        return $this;
    }

    /**
     * Get the column type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Set the column order
     *
     * @param int $order
     * @return Drake_Data_Grid_Column_Column
     */
    public function setOrder($order)
    {
        $this->_order = $order;
        return $this;
    }

    /**
     * Get the column order
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Set the CSS class
     *
     * @param string $cssClass
     * @return Drake_Data_Grid_Column_Column
     */
    public function setCssClass($cssClass)
    {
        $this->_cssClass = $cssClass;
        return $this;
    }

    /**
     * Get the CSS class
     *
     * @return string
     */
    public function getCssClass()
    {
        return $this->_cssClass;
    }

    /**
     * Set the column width
     *
     * @param int $width
     * @return Drake_Data_Grid_Column_Column
     */
    public function setWidth($width)
    {
        $this->_width = (int) $width;
        return $this;
    }

    /**
     * Get the column width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * Set the grid
     *
     * @param Drake_Data_Grid_Grid $grid
     * @return Drake_Data_Grid_Column_Column
     */
    public function setGrid(Drake_Data_Grid_Grid $grid)
    {
        $this->_grid = $grid;
        return $this;
    }

    /**
     * Get the grid
     *
     * @return Drake_Data_Grid_Grid
     */
    public function getGrid()
    {
        return $this->_grid;
    }

    /**
     * Set the column renderer. If the renderer does not yet exist in the grid,
     * it will be added to it's internal cache.
     *
     * @param Drake_Data_Grid_Column_Renderer_RendererAbstract $renderer
     * @return Drake_Data_Grid_Column_Column
     */
    public function setRenderer(Drake_Data_Grid_Column_Renderer_RendererAbstract $renderer)
    {
        $renderers = $this->getGrid()->getColumnRenderers();
        if (!isset($renderers[$renderer->getType()])) {
            $this->getGrid()->addColumnRenderer($renderer);
        }
        
        $this->_renderer = $renderer;
        return $this;
    }

    /**
     * Get the filter renderer. If none has been set, it will return the default
     * renderer for this column type.
     *
     * @return Drake_Data_Grid_Column_Renderer_RendererAbstract
     */
    public function getRenderer()
    {
        if (null === $this->_renderer) {
            $classname = $this->_getRendererByType();
            $renderer = new $classname;
            $this->setRenderer($renderer);
        }
        return $this->_renderer;
    }

    /**
     * Get the renderer class by the type of this column. An unknown column type
     * will default to the text renderer.
     *
     * @return string
     */
    protected function _getRendererByType()
    {
        $type = strtolower($this->getType());
        $renderers = $this->getGrid()->getColumnRenderers();
        if (isset($renderers[$type])) {
            return $renderers[$type];
        }

        // @todo Add support for overloading these renderers
        switch ($type) {
            case 'date':
                $rendererClass = 'Drake_Data_Grid_Column_Renderer_Date';
                break;

            case 'datetime':
                $rendererClass = 'Drake_Data_Grid_Column_Renderer_Datetime';
                break;

            case 'text':
            default:
                $rendererClass = 'Drake_Data_Grid_Column_Renderer_Text';
                break;
        }

        return $rendererClass;
    }

    /**
     * Set the filter. If the filter does not yet exist in the grid, it will be
     * added to it's internal cache.
     *
     * @param Drake_Data_Grid_Column_Filter_FilterAbstract $filter
     * @return Drake_Data_Grid_Column_Column
     */
    public function setFilter(Drake_Data_Grid_Column_Filter_FilterAbstract $filter)
    {
        $filters = $this->getGrid()->getColumnFilters();
        if (!isset($filters[$filter->getType()])) {
            $this->getGrid()->addColumnFilter($filter);
        }
        
        $this->_filter = $filter;
        return $this;
    }

    /**
     * Get the filter. If none has been set, it will return the default filter
     * for this column type.
     *
     * @return Drake_Data_Grid_Column_Filter_FilterAbstract
     */
    public function getFilter()
    {
        if (null === $this->_filter) {
            $classname = $this->_getFilterByType();
            $filter = new $classname;
            $this->setFilter($filter);
        }
        return $this->_filter;
    }

    /**
     * Get the filter class by the type of this column. An unknown column type
     * will default to the text filter.
     *
     * @return string
     */
    protected function _getFilterByType()
    {
        $type = strtolower($this->getType());
        $filters = $this->getGrid()->getColumnFilters();
        if (isset($filters[$type])) {
            return $filters[$type];
        }

        // @todo Add support for overloading these filters
        switch ($type) {
            case 'date':
                $filterClass = 'Drake_Data_Grid_Column_Filter_Date';
                break;

            case 'datetime':
                $filterClass = 'Drake_Data_Grid_Column_Filter_Datetime';
                break;

            case 'text':
            default:
                $filterClass = 'Drake_Data_Grid_Column_Filter_Text';
                break;
        }

        return $filterClass;
    }

    /**
     * Render the column
     *
     * @param mixed $value
     * @return string
     */
    public function render($value)
    {
        $value = $this->getFilter()->filter($value);
        $result = $this->getRenderer()->setValue($value)->render();
        return $result;
    }
}