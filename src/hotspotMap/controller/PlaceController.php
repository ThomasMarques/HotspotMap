<?php

namespace HotspotMap\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HotspotMap\model\User;
use HotspotMap\View\ViewRenderer;

class PlaceController
{

    public function listAction (Request $request, Application $app)
    {
        return "listAction";
    }

    public function getAction (Request $request, Application $app, $id)
    {

        $user = new User();
        $user->setDisplayName("alexandre");

        return $app['renderer']->render($app, 200, $user);
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