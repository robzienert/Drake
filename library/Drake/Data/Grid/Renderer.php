<?php
class Drake_Data_Grid_Renderer extends Zend_View_Helper_HtmlElement
{
    /**
     * @var Drake_Data_Grid_Grid
     */
    protected $grid;

    /**
     * Set the grid
     *
     * @param Drake_Data_Grid_Grid $grid
     * @return void
     */
    public function setGrid(Drake_Data_Grid_Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Get the grid
     *
     * @return Drake_Data_Grid_Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * Render the grid table
     *
     * @return string
     */
    public function render()
    {
        $grid = $this->getGrid();
        $rows = $grid->prepare();

        $xhtml = '<table id="' . $this->_normalizeId($grid->getId()) . '" class="data_grid">';
        $xhtml .= $this->_generateColumnHeaders();
        $xhtml .= '<tbody>';

        if (empty($rows)) {
            $xhtml .= sprintf(
                '<tr class="empty"><td colspan="%d">%s</td></tr>',
                $grid->getColumnCount(),
                $grid->getEmptyText()
            );
        } else {
            foreach ($rows as $row) {
                $xhtml .= $this->_generateRow($row);
            }
        }

        $xhtml .= '</tbody></table>';

        return $xhtml;
    }

    /**
     * Renders the column headers
     *
     * @return string
     */
    protected function _generateColumnHeaders()
    {
        $columns = array();
        foreach ($this->getGrid()->getColumns() as $column) {
            $columns = $this->view->escape($column->getName());
        }
        $xhtml = '<thead>';
        $xhtml .= $this->_renderRow($columns);
        $xhtml .= '</thead>';
        
        return $row;
    }

    /**
     * Renders an individual row
     *
     * @param array $row
     * @return string
     */
    protected function _generateRow($row)
    {
        $xhtml = '<tr>' . PHP_EOL;
        foreach ($row as $column) {
            $xhtml .= '<td>' . $column . '</td>' . PHP_EOL;
        }
        $xhtml .= '</tr>' . PHP_EOL;
        return $xhtml;
    }

    /**
     * @throws LogicException Always
     */
    public function renderer()
    {
        $this->direct();
    }

    /**
     * @throws LogicExcption Always
     */
    public function direct()
    {
        throw new LogicException("Direct access to this renderer is not permitted");
    }
}