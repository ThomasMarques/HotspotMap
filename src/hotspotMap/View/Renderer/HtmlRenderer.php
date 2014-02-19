<?php

namespace HotspotMap\View\Renderer;

use HotspotMap\View\Renderer\Renderer;

class HtmlRenderer implements Renderer {

    public function render($app, $view, $data)
    {

        return $app['twig']->render('home.html', array(
            'data'=>$data
        ));

    }
}