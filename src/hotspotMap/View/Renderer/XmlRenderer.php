<?php

namespace HotspotMap\View\Renderer;

use HotspotMap\View\Renderer\Renderer;
use Hateoas\HateoasBuilder;

class XmlRenderer implements Renderer {

    public function render($data)
    {

        $serializer = HateoasBuilder::create()->build();

        return $serializer->serialize($data, 'xml');
    }
}