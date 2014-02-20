<?php

namespace HotspotMap\dal\MySqlImplementation;

require_once __DIR__ . "/../../model/Place.php";
require_once __DIR__ . "/../IPlaceMapper.php";

class MySqlPlaceMapper extends \HotspotMap\dal\IPlaceMapper
{
    /**
     * @var Connexion
     */
    private $connexion;

    /**
     * @param Connexion $connexion
     */
    public function __construct(Connexion $connexion)
    {
        $this->connexion = $connexion;
    }

    /**
     * @param \Hotspotmap\model\Place $place
     * @return array
     */
    public function persist(\Hotspotmap\model\Place $place)
    {
        $errors = $this->checkAttribute($place);

        if(empty($errors))
        {
            $parameters = [];
            $isNew = (null == $place->getPlaceId());
            if($isNew)
            {
                // Insert
                $query = <<<SQL
INSERT INTO place
(placeId, name, longitude, latitude, schedules, description, hotspotType, coffee, internetAccess, placesNumber, comfort, frequenting, visitNumber, submissionDate, validate)
VALUES (NULL, :name, :longitude, :latitude, :schedules, :description, :hotspotType, :coffee, :internetAccess, :placesNumber, :comfort, :frequenting, :visitNumber, :submissionDate, :validate);
SQL;
            }
            else
            {
                // Update
                $query = <<<SQL
UPDATE Place
SET name = :name,
longitude = :longitude,
latitude = :latitude,
schedules = :schedules,
description = :description,
hotspotType = :hotspotType,
coffee = :coffee,
internetAccess = :internetAccess,
placesNumber = :placesNumber,
comfort = :comfort,
frequenting = :frequenting,
visitNumber = :visitNumber,
submissionDate = :submissionDate,
validate = :validate
WHERE placeId = :placeId
SQL;
                $parameters["placeId"] = $place->getPlaceId();
            }

            /// Filling all parameters
            $parameters["name"] = $place->getName();
            $parameters["longitude"] = $place->getLongitude();
            $parameters["latitude"] = $place->getLatitude();
            $parameters["schedules"] = $place->getSchedules();
            $parameters["description"] = $place->getDescription();
            $parameters["hotspotType"] = $place->getHotspotType();
            $parameters["coffee"] = ($place->getCoffee() ? 1 : 0);
            $parameters["internetAccess"] = ($place->getInternetAccess() ? 1 : 0);
            $parameters["placesNumber"] = $place->getPlacesNumber();
            $parameters["comfort"] = $place->getComfort();
            $parameters["frequenting"] = $place->getFrequenting();
            $parameters["visitNumber"] = $place->getVisitNumber();
            $parameters["submissionDate"] = $place->getSubmissionDate()->format("Y-m-d");
            $parameters["validate"] = $place->getValidate();
            ///

            $success = $this->connexion->executeQuery($query, $parameters);
            if($success)
            {
                if($isNew)
                {
                    $place->setPlaceId(intval($this->connexion->lastInsertId()));
                }
            }
            else
            {
                $errors = $this->connexion->errorInfo();
            }
        }

        return $errors;
    }

    /**
     * @param \Hotspotmap\model\Place $place
     * @return array
     */
    public function remove(\Hotspotmap\model\Place $place)
    {
        $errors = [];

        if(null == $place->getPlaceId())
        {
            $errors["id"] = "Missing Id";
        }
        else
        {
            $parameters = [];
            $query = <<<SQL
DELETE FROM Place
WHERE placeId = :placeId
SQL;
            $parameters["placeId"] = $place->getPlaceId();
            if(!$this->connexion->executeQuery($query, $parameters))
            {
                $errors = $this->connexion->errorInfo();
            }
        }
        return $errors;
    }
} 