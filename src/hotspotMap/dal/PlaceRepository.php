<?php

namespace HotspotMap\dal;

require_once "IPlaceMapper.php";
require_once "IFinder.php";

class PlaceRepository
{
    /**
     * @var \HotspotMap\dal\IPlaceMapper
     */
    private $placeMapper;

    /**
     * @var \HotspotMap\dal\IFinder
     */
    private $finder;

    /**
     * @param \HotspotMap\dal\IPlaceMapper $placeMapper
     * @param \HotspotMap\dal\IFinder $finder
     */
    public function __construct(\HotspotMap\dal\IPlaceMapper $placeMapper, \HotspotMap\dal\IFinder $finder)
    {
        $this->placeMapper = $placeMapper;
        $this->finder = $finder;
    }

    /**
     * @return int
     */
    public function countValidatePlaces()
    {
        $data = $this->finder->select(array("count(*)"))
            ->from(array("Place"))
            ->getResults();

        return intval($data[0][0]);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array of Place
     */
    public function findAllValidateByPage($page, $limit)
    {
        $data = $this->finder->select(array("*"))
            ->from(array("Place"))
            ->where("validate = :validate", ["validate" => 1])
            ->limit(($page-1) * $limit, $limit)
            ->getResults();

        $places = [];
        for( $i = 0 ; $i < sizeof($data) ; ++$i )
        {
            $place = $this->createPlaceFromData($data[$i]);
            $places[] = $place;
        }
        return $places;
    }

    /**
     * @param int $id
     * @return \Hotspotmap\model\Place
     */
    public function findOneById($id)
    {
        $data = $this->finder->select(array("*"))
            ->from(array("Place"))
            ->where("placeId = :placeId", ["placeId" => $id])
            ->getResults();

        $place = null;
        for( $i = 0 ; $i < sizeof($data) ; ++$i )
        {
            $place = $this->createPlaceFromData($data[0]);
        }
        return $place;
    }

    /**
     * @param int $latitude
     * @param int $longitude
     * @param int $searchDistance
     * @param int $page
     * @param int $limit
     * @return array of Place
     */
    public function findNearest($latitude, $longitude, $searchDistance, $page, $limit) {

        $places = $this->findAllValidateByPage($page, $limit);

        $lat = $latitude;
        $lon = $longitude;

        $result = [];
        foreach ($places as $place)
        {
            $distance = ( 6371 * acos( cos( deg2rad( $lat ) ) * cos( deg2rad( $place->getLatitude() ) )
                    * cos( deg2rad( $place->getLongitude() ) - deg2rad( $lon ) ) + sin( deg2rad( $lat ) ) * sin(deg2rad( $place->getLatitude() )) ) );
            if ($distance < $searchDistance)
            {
                $result[] = $place;
            }
        }

        return $result;

    }

    /**
     * @return array of \Hotspotmap\model\Place
     */
    public function findAllNotValidated()
    {
        $data = $this->finder->select(array("*"))
            ->from(array("Place"))
            ->where("validate = :validate", ["validate" => 0])
            ->getResults();

        $places = [];
        for( $i = 0 ; $i < sizeof($data) ; ++$i )
        {
            $place = $this->createPlaceFromData($data[$i]);
            $places[] = $place;
        }
        return $places;
    }

    /**
     * @param \Hotspotmap\model\Place $place
     * @return array
     */
    public function save(\Hotspotmap\model\Place $place)
    {
        return $this->placeMapper->persist($place);
    }

    /**
     * @param \Hotspotmap\model\Place $place
     * @return array
     */
    public function remove(\Hotspotmap\model\Place $place)
    {
        return $this->placeMapper->remove($place);
    }


    private function createPlaceFromData($placeData  = [])
    {
        $place = new \HotspotMap\model\Place();
        $place->setPlaceId($placeData[0]);
        $place->setName($placeData[1]);
        $place->setLatitude(floatval($placeData[2]));
        $place->setLongitude(floatval($placeData[3]));
        $place->setSchedules($placeData[4]);
        $place->setDescription($placeData[5]);
        $place->setCoffee(intval($placeData[6]));
        $place->setCoffee(intval($placeData[7]) == 1);
        $place->setInternetAccess(intval($placeData[8]) == 1);
        $place->setPlacesNumber(intval($placeData[9]));
        $place->setComfort(intval($placeData[10]));
        $place->setFrequenting(intval($placeData[11]));
        $place->setVisitNumber(intval($placeData[12]));
        $place->setSubmissionDate($date = date_create_from_format("Y-m-d", $placeData[13]));
        $place->setValidate(intval($placeData[14]) == 1);

        return $place;
    }
} 