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
        )));
        $grid->addColumn('slug', new Drake_Data_Grid_Column_Column(array(
            'name' => 'URL Slug',
            'rowField' => 'slug'
        )));

        $this->view->grid = $grid->render();
    }
}

