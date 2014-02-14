<?php

namespace HotspotMap\dal;


class DALFactory {


    /**
     * @param string $entityName
     *      Available entities : User, Place, Comment
     * @param string $implementation
     *      Available implementations : MySql
     * @return mixed
     */
    static public function getDataMapper($entityName, $implementation)
    {
        $className = "HotspotMap\\dal\\" . $implementation . "Implementation\\" . $implementation . $entityName . "Mapper";
        $class = "Class".$className;
        return new $class();
    }

    /**
     * @param string $implementation
     *      Available implementations : MySql
     * @return mixed
     */
    static public function getFinder($implementation)
    {
        $className = "HotspotMap\\dal\\" . $implementation . "Implementation\\" . $implementation . "Finder";
        $class = "Class".$className;
        return new $class();
    }
} 