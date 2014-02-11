<?php

namespace hotspotMap\service;

require_once __DIR__ . "/../model/Place.php";

class PlaceMapper
{

    /**
     * @var DataAccessLayer
     */
    private $dal;

    /**
     * @param DataAccessLayer $dal
     */
    public function __construct(DataAccessLayer $dal)
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
            $isNew = null === $place->getPlaceId();
            if($isNew)
            {
                // Insert
                // TODO
                $query = <<<SQL
INSERT INTO Place
(placeId)
VALUES (NULL)
SQL;
            }
            else
            {
                // Update
                // TODO
                $query = <<<SQL
UPDATE Place
SET mailAddress = :mailAddress,
WHERE userId = :userId
SQL;
                $parameters["placeId"] = $place->getPlaceId();
            }

            /// Filling all parameters
            // TODO
            $parameters[""] = htmlentities("");
            ///

            $success = $this->dal->executeQuery($query, $parameters);
            if($success)
            {
                if($isNew)
                {
                    $place->setPlaceId((int)$this->dal->lastInsertId());
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

        if(null === $place->getPlaceId())
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

    /**
     * @param \hotspotmap\model\Place $place
     * @return array
     */
    private function checkAttribute(\hotspotmap\model\Place $place)
    {
        $errors = [];

        // TODO

        return $errors;
    }
} 