<?php
abstract class Drake_Data_Grid_Column_Renderer_RendererAbstract
{
    protected $type;

    public function setType($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
    
    abstract public function render()
    {
    }
}