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
    protected $columns = array();

    /**
     * @var array
     */
    protected $columnFilters = array();

    /**
     * @var array
     */
    protected $columnRenderers = array();

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var string
     */
    protected $emptyText = 'No record found.';

    /**
     * @var array|null
     */
    protected $preparedRows;

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
     * Set the text that will be displayed when no entries can be displayed by
     * the grid.
     *
     * @param string $emptyText
     * @return void
     */
    public function setEmptyText($emptyText)
    {
        $this->emptyText = $emptyText;
    }

    /**
     * Set the grid data
     *
     * @param array $data
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get the grid data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Add a new column to the grid
     *
     * @param string $columnId
     * @param Drake_Data_Grid_Column_Column $column
     */
    public function addColumn($columnId, Drake_Data_Grid_Column_Column $column)
    {
        $columnId = Drake_Util_StringInflector::underscore($columnId);
        if (isset($this->columns[$columnId])) {
            throw new LogicException(
                "Cannot add new column `$columnId`: A column already exists with that id");
        }
        $column->setId($columnId);
        $this->columns[$columnId] = $column;
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
        if (isset($this->columns[$columnId])) {
            return $this->columns[$columnId];
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
        return $this->columns;
    }

    /**
     * Remove a single column by id.
     *
     * @param string $columnId
     * @return void
     */
    public function removeColumn($columnId)
    {
        $columnId = Drake_Util_StringInflector::underscore($columnId);
        if (isset($this->columns[$columnId])) {
            unset($this->columns[$columnId]);
        }
    }

    /**
     * Retrieve the number of columns in the grid
     *
     * @return int
     */
    public function getColumnCount()
    {
        return count($this->columns);
    }

    /**
     * Add a column renderer to the internal cache
     *
     * @param Drake_Data_Grid_Column_Renderer_RendererAbstract $renderer
     * @return void
     */
    public function addColumnRenderer(Drake_Data_Grid_Column_Renderer_RendererAbstract $renderer)
    {
        $name = $renderer->getName();
        if (isset($this->columnRenderers[$name])) {
            throw new LogicException(
                "Renderer `$name` has already been registered with the grid");
        }
        $this->columnRenderers[$name] = $renderer;
    }

    /**
     * Get a column renderer from the internal cache. If it does not exist, this
     * will return false.
     *
     * @param string $name
     * @return Drake_Data_Grid_Column_Renderer_RendererAbstract|false
     */
    public function getColumnRenderer($name)
    {
        $name = Drake_Util_StringInflector::underscore($name);
        if (isset($this->columnRenderers[$name])) {
            return $this->columnRenderers[$name];
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
        return $this->columnRenderers;
    }

    /**
     * Remove a registered column renderer.
     *
     * @param string $name
     * @return void
     */
    public function removeColumnRenderer($name)
    {
        $name = Drake_Util_StringInflector::underscore($name);
        if (isset($this->columnRenderers[$name])) {
            unset($this->columnRenderers[$name]);
        }
    }

    /**
     * Add a column filter to the internal cache
     *
     * @param Drake_Data_Grid_Column_Filter_FilterAbstract $renderer
     * @return void
     */
    public function addColumnFilter(Drake_Data_Grid_Column_Filter_FilterAbstract $filter)
    {
        $name = $filter->getName();
        if (isset($this->columnFilters[$name])) {
            throw new LogicException(
                "Filter `$name` has already been registered with the grid");
        }
        $this->columnFilters[$name] = $filter;
    }

    /**
     * Get a column filter from the internal cache. If it does not exist, this
     * will return false.
     *
     * @param string $name
     * @return Drake_Data_Grid_Column_Filter_FilterAbstract|false
     */
    public function getColumnFilter($name)
    {
        $name = Drake_Util_StringInflector::underscore($name);
        if (isset($this->columnFilters[$name])) {
            return $this->columnFilters[$name];
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
        return $this->columnFilters;
    }

    /**
     * Remove a registered column filter.
     *
     * @param string $name
     * @return void
     */
    public function removeColumnFilter($name)
    {
        $name = Drake_Util_StringInflector::underscore($name);
        if (isset($this->columnFilters[$name])) {
            unset($this->columnFilters[$name]);
        }
    }

    /**
     * Prepares the grid data for rendering.
     *
     * @return array
     */
    public function prepare()
    {
        $preparedRows[] = array();
        foreach ($this->getData as $data) {
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

        $this->preparedRows = $preparedRows;
        return $this->preparedRows;
    }

    /**
     * Render the grid
     *
     * @return string
     */
    public function render()
    {
        if (null === $this->preparedRows) {
            $this->prepare();
        }

        if (empty($this->preparedRows)) {
            // Render the empty text var
        }

        // @todo Default grid renderer? prepare() allows people to roll their
        // own template.
    }
}