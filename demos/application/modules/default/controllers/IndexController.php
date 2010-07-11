<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function gridAction()
    {
        $data = array();
        for ($i = 1; $i <= 20; $i++) {
            $id = rand(1, 100);
            $data[] = array(
                'id' => $id,
                'title' => 'Test ' . $id,
                'slug' => 'test-' . $id,
            );
        }

        $grid = new Drake_Data_Grid_Grid(array(
            'id' => 'test_grid',
            'view' => $this->view,
            'data' => $data,
        ));
        $grid->addColumn('id', new Drake_Data_Grid_Column_Column(array(
            'name' => 'ID',
            'rowField' => 'id',
        )));
        $grid->addColumn('title', new Drake_Data_Grid_Column_Column(array(
            'name' => 'Title',
            'rowField' => 'title',
            'width' => 100,
        )));
        $grid->addColumn('slug', new Drake_Data_Grid_Column_Column(array(
//            'filter' => 'none',
//            'filter' => new Drake_Data_Grid_Column_Filter_None(),
            'name' => 'URL Slug',
            'rowField' => 'slug',
            'width' => 75,
        )));
        
        // @todo Column filter/renderer overrides need to be done after the
        // column has been added right now. This needs to change, as well as
        // accepting names instead of just objects.
        $grid->getColumn('slug')->setFilter(new Drake_Data_Grid_Column_Filter_None());

        $this->view->grid = $grid->render();
    }
}

