<?php

namespace HotspotMap\View\Renderer;

class RendererFactory {

    public function getRenderer($type)
    {
        $className = 'HotspotMap\\View\\Renderer\\'.$type.'Renderer';
        return new $className();
    }

} 