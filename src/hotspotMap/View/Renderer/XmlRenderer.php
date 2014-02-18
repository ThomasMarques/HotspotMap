<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 12/02/14
 * Time: 16:37
 */

namespace hotspotMap\View\Renderer;

use HotspotMap\View\Renderer\Renderer;
use Hateoas\HateoasBuilder;

class XmlRenderer implements Renderer {

    public function render($app, $view, $data)
    {

        $serializer = HateoasBuilder::create()->build();

        return $serializer->serialize($data, 'xml');
    }
}