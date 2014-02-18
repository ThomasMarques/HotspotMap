<?php

namespace HotspotMap\View\Renderer;

use HotspotMap\View\Renderer\Renderer;
use Hateoas\HateoasBuilder;

class JsonRenderer implements Renderer {

    public function render($app, $view, $data)
    {

        $serializer = HateoasBuilder::create()->build();

        return $serializer->serialize($data, 'json');
    }
}