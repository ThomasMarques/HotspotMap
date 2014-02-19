<?php

namespace HotspotMap\dal;

require_once "../../../src/hotspotMap/dal/DALFactory.php";
require_once "../../../src/hotspotMap/model/User.php";

class UserRepositoryUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserRepository
     */
    protected static $userRepository;

    /**
     * @var Connection
     */
    protected static $connexion;

    public static function setUpBeforeClass()
    {
        /// Objects construction
        self::$connexion = \HotspotMap\dal\DALFactory::getConnexion();
        self::$userRepository = \HotspotMap\dal\DALFactory::getRepository("User");
    }

    public static function tearDownAfterClass()
    {
        /// Objects destruction
        self::$userRepository = NULL;
        self::$connexion = NULL;
    }

    public function testInsertUser()
    {
        $user = new \hotspotMap\model\User();
        /// Insertion with null address
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");
        $errors = self::$userRepository->save($user);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with bad address
        $user->setMailAddress("bad@address");
        $errors = self::$userRepository->save($user);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with good parameters
        $user->setMailAddress("good@address.fr");
        $errors = self::$userRepository->save($user);

        $this->assertEmpty($errors);
        $this->assertNotNull($user->getUserId());
        ///

        /// Violated constraint unique on mailAddress
        $user = new \hotspotMap\model\User();
        $user->setMailAddress("good@address.fr");
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");
        $errors = self::$userRepository->save($user);

        $this->assertNotEmpty($errors);
        ///
    }

    public function testUpdateUser()
    {
        /// Insertion
        $user = new \hotspotMap\model\User();
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");
        $user->setMailAddress("good@address.fr");
        self::$userRepository->save($user);
        ///

        /// Bad update with bad mail
        $user->setMailAddress("bad@address");
        $errors = self::$userRepository->save($user);

        $this->assertNotEmpty($errors);
        ///

        /// Update with good parameters
        $user->setMailAddress("othergood@address.com");
        $errors = self::$userRepository->save($user);

        $this->assertEmpty($errors);
        ///
    }

    public function testDeleteUser()
    {
        /// Insertion
        $user = new \hotspotMap\model\User();
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");
        $user->setMailAddress("good@address.fr");
        self::$userRepository->save($user);
        ///

        /// Removing test with null id
        $userId = $user->getUserId();
        $user->setUserId(null);
        $errors = self::$userRepository->remove($user);

        $this->assertNotEmpty($errors);
        ///

        /// Removing test with null id
        $user->setUserId($userId);
        $errors = self::$userRepository->remove($user);

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