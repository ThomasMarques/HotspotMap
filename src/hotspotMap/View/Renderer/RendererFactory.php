<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 12/02/14
 * Time: 16:37
 */

namespace hotspotMap\View\Renderer;

class RendererFactory {

    public function getRenderer($type)
    {
        $className = 'HotspotMap\\View\\Renderer\\'.$type.'Renderer';
        return new $className();
    }

} 