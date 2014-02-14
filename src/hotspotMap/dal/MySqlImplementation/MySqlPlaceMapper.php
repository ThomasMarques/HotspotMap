<?php

namespace HotspotMap\dal\MySqlImplementation;

require_once __DIR__ . "/../../model/Place.php";
require_once __DIR__ . "/../IPlaceMapper.php";

class MySqlPlaceMapper extends \HotspotMap\dal\IPlaceMapper
{
    /**
     * @var Connexion
     */
    private $dal;

    /**
     * @param Connexion $dal
     */
    public function __construct(Connexion $dal)
    {
        $this->dal = $dal;
    }

    /**
     * @param \hotspotmap\model\Place $place
     * @return array
     */
    public function persist(\hotspotmap\model\Place $place)
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
(placeId, name, longitude, latitude, schedules, description, coffee, internetAccess, placesNumber, comfort, frequenting, visitNumber, submissionDate, validate)
VALUES (NULL, :name, :longitude, :latitude, :schedules, :description, :coffee, :internetAccess, :placesNumber, :comfort, :frequenting, :visitNumber, :submissionDate, :validate);
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
coffee = :coffee,
internetAccess = :internetAccess,
placesNumber = :placesNumber,
comfort = :comfort,
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
            $parameters["coffee"] = ($place->getCoffee() ? 1 : 0);
            $parameters["internetAccess"] = ($place->getInternetAccess() ? 1 : 0);
            $parameters["placesNumber"] = $place->getPlacesNumber();
            $parameters["comfort"] = $place->getComfort();
            $parameters["frequenting"] = $place->getFrequenting();
            $parameters["visitNumber"] = $place->getVisitNumber();
            $parameters["submissionDate"] = $place->getSubmissionDate()->format("Y-m-d");
            $parameters["validate"] = $place->getValidate();
            ///

            $success = $this->dal->executeQuery($query, $parameters);
            if($success)
            {
                if($isNew)
                {
                    $place->setPlaceId(intval($this->dal->lastInsertId()));
                }
            }
            else
            {
                $errors = $this->dal->errorInfo();
            }
        }

        return $errors;
    }

    /**
     * @param \hotspotmap\model\Place $place
     * @return array
     */
    public function remove(\hotspotmap\model\Place $place)
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
            if(!$this->dal->executeQuery($query, $parameters))
            {
                $errors = $this->dal->errorInfo();
            }
        }
        return $errors;
    }
} 