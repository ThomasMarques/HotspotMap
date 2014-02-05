<?php

namespace HotspotMap\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PlaceController
{

    public function listPlaces (Request $request, Application $app)
    {
        return "places list";
    }

}