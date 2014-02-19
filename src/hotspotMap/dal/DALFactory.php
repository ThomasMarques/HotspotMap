<?php

namespace HotspotMap\dal;

class DALFactory
{
    /**
     * Available implementations : MySql
     */
    const IMPLEMENTATION = "MySql";

    /**
     * @var Connexion
     */
    static private $connexion;

    /**
     * @var IFinder
     */
    static private $finder;

    /**
     * @var array
     */
    static private $dataMapper = [];

    /**
     * @var array
     */
    static private $repositories = [];

    /**
     * @return Connexion
     */
    static public function getConnexion()
    {
        if(self::$connexion == null)
        {
            $className = "\\HotspotMap\\dal\\" . self::IMPLEMENTATION . "Implementation\\Connexion";
            self::$connexion = new $className();
        }

        return self::$connexion;
    }

    /**
     * @param string $entityName
     *      Available entities : User, Place, Comment
     * @return mixed
     */
    static public function getDataMapper($entityName)
    {
        if(!isset(self::$dataMapper[$entityName]))
        {
            $className = "\\HotspotMap\\dal\\" . self::IMPLEMENTATION . "Implementation\\" . self::IMPLEMENTATION . $entityName . "Mapper";
            self::$dataMapper[$entityName] = new $className(self::getConnexion());
        }
        return self::$dataMapper[$entityName];
    }

    /**
     * @return IFinder
     */
    static public function getFinder()
    {
        if(self::$finder == null)
        {
            $className = "\\HotspotMap\\dal\\" . self::IMPLEMENTATION . "Implementation\\" . self::IMPLEMENTATION . "Finder";
            self::$finder = new $className(self::getConnexion());
        }
        return self::$finder;
    }

    /**
     * @param string $entityName
     *      Available entities : User, Place, Comment
     * @return mixed
     */
    static public function getRepository($entityName)
    {
        if(!isset(self::$repositories[$entityName]))
        {
            $dataMapper = self::getDataMapper($entityName);
            $finder = self::getFinder();
            $className = "\\HotspotMap\\dal\\" . $entityName . "Repository";
            self::$repositories[$entityName] = new $className($dataMapper, $finder);
        }
        return self::$repositories[$entityName];
    }
} 