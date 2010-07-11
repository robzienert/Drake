<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * A data grid viewer
 *
 * Inspired by Magento's Adminhtml Grid Widget.
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Data_Grid_Grid
{
    /**
     * @var array
     */
    protected $_columns = array();

    /**
     * @var array
     */
    protected $_columnFilters = array();

    /**
     * @var array
     */
    protected $_columnHeaders;

    /**
     * @var array
     */
    protected $_columnRenderers = array();

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * @var string
     */
    protected $_emptyText = 'No records found.';

    /**
     * @var string
     */
    protected $_id;

    /**
     * @var array|null
     */
    protected $_preparedRows;

    /**
     * @var Drake_Data_Grid_Renderer
     */
    protected $_renderer;

    /**
     * @var Zend_View_Interface
     */
    protected $_view;

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
     * @return void
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
     * Set the ID
     *
     * @param string $id
     * @return Drake_Data_Grid_Grid
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * Get the id
     *
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Set the text that will be displayed when no entries can be displayed by
     * the grid.
     *
     * @param string $emptyText
     * @return Drake_Data_Grid_Grid
     */
    public function setEmptyText($emptyText)
    {
        $this->_emptyText = $emptyText;
        return $this;
    }

    /**
     * Get the empty text
     *
     * @return string
     */
    public function getEmptyText()
    {
        return $this->_emptyText;
    }

    /**
     * Set the view object
     *
     * @param Zend_View_Interface $view
     * @return Drake_Data_Grid_Grid
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * Get the view object
     *
     * @return Zend_View_Interface
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * Set the grid data
     *
     * @param array $data
     * @return Drake_Data_Grid_Grid
     */
    public function setData($data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * Get the grid data
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Set the columns en masse
     *
     * @param array $columns
     * @return Drake_Data_Grid_Grid
     */
    public function setColumns(array $columns)
    {
        $this->columns = array();
        foreach ($columns as $id => $column) {
            $this->addColumn($id, $column);
        }
        return $this;
    }

    /**
     * Add a new column to the grid
     *
     * @todo Refactor to reflect more of a Zend_Form->addElement approach
     *
     * @param string $columnId
     * @param Drake_Data_Grid_Column_Column $column
     */
    public function addColumn($columnId, Drake_Data_Grid_Column_Column $column)
    {
        $columnId = Drake_Util_StringInflector::underscore($columnId);
        if (isset($this->_columns[$columnId])) {
            throw new LogicException(
                "Cannot add new column `$columnId`: A column already exists with that id");
        }
        
        $column->setId($columnId);
        $column->setGrid($this);

        $this->_columns[$columnId] = $column;
        
        return $this;
    }

    /**
     * Retrieves a column by id. If the column does not exist, this method will
     * return false.
     *
     * @param string $columnId
     * @return Drake_Data_Grid_Column_Column|false
     */
    public function getColumn($columnId)
    {
        $columnId = Drake_Util_StringInflector::underscore($columnId);
        if (isset($this->_columns[$columnId])) {
            return $this->_columns[$columnId];
        }
        return false;
    }

    /**
     * Get all columns
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * Remove a single column by id.
     *
     * @param string $columnId
     * @return Drake_Data_Grid_Grid
     */
    public function removeColumn($columnId)
    {
        $columnId = Drake_Util_StringInflector::underscore($columnId);
        if (isset($this->_columns[$columnId])) {
            unset($this->_columns[$columnId]);
        }
        return $this;
    }

    /**
     * Retrieve the number of columns in the grid
     *
     * @return int
     */
    public function getColumnCount()
    {
        return count($this->_columns);
    }

    /**
     * Add a column renderer to the internal cache
     *
     * @param Drake_Data_Grid_Column_Renderer_RendererAbstract $renderer
     * @return Drake_Data_Grid_Grid
     */
    public function addColumnRenderer(Drake_Data_Grid_Column_Renderer_RendererAbstract $renderer)
    {
        $type = $renderer->getType();
        if (isset($this->_columnRenderers[$type])) {
            throw new LogicException(
                "Renderer `$type` has already been registered with the grid");
        }
        $this->_columnRenderers[$type] = $renderer;
        return $this;
    }

    /**
     * Get a column renderer from the internal cache. If it does not exist, this
     * will return false.
     *
     * @param string $type
     * @return Drake_Data_Grid_Column_Renderer_RendererAbstract|false
     */
    public function getColumnRenderer($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        if (isset($this->_columnRenderers[$type])) {
            return $this->_columnRenderers[$type];
        }
        return false;
    }

    /**
     * Get all registered column renderers.
     *
     * @return array
     */
    public function getColumnRenderers()
    {
        return $this->_columnRenderers;
    }

    /**
     * Remove a registered column renderer.
     *
     * @param string $type
     * @return Drake_Data_Grid_Grid
     */
    public function removeColumnRenderer($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        if (isset($this->_columnRenderers[$type])) {
            unset($this->_columnRenderers[$type]);
        }
        return $this;
    }

    /**
     * Add a column filter to the internal cache
     *
     * @param Drake_Data_Grid_Column_Filter_FilterAbstract $renderer
     * @return void
     */
    public function addColumnFilter(Drake_Data_Grid_Column_Filter_FilterAbstract $filter)
    {
        $type = $filter->getType();
        if (isset($this->_columnFilters[$type])) {
            throw new LogicException(
                "Filter `$type` has already been registered with the grid");
        }
        $this->_columnFilters[$type] = $filter;
    }

    /**
     * Get a column filter from the internal cache. If it does not exist, this
     * will return false.
     *
     * @param string $type
     * @return Drake_Data_Grid_Column_Filter_FilterAbstract|false
     */
    public function getColumnFilter($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        if (isset($this->_columnFilters[$type])) {
            return $this->_columnFilters[$type];
        }
        return false;
    }

    /**
     * Get all registered column filters.
     *
     * @return array
     */
    public function getColumnFilters()
    {
        return $this->_columnFilters;
    }

    /**
     * Remove a registered column filter.
     *
     * @param string $type
     * @return Drake_Data_Grid_Grid
     */
    public function removeColumnFilter($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        if (isset($this->_columnFilters[$type])) {
            unset($this->_columnFilters[$type]);
        }
    }

    /**
     * Set the grid renderer
     *
     * @todo Set up a Renderable interface
     *
     * @param Drake_Data_Grid_Renderer $renderer
     * @return Drake_Data_Grid_Grid
     */
    public function setGridRenderer(Drake_Data_Grid_Renderer $renderer)
    {
        $this->_renderer = $renderer;
        return $this;
    }

    /**
     * Get the grid renderer
     *
     * @return Drake_Data_Grid_Renderer
     */
    public function getGridRenderer()
    {
        if (null === $this->_renderer) {
            $this->_renderer = new Drake_Data_Grid_Renderer();
        }
        return $this->_renderer;
    }

    /**
     * Prepares the grid data for rendering.
     *
     * @return array
     */
    public function prepare()
    {
        $preparedRows = array();
        foreach ($this->getData() as $data) {
            $row = array();
            foreach ($this->getColumns() as $column) {
                /* $column Drake_Data_Grid_Column_Column */
                if (isset($data[$column->getRowField()])) {
                    $order = $column->getOrder();
                    while (isset($row[$order])) {
                        $order += 0.01;
                        $column->setOrder($order);
                    }
                    $row[$order] = $column->render($data[$column->getRowField()]);
                }
                ksort($row);
            }
            $preparedRows[] = $row;
        }

        $this->_preparedRows = $preparedRows;
        return $this->_preparedRows;
    }

    /**
     * Render the grid
     *
     * @return string
     * @throws LogicException If the view object has not been set
     */
    public function render()
    {
        if (null === $this->getView()) {
            throw new LogicException(
                "Cannot render grid: View object has not been set!");
        }
        
        return $this->getGridRenderer()
            ->setGrid($this)
            ->setView($this->getView())
            ->render();
    }
}