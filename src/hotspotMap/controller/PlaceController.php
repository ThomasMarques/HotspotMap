<?php

namespace HotspotMap\Controller;

use HotspotMap\dal\DALFactory;
use HotspotMap\dal\PlaceRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use HotspotMap\model\Place;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

class PlaceController extends Controller
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

        if(null == $place)
        {
            $errors[] = "No place found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 200, $place);
    }

    public function postAction (Request $request, Application $app)
    {
        $place = $this->fillPlaceWithRequestAttribute($request, new Place());

        /// Not editable, only fix by us at creation.
        $place->setSubmissionDate(new \DateTime());
        $place->setVisitNumber(0);
        $place->setValidate(false);

        $errors = $this->placeRepository->save($place);

        if(!empty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 201, $place);
    }

    public function putAction (Request $request, Application $app, $id)
    {
        /// Checking rights
        /// Only Author, moderator and admin
        /// 401 if not connected
        /// 405 if connected but not allowed

        $place = $this->placeRepository->findOneById($id);

        if(null == $place)
        {
            $errors[] = "No place found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        $place = $this->fillPlaceWithRequestAttribute($request, $place);

        /// TODO -> TO THINK
        /// $place->setValidate(false);

        $errors = $this->placeRepository->save($place);

        if(!isEmpty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 204, null);
    }

    public function deleteAction (Request $request, Application $app, $id)
    {
        /// Checking rights
        /// Only Author, moderator and admin
        /// 401 if not connected
        /// 405 if connected but not allowed

        $place = $this->placeRepository->findOneById($id);

        if(null == $place)
        {
            $errors[] = "No place found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        $errors = $this->placeRepository->remove($place);

        if(!empty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 204, null);
    }

    /**
     * @param Request $request
     * @param Place $place
     * @return Place
     */
    private function fillPlaceWithRequestAttribute(Request $request, Place $place)
    {
        $name = $request->get("name", null);
        if(null != $name)
            $place->setName($name);

        $longitude = $request->get("longitude", null);
        if(null != $longitude)
            $place->setLongitude(intval($longitude));

        $latitude = $request->get("latitude", null);
        if(null != $latitude)
            $place->setLatitude(intval($latitude));

        $schedules = $request->get("schedules", null);
        if(null != $schedules)
            $place->setSchedules($schedules);

        $description = $request->get("description", null);
        if(null != $description)
            $place->setDescription($description);

        $hotspotType = $request->get("hotspotType", null);
        if(null != $hotspotType)
            $place->setHotspotType(intval($hotspotType));

        $coffee = $request->get("coffee", null);
        if(null != $coffee)
            $place->setCoffee(intval($coffee) != 0);

        $internetAccess = $request->get("internetAccess", null);
        if(null != $internetAccess)
            $place->setCoffee(intval($internetAccess) != 0);

        $placesNumber = $request->get("placesNumber", null);
        if(null != $placesNumber)
            $place->setPlacesNumber(intval($placesNumber));

        $comfort = $request->get("comfort", null);
        if(null != $comfort)
            $place->setPlacesNumber(intval($comfort));

        $frequenting = $request->get("frequenting", null);
        if(null != $frequenting)
            $place->setFrequenting(intval($frequenting));

        return $place;
    }
}