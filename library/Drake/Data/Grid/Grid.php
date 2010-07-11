<?php
class Drake_Data_Grid_Grid
{
    protected $columns = array();
    protected $columnFilters = array();
    protected $columnRenderers = array();
    protected $data = array();
    protected $emptyText = 'No record found.';

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

    public function addColumnRenderer(Drake_Data_Grid_Column_Renderer_RendererAbstract $renderer)
    {
        $name = $renderer->getName();
        if (isset($this->columnRenderers[$name])) {
            throw new LogicException(
                "Renderer `$name` has already been registered with the grid");
        }
        $this->columnRenderers[$name] = $renderer;
    }

    public function getColumnRenderer($name)
    {
        $name = Drake_Util_StringInflector::underscore($name);
        if (isset($this->columnRenderers[$name])) {
            return $this->columnRenderers[$name];
        }
        return false;
    }

    public function getColumnRenderers()
    {
        return $this->columnRenderers;
    }

    public function removeColumnRenderer($name)
    {
        $name = Drake_Util_StringInflector::underscore($name);
        if (isset($this->columnRenderers[$name])) {
            unset($this->columnRenderers[$name]);
        }
    }

    public function addColumnFilter(Drake_Data_Grid_Column_Filter_FilterAbstract $filter)
    {
        $name = $filter->getName();
        if (isset($this->columnFilters[$name])) {
            throw new LogicException(
                "Filter `$name` has already been registered with the grid");
        }
        $this->columnFilters[$name] = $filter;
    }

    public function getColumnFilter($name)
    {
        $name = Drake_Util_StringInflector::underscore($name);
        if (isset($this->columnFilters[$name])) {
            return $this->columnFilters[$name];
        }
        return false;
    }

    public function getColumnFilters()
    {
        return $this->columnFilters;
    }

    public function removeColumnFilter($name)
    {
        $name = Drake_Util_StringInflector::underscore($name);
        if (isset($this->columnFilters[$name])) {
            unset($this->columnFilters[$name]);
        }
    }
}