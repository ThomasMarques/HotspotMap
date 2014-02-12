<?php

namespace hotspotMap\service;

require_once "../../../src/hotspotMap/service/PlaceMapper.php";
require_once "../../../src/hotspotMap/service/DataAccessLayer.php";
require_once "../../../src/hotspotMap/model/Place.php";

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
        /// Insertion with null arguments (name, latitude, longitude)
        $place->setSchedules("07:30 – 21:00");//\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00");
        $place->setDescription("Good Starbuks with Wifi");
        $place->setCoffee(true);
        $place->setInternetAccess(true);
        $place->setPlacesNumber(100);
        $place->setComfort(4);
        $place->setFrequenting(4);
        $place->setSubmissionDate(new \DateTime());
        $place->setVisitNumber(0);
        $place->setValidate(0);
        $errors = self::$placeMapper->persist($place);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with bad location
        $place->setName("Starbucks");
        $place->setLongitude(200);
        $place->setLatitude(-120);
        $errors = self::$placeMapper->persist($place);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with good parameters
        $place->setLongitude(48.84951);
        $place->setLatitude(2.29791);
        $errors = self::$placeMapper->persist($place);

        $this->assertEmpty($errors);
        $this->assertNotNull($place->getPlaceId());
        ///
    }

    public function testUpdatePlace()
    {
        /// Insertion
        $place = new \hotspotMap\model\Place();
        $place->setName("Starbucks");
        $place->setSchedules("07:30 – 21:00");//\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00");
        $place->setDescription("Good Starbuks with Wifi");
        $place->setCoffee(true);
        $place->setInternetAccess(true);
        $place->setPlacesNumber(100);
        $place->setComfort(4);
        $place->setFrequenting(4);
        $place->setSubmissionDate(new \DateTime());
        $place->setVisitNumber(0);
        $place->setValidate(0);
        self::$placeMapper->persist($place);
        ///

        /// Bad update with bad location
        $place->setLongitude(200);
        $place->setLatitude(-120);
        $errors = self::$placeMapper->persist($place);

        $this->assertNotEmpty($errors);
        ///

        /// Update with good parameters
        $place->setLongitude(48.84951);
        $place->setLatitude(2.29791);
        $errors = self::$placeMapper->persist($place);

        $this->assertEmpty($errors);
        ///
    }

    public function testDeletePlace()
    {
        /// Insertion
        $place = new \hotspotMap\model\Place();
        $place->setName("Starbucks");
        $place->setLongitude(48.84951);
        $place->setLatitude(2.29791);
        $place->setSchedules("07:30 – 21:00");//\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00\n07:30 – 21:00");
        $place->setDescription("Good Starbuks with Wifi");
        $place->setCoffee(true);
        $place->setInternetAccess(true);
        $place->setPlacesNumber(100);
        $place->setComfort(4);
        $place->setFrequenting(4);
        $place->setSubmissionDate(new \DateTime());
        $place->setVisitNumber(0);
        $place->setValidate(0);
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