<?php

namespace hotspotMap\service;

require_once "../../../src/hotspotMap/service/UserMapper.php";
require_once "../../../src/hotspotMap/service/DataAccessLayer.php";
require_once "../../../src/hotspotMap/model/User.php";

class UserMapperUnitTest extends \PHPUnit_Framework_TestCase
{
    protected static $dal;
    protected static $userMapper;

    public static function setUpBeforeClass()
    {
        /// Objects construction
        /// Connexion to the database for tests
        self::$dal = new DataAccessLayer("mysql:host=localhost;dbname=hotspotmaptest", "root", "");
        self::$userMapper  = new UserMapper(self::$dal);
    }

    public static function tearDownAfterClass()
    {
        /// Objects destruction
        self::$dal = NULL;
        self::$userMapper = NULL;
    }

    public function testInsertUser()
    {
        $user = new \hotspotMap\model\User();
        /// Insertion with null address
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");
        $errors = self::$userMapper->persist($user);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with bad address
        $user->setMailAddress("bad@address");
        $errors = self::$userMapper->persist($user);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with good parameters
        $user->setMailAddress("good@address.fr");
        $errors = self::$userMapper->persist($user);

        $this->assertEmpty($errors);
        $this->assertNotNull($user->getUserId());
        ///

        /// Violated constraint unique on mailAddress
        $user = new \hotspotMap\model\User();
        $user->setMailAddress("good@address.fr");
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");
        $errors = self::$userMapper->persist($user);

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
        self::$userMapper->persist($user);
        ///

        /// Bad update with bad mail
        $user->setMailAddress("bad@address");
        $errors = self::$userMapper->persist($user);

        $this->assertNotEmpty($errors);
        ///

        /// Update with good parameters
        $user->setMailAddress("othergood@address.com");
        $errors = self::$userMapper->persist($user);

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
        self::$userMapper->persist($user);
        ///

        /// Removing test with null id
        $userId = $user->getUserId();
        $user->setUserId(null);
        $errors = self::$userMapper->remove($user);

        $this->assertNotEmpty($errors);
        ///

        /// Removing test with null id
        $user->setUserId($userId);
        $errors = self::$userMapper->remove($user);

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