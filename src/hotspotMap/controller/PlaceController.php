<?php

namespace HotspotMap\Controller;

use HotspotMap\dal\DALFactory;
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
    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    public function __construct()
    {
        $this->placeRepository = DALFactory::getRepository("Place");
    }

    public function listAction (Request $request, Application $app, $page = 1)
    {
        $limit=20;

        $places = $this->placeRepository->findAllByPage($page, $limit);
        $total = $this->placeRepository->countPlaces();

        $result = $app['collection-helper']->buildCollection($places, 'place_list', 'places', $page, $limit, $total);

        return $app['renderer']->render($app, 200, $result);

    }

    public function getAction (Request $request, Application $app, $id)
    {

        $place = $this->placeRepository->findOneById($id);

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
        /// Checking rights

        $place = new Place();
        $place->setPlaceId($id);
        $errors = $this->placeRepository->remove($place);

        if(!isEmpty($errors))
        {
            return implode("</br>", $errors);
        }

        return "deleteAction completed";
    }

}