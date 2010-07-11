<?php
class Drake_Data_Grid_Column_Renderer_Text
    extends Drake_Data_Grid_Column_Renderer_RendererAbstract
{
    protected $type = 'text';

    public function render()
    {
        return $this->getValue();
    }
}