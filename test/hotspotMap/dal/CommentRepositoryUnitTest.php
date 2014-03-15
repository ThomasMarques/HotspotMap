<?php

namespace HotspotMap\dal;

require_once "../../../src/hotspotMap/dal/DALFactory.php";
require_once "../../../src/hotspotMap/model/Comment.php";
require_once "../../../src/hotspotMap/model/User.php";
require_once "../../../src/hotspotMap/model/Place.php";

class CommentRepositoryUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connexion
     */
    protected static $connexion;

    /**
     * @var PlaceRepository
     */
    protected static $placeRepository;
    /**
     * @var UserRepository
     */
    protected static $userRepository;
    /**
     * @var CommentRepository
     */
    protected static $commentRepository;

    public static function setUpBeforeClass()
    {
        /// Objects construction
        /// Connexion to the database for tests
        self::$connexion = \HotspotMap\dal\DALFactory::getConnexion();
        self::$placeRepository = \HotspotMap\dal\DALFactory::getRepository("Place");
        self::$userRepository = \HotspotMap\dal\DALFactory::getRepository("User");
        self::$commentRepository = \HotspotMap\dal\DALFactory::getRepository("Comment");
    }

    public static function tearDownAfterClass()
    {
        /// Objects destruction
        self::$placeRepository = NULL;
        self::$userRepository = NULL;
        self::$commentRepository = NULL;
        self::$connexion = NULL;
    }

    /**
     * @return \HotspotMap\model\Place
     */
    private function insertNewPlace()
    {
        $place = new \HotspotMap\model\Place();
        $place->setName("Starbucks");
        $place->setLongitude(48.84951);
        $place->setLatitude(2.297911);
        $place->setSchedules("07:30 – 21:00");
        $place->setDescription("Good Starbuks with Wifi");
        $place->setHotspotType(0);
        $place->setCoffee(true);
        $place->setInternetAccess(true);
        $place->setPlacesNumber(100);
        $place->setComfort(4);
        $place->setFrequenting(4);
        $place->setSubmissionDate(new \DateTime());
        $place->setVisitNumber(0);
        $place->setValidate(0);
        self::$placeRepository->save($place);

        return $place;
    }

    /**
     * @return \HotspotMap\model\User
     */
    private function insertNewUser()
    {
        $user = new \HotspotMap\model\User();
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");
        $user->setMailAddress("good@address.fr");
        self::$userRepository->save($user);

        return $user;
    }

    public function testInsertComment()
    {
        $place = $this->insertNewPlace();
        $user = $this->insertNewUser();

        $comment = new \HotspotMap\model\Comment();
        /// Insertion with null content
        $comment->setPlace($place);
        $comment->setUser($user);
        $errors = self::$commentRepository->save($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with userId and displayName (Not permitted)
        $comment->setContent("My content");
        $comment->setAuthorDisplayName("Batman");
        $comment->setUser($user);
        $errors = self::$commentRepository->save($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with only displayName
        $comment->setUser(null);
        $comment->setAuthorDisplayName("Batman");
        $errors = self::$commentRepository->save($comment);

        $this->assertEmpty($errors);
        $this->assertNotNull($comment->getCommentId());
        ///

        /// Insertion with only userId
        $comment->setCommentId(null);
        $comment->setUser($user);
        $comment->setAuthorDisplayName(null);
        $errors = self::$commentRepository->save($comment);

        $this->assertEmpty($errors);
        $this->assertNotNull($comment->getCommentId());
        ///

        /// Verification that the comment has been added to the place.
        $comments = self::$commentRepository->findAllByPlaceId($place->getPlaceId());
        $this->assertNotEmpty($comments);
        ///
    }

    public function testUpdateComment()
    {
        $place = $this->insertNewPlace();
        /// Insertion
        $comment = new \HotspotMap\model\Comment();
        $comment->setContent("My content");
        $comment->setPlace($place);
        $comment->setAuthorDisplayName("Batman");
        self::$commentRepository->save($comment);
        ///

        /// Bad update with bad
        $comment->setContent(null);
        $comment->setAuthorDisplayName(null);
        $errors = self::$commentRepository->save($comment);
        $this->assertNotEmpty($errors);
        ///

        /// Update with good
        $comment->setContent("My content");
        $comment->setAuthorDisplayName("Batman");
        $errors = self::$commentRepository->save($comment);

        $this->assertEmpty($errors);
        ///
    }

    public function testDeleteComment()
    {
        $place = $this->insertNewPlace();
        /// Insertion
        $comment = new \HotspotMap\model\Comment();
        $comment->setContent("My content");
        $comment->setPlace($place);
        $comment->setAuthorDisplayName("Batman");
        self::$commentRepository->save($comment);
        ///

        /// Removing test with null id
        $commentId = $comment->getCommentId();
        $comment->setCommentId(null);
        $errors = self::$commentRepository->remove($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Removing test with null id
        $comment->setCommentId($commentId);
        $errors = self::$commentRepository->remove($comment);

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