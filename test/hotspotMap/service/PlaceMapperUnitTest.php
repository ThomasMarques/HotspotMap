<?php

namespace hotspotMap\service;

require_once "../../../src/hotspotMap/service/PlaceMapper.php";
require_once "../../../src/HotspotMap/service/DataAccessLayer.php";
require_once "../../../src/HotspotMap/model/Place.php";

class PlaceMapperUnitTest extends \PHPUnit_Framework_TestCase
{
    protected static $dal;
    protected static $placeMapper;

    public static function setUpBeforeClass()
    {
        /// Objects construction
        /// Connexion to the database for tests
        self::$dal = new DataAccessLayer("mysql:host=localhost;dbname=hotspotmaptest", "root", "");
        self::$placeMapper  = new PlaceMapper(self::$dal);
    }

    public static function tearDownAfterClass()
    {
        /// Objects destruction
        self::$dal = NULL;
        self::$placeMapper = NULL;
    }

    public function testInsertPlace()
    {
        $place = new \hotspotMap\model\Place();
        /// Insertion with null...

        $errors = self::$placeMapper->persist($place);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with bad...
        $errors = self::$placeMapper->persist($place);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with good parameters...
        $errors = self::$placeMapper->persist($place);

        $this->assertEmpty($errors);
        $this->assertNotNull($place->getPlaceId());
        ///

        /// Violated constraint ...
        $place = new \hotspotMap\model\Place();

        $errors = self::$userMapper->persist($place);

        $this->assertNotEmpty($errors);
        ///
    }

    public function testUpdatePlace()
    {
        /// Insertion
        $place = new \hotspotMap\model\Place();
        self::$placeMapper->persist($place);
        ///

        /// Bad update with bad...
        $errors = self::$placeMapper->persist($place);

        $this->assertNotEmpty($errors);
        ///

        /// Update with good parameters...
        $errors = self::$placeMapper->persist($place);

        $this->assertEmpty($errors);
        ///
    }

    public function testDeletePlace()
    {
        /// Insertion
        $place = new \hotspotMap\model\Place();
        self::$placeMapper->persist($place);
        ///

        /// Removing test with null id
        $placeId = $place->getPlaceId();
        $place->setPlaceId(null);
        $errors = self::$placeMapper->remove($place);

        $this->assertNotEmpty($errors);
        ///

        /// Removing test with null id
        $place->setPlaceId($placeId);
        $errors = self::$placeMapper->remove($place);

        $this->assertEmpty($errors);
        ///
    }

    protected function setUp()
    {
        /// Begin transaction
        self::$dal->beginTransaction();
    }

    protected function tearDown()
    {
        /// RollBack
        self::$dal->rollBack();
    }
} 