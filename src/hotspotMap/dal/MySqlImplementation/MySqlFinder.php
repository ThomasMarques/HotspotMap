<?php

namespace HotspotMap\dal\MySqlImplementation;

require_once __DIR__ . "/../IFinder.php";

class MySqlFinder implements \HotspotMap\dal\IFinder
{
    /**
     * @var string
     */
    private $query;
    /**
     * @var array
     */
    private $parameters = [];

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
     * @param array $fieldsArray
     *      string array
     *      example : array(user.displayName, comment.content, place.name)
     * @return IFinder
     */
    public function select($fieldsArray = [])
    {
        if(!empty($fieldsArray))
        {
            $this->query = "SELECT " . $fieldsArray[0];
            for($i = 1; $i < sizeof($fieldsArray) ; ++$i)
            {
                $this->query .= ", " . $fieldsArray[$i];
            }
        }
        return $this;
    }

    /**
     * @param array $entitiesName
     *      string array
     *      example : array(user, comment, place)
     * @return IFinder
     */
    public function from($entitiesName = [])
    {
        if(!empty($entitiesName))
        {
            $this->query .= " FROM " . $entitiesName[0];
            for($i = 1; $i < sizeof($entitiesName) ; ++$i)
            {
                $this->query .= ", " . $entitiesName[$i];
            }
        }
        return $this;
    }

    /**
     * @param string $condition
     *      - Available comparators : = < > <= >= <> LIKE
     *      - Available operators : or and not
     *      example : " user.mail <> :mail or place.name LIKE :placeName "
     * @param $params
     *      example : ["mail" => "notexcepted@mail.com", "placeName" => "starting_name%"]
     * @return IFinder
     */
    public function where($condition, $params = [])
    {
        $this->parameters = $params;
        $this->query .= " WHERE " . $condition;
        return $this;
    }

    /**
     * @param array $fields
     *      example : array(user.displayName, comment.content, place.name)
     * @return IFinder
     */
    public function orderBy($fields = [])
    {
        if(!empty($fields))
        {
            $this->query .= " ORDER BY " . $fields[0];
            for($i = 1; $i < sizeof($fields) ; ++$i)
            {
                $this->query .= ", " . $fields[$i];
            }
        }
        return $this;
    }

    /**
     * @return array
     *      Execute and return search results.
     */
    public function getResults()
    {
        return $this->connexion->selectQuery($this->query, $this->parameters);
    }
} 