<?php

namespace HotspotMap\dal\MySqlImplementation;

require_once "IFinder.php";

class MySqlFinder implements HotspotMap\dal\IFinder
{
    /**
     * @param array $fieldsArray
     *      string array
     *      example : array(user.displayName, comment.content, place.name)
     * @return IFinder
     */
    public function select($fieldsArray = [])
    {

    }

    /**
     * @param array $entityName
     *      string array
     *      example : array(user, comment, place)
     * @return IFinder
     */
    public function from($entityName= [])
    {

    }

    /**
     * @param string $condition
     *      - Available comparators : = < > <= >= <> LIKE
     *      - Available operators : or and not
     *      example : " user.mail <> 'notexcepted@mail.com' or place.name LIKE 'starting_name%' "
     * @return IFinder
     */
    public function where($condition)
    {

    }

    /**
     * @param array $fields
     *      example : array(user.displayName, comment.content, place.name)
     * @return IFinder
     */
    public function orderBy($fields = [])
    {

    }

    /**
     * @return array
     *      Execute and return search results.
     */
    public function getResults()
    {

    }
} 