<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 12/02/14
 * Time: 16:37
 */

namespace hotspotMap\View\Renderer;

use HotspotMap\View\Renderer\Renderer;

class HtmlRenderer implements Renderer {

    public function render($app, $view, $data)
    {

        return $app['twig']->render('home.html', array(
            'data'=>$data
        ));

    }
}