<?php

namespace HotspotMap\View\Renderer;

use HotspotMap\View\Renderer\Renderer;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;

class JsonRenderer implements Renderer {

    public function render($app, $view, $data)
    {

        $serializer = $app['serializer'];

        return $serializer->serialize($data, 'json');
    }
}