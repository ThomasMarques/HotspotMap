<?php

namespace hotspotMap\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PlaceController
{

    public function listAction (Request $request, Application $app)
    {
        return "listAction";
    }

    public function getAction (Request $request, Application $app, $id)
    {
        return "getAction";
    }

    public function postAction (Request $request, Application $app)
    {
        return "postAction";
    }

    public function putAction (Request $request, Application $app, $id)
    {
        return "putAction";
    }

    public function deleteAction (Request $request, Application $app, $id)
    {
        return "deleteAction";
    }

}