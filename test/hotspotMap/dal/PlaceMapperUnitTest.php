<?php

namespace HotspotMap\dal;

require_once "../../../src/hotspotMap/dal/DALFactory.php";
require_once "../../../src/hotspotMap/model/Place.php";

class PlaceMapperUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PlaceRepository
     */
    protected static $placeRepository;

    /**
     * @var Connection
     */
    protected static $connexion;

    public static function setUpBeforeClass()
    {
        /// Objects construction
        self::$connexion = \HotspotMap\dal\DALFactory::getConnexion();
        self::$placeRepository = \HotspotMap\dal\DALFactory::getRepository("Place");
    }

    public static function tearDownAfterClass()
    {
        /// Objects destruction
        self::$placeRepository = NULL;
        self::$connexion = NULL;
    }

    public function testInsertPlace()
    {
        $place = new \HotspotMap\model\Place();
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
        $errors = self::$placeRepository->save($place);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with bad location
        $place->setName("Starbucks");
        $place->setLongitude(200);
        $place->setLatitude(-120);
        $errors = self::$placeRepository->save($place);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with good parameters
        $place->setLongitude(48.84951);
        $place->setLatitude(2.29791);
        $errors = self::$placeRepository->save($place);

        $this->assertEmpty($errors);
        $this->assertNotNull($place->getPlaceId());
        ///
    }

    public function testUpdatePlace()
    {
        /// Insertion
        $place = new \HotspotMap\model\Place();
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
        self::$placeRepository->save($place);
        ///

        /// Bad update with bad location
        $place->setLongitude(200);
        $place->setLatitude(-120);
        $errors = self::$placeRepository->save($place);

        $this->assertNotEmpty($errors);
        ///

        /// Update with good parameters
        $place->setLongitude(48.84951);
        $place->setLatitude(2.29791);
        $errors = self::$placeRepository->save($place);

        $this->assertEmpty($errors);
        ///
    }

    public function testDeletePlace()
    {
        /// Insertion
        $place = new \HotspotMap\model\Place();
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
        self::$placeRepository->save($place);
        ///

        /// Removing test with null id
        $placeId = $place->getPlaceId();
        $place->setPlaceId(null);
        $errors = self::$placeRepository->remove($place);

        $this->assertNotEmpty($errors);
        ///

        /// Removing test with null id
        $place->setPlaceId($placeId);
        $errors = self::$placeRepository->remove($place);

        $this->assertEmpty($errors);
        ///
    }

    protected function setUp()
    {
        /// Begin transaction
        self::$connexion->beginTransaction();
    }

    protected function tearDown()
    {
        /// RollBack
        self::$connexion->rollBack();
    }
} 