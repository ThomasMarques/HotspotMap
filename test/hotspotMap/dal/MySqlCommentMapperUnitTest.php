<?php

namespace HotspotMap\dal;

require_once "../../../src/hotspotMap/dal/MySqlImplementation/Connexion.php";
require_once "../../../src/hotspotMap/dal/ICommentMapper.php";
require_once "../../../src/hotspotMap/dal/IPlaceMapper.php";
require_once "../../../src/hotspotMap/dal/IUserMapper.php";
require_once "../../../src/hotspotMap/dal/MySqlImplementation/MySqlCommentMapper.php";
require_once "../../../src/hotspotMap/dal/MySqlImplementation/MySqlPlaceMapper.php";
require_once "../../../src/hotspotMap/dal/MySqlImplementation/MySqlUserMapper.php";
require_once "../../../src/hotspotMap/model/Comment.php";
require_once "../../../src/hotspotMap/model/Place.php";
require_once "../../../src/hotspotMap/model/User.php";

class MySqlCommentMapperUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connexion
     */
    protected static $dal;

    /**
     * @var ICommentMapper
     */
    protected static $commentMapper;

    /**
     * @var IPlaceMapper
     */
    protected static $placeMapper;

    /**
     * @var IUserMapper
     */
    protected static $userMapper;

    public static function setUpBeforeClass()
    {
        /// Objects construction
        /// Connexion to the database for tests
        self::$dal = new \HotspotMap\dal\MySqlImplementation\Connexion("mysql:host=localhost;dbname=hotspotmaptest", "root", "");
        self::$commentMapper  = new \HotspotMap\dal\MySqlImplementation\MySqlCommentMapper(self::$dal);
        self::$placeMapper = new \HotspotMap\dal\MySqlImplementation\MySqlPlaceMapper(self::$dal);
        self::$userMapper = new \HotspotMap\dal\MySqlImplementation\MySqlUserMapper(self::$dal);
    }

    public static function tearDownAfterClass()
    {
        /// Objects destruction
        self::$dal = NULL;
        self::$commentMapper  = NULL;
        self::$placeMapper = NULL;
        self::$userMapper = NULL;
    }

    /**
     * @return int
     */
    private function insertNewPlace()
    {
        $place = new \hotspotMap\model\Place();
        $place->setName("Starbucks");
        $place->setLongitude(48.84951);
        $place->setLatitude(2.297911);
        $place->setSchedules("07:30 – 21:00");
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

        return $place->getPlaceId();
    }

    /**
     * @return int
     */
    private function insertNewUser()
    {
        $user = new \hotspotMap\model\User();
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");
        $user->setMailAddress("good@address.fr");
        self::$userMapper->persist($user);

        return $user->getUserId();
    }

    public function testInsertComment()
    {
        $placeId = $this->insertNewPlace();
        $userId = $this->insertNewUser();

        $comment = new \hotspotMap\model\Comment();
        /// Insertion with null content
        $comment->setPlaceId($placeId);
        $comment->setUserId($userId);
        $errors = self::$commentMapper->persist($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with userId and displayName (Not permitted)
        $comment->setContent("My content");
        $comment->setAuthorDisplayName("Batman");
        $comment->setUserId($userId);
        $errors = self::$commentMapper->persist($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with only displayName
        $comment->setUserId(null);
        $comment->setAuthorDisplayName("Batman");
        $errors = self::$commentMapper->persist($comment);

        $this->assertEmpty($errors);
        $this->assertNotNull($comment->getCommentId());
        ///

        /// Insertion with only userId
        $comment->setCommentId(null);
        $comment->setUserId($userId);
        $comment->setAuthorDisplayName(null);
        $errors = self::$commentMapper->persist($comment);

        $this->assertEmpty($errors);
        $this->assertNotNull($comment->getCommentId());
        ///
    }

    public function testUpdateComment()
    {
        $placeId = $this->insertNewPlace();
        /// Insertion
        $comment = new \hotspotMap\model\Comment();
        $comment->setContent("My content");
        $comment->setPlaceId($placeId);
        $comment->setAuthorDisplayName("Batman");
        self::$commentMapper->persist($comment);
        ///

        /// Bad update with bad
        $comment->setContent(null);
        $comment->setAuthorDisplayName(null);
        $errors = self::$commentMapper->persist($comment);
        $this->assertNotEmpty($errors);
        ///

        /// Update with good
        $comment->setContent("My content");
        $comment->setAuthorDisplayName("Batman");
        $errors = self::$commentMapper->persist($comment);

        $this->assertEmpty($errors);
        ///
    }

    public function testDeleteComment()
    {
        $placeId = $this->insertNewPlace();
        /// Insertion
        $comment = new \hotspotMap\model\Comment();
        $comment->setContent("My content");
        $comment->setPlaceId($placeId);
        $comment->setAuthorDisplayName("Batman");
        self::$commentMapper->persist($comment);
        ///

        /// Removing test with null id
        $commentId = $comment->getCommentId();
        $comment->setCommentId(null);
        $errors = self::$commentMapper->remove($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Removing test with null id
        $comment->setCommentId($commentId);
        $errors = self::$commentMapper->remove($comment);

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