<?php
class Drake_Data_Grid_Column_Column
{
    protected $emptyText;
    protected $filter;
    protected $grid;
    protected $id;
    protected $name;
    protected $renderer;
    protected $rowField;
    protected $type;

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

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRowField($rowField)
    {
        $this->rowField = $rowField;
    }

    public function getRowField()
    {
        return $this->rowField;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setGrid(Drake_Data_Grid_Grid $grid)
    {
        $this->grid = $grid;
    }

    public function getGrid()
    {
        return $this->grid;
    }

    public function setRenderer(Drake_Data_Grid_Column_Renderer_RendererAbstract $renderer)
    {
        $renderers = $this->getGrid()->getColumnRenderers();
        if (!isset($renderers[$renderer->getType()])) {
            $this->getGrid()->addColumnRenderer($renderer);
        }
        
        $this->renderer = $renderer;
    }

    public function getRenderer()
    {
        if (null === $this->renderer) {
            $this->setRenderer($this->_getRendererByType());
        }
        return $this->renderer;
    }

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

    public function setFilter(Drake_Data_Grid_Column_Filter_FilterAbstract $filter)
    {
        $filters = $this->getGrid()->getColumnFilters();
        if (!isset($filters[$filter->getType()])) {
            $this->getGrid()->addColumnFilter($filter);
        }
        
        $this->filter = $filter;
    }

    public function getFilter()
    {
        if (null === $this->filter) {
            $this->setFilter($this->_getFilterByType());
        }
        return $this->filter;
    }

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


}