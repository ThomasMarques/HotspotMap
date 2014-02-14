<?php

namespace HotspotMap\dal;


abstract class IPlaceMapper
{
    /**
     * @param \hotspotmap\model\Place $place
     * @return array
     */
    abstract public function persist(\hotspotmap\model\Place $place);

    /**
     * @param \hotspotmap\model\Place $place
     * @return array
     */
    abstract public function remove(\hotspotmap\model\Place $place);

    /**
     * @param \hotspotmap\model\Place $place
     * @return array
     */
    protected function checkAttribute(\hotspotmap\model\Place $place)
    {
        $errors = [];

        if(null == $place->getName() || strlen($place->getName()) < 1)
        {
            $errors["name"] = "The attribute name cannot be null or empty.";
        }
        if(null == $place->getLatitude() || $place->getLatitude() < -90 || $place->getLatitude() > 90)
        {
            $errors["latitude"] = "The latitude must be between -90 and 90.";
        }
        if(null == $place->getLongitude() || $place->getLongitude() < -180 || $place->getLongitude() > 180)
        {
            $errors["longitude"] = "The latitude must be between -180 and 180.";
        }
        if(null == $place->getSchedules() || strlen($place->getSchedules()) < 1)
        {
            $errors["schedules"] = "The schedules cannot be null or empty.";
        }

        return $errors;
    }

}