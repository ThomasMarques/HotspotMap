<?php

namespace HotspotMap\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use HotspotMap\model\User;
use HotspotMap\model\Place;
use HotspotMap\View\ViewRenderer;
use Hateoas\Configuration\Route;
use Hateoas\Configuration\Annotation\Relation;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

class PlaceController
{

    public function listAction (Request $request, Application $app, $page = 1)
    {

        $place1 = new Place();
        $place1->setName("Test");
        $place1->setPlaceId(4);
        $place2 = new Place();
        $place2->setName("Test");
        $place2->setPlaceId(4);

        $limit=20;
        $total=1;
        $result = $app['collection-helper']->buildCollection(array($place1,$place2), 'place_list', 'places', $page, $limit, $total);

        return $app['renderer']->render($app, 200, $result);

    }

    public function getAction (Request $request, Application $app, $id)
    {

        $place = new Place();
        $place->setName("Test");
        $place->setPlaceId(4);

        return $app['renderer']->render($app, 200, $place);
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