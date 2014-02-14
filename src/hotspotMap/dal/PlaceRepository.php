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
     * @return \hotspotmap\model\Place
     */
    public function findOneById($id)
    {
    }

    /**
     * @param \hotspotmap\model\Place $place
     * @return bool
     */
    public function save(\hotspotmap\model\Place $place)
    {
        $this->placeMapper->persist($place);
    }

    /**
     * @param \hotspotmap\model\Place $place
     * @return bool
     */
    public function remove(\hotspotmap\model\Place $place)
    {
        $this->placeMapper->remove($place);
    }
} 