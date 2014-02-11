<?php

namespace test;

require_once "../src/HotspotMap/service/EntityMapper.php";
require_once "../src/HotspotMap/service/DataAccessLayer.php";
require_once "../src/HotspotMap/model/User.php";

class EntityMapperTest //extends \PHPUnit_Framework_TestCase
{
    protected static $dal;
    protected static $entityMapper;

    public static function setUpBeforeClass()
    {
        self::$dal = new \service\DataAccessLayer("mysql:host=localhost;dbname=hotspotmaptest", "root", "");
        self::$entityMapper  = new \service\EntityMapper(self::$dal);
    }

    public static function tearDownAfterClass()
    {
        self::$dal = NULL;
        self::$entityMapper = NULL;
    }

    public function testUser()
    {
        self::setUpBeforeClass();

        /// BEGIN TRANSACTION
        self::$dal->beginTransaction();

        /// Test Insert
        $user = new \model\User();
        $user->setPrivilege(0);
        $user->setDisplayName("Display Name");

        $errors = self::$entityMapper->persistUser($user);

        //$this->assertNotEmpty($errors);
        print "\$this->assertNotEmpty(\$errors) : " . (empty($errors)?"fail":"success") . "</br>";

        $user->setMailAddress("bad@address");

        $errors = self::$entityMapper->persistUser($user);

        //$this->assertNotEmpty($errors);
        print "\$this->assertNotEmpty(\$errors) : " . (empty($errors)?"fail":"success") . "</br>";

        $user->setMailAddress("good@address.fr");

        $errors = self::$entityMapper->persistUser($user);

        //$this->assertEmpty($errors);
        print "\$this->assertEmpty(\$errors) : " . (empty($errors)?"success":"fail") . "</br>";

        //$this->assertNotNull($user->getUserId())
        print "\$this->assertNotNull(\$user->getUserId()) : " . (null === $user->getUserId()?"fail":"success") . "</br>";


        /// Test Update
        $user->setMailAddress("othergood@address.com");

        $errors = self::$entityMapper->persistUser($user);

        //$this->assertEmpty($errors);
        print "\$this->assertEmpty(\$errors) : " . (empty($errors)?"success":"fail") . "</br>";

        $errors = self::$entityMapper->removeUser($user);

        //$this->assertEmpty($errors);
        print "\$this->assertEmpty(\$errors) : " . (empty($errors)?"success":"fail") . "</br>";

        /// ROLLBACK
        self::$dal->rollBack();

        self::tearDownAfterClass();
    }
} 