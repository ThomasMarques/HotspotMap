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
        if(sizeof($data) == 1)
        {
            $place = $this->createPlaceFromData($data[0]);
        }
        return $place;
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
     * @return bool
     */
    public function save(\Hotspotmap\model\Place $place)
    {
        return $this->placeMapper->persist($place);
    }

    /**
     * @param \Hotspotmap\model\Place $place
     * @return bool
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
        $place->setCoffee(intval($placeData[6]) == 1);
        $place->setInternetAccess(intval($placeData[7]) == 1);
        $place->setPlacesNumber(intval($placeData[8]));
        $place->setComfort(intval($placeData[9]));
        $place->setFrequenting(intval($placeData[10]));
        $place->setVisitNumber(intval($placeData[11]));
        $place->setSubmissionDate($date = date_create_from_format("Y-m-d", $placeData[12]));
        $place->setValidate(intval($placeData[13]) == 1);

        return $place;
    }
} 