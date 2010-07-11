<?php
abstract class Drake_Data_Grid_Column_Renderer_RendererAbstract
{
    protected $type;
    protected $value;

    public function setType($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue($value)
    {
        return $this->value;
    }
    
    abstract public function render()
    {
    }
}