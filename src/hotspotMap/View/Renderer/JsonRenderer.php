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

class JsonRenderer implements Renderer {

    public function render($data)
    {

        $serializer = HateoasBuilder::create()->build();

        return $serializer->serialize($data, 'json');
    }
}