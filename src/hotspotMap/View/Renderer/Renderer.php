<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 12/02/14
 * Time: 16:38
 */

namespace hotspotMap\View\Renderer;


interface Renderer {

    public function render($app, $view, $data);

} 