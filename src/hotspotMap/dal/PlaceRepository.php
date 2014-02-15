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
} 