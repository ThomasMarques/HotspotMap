<?php

namespace hotspotMap\service;

require_once "../../../src/hotspotMap/service/CommentMapper.php";
require_once "../../../src/HotspotMap/service/DataAccessLayer.php";
require_once "../../../src/HotspotMap/model/Comment.php";

class CommentMapperUnitTest extends \PHPUnit_Framework_TestCase
{
    protected static $dal;
    protected static $commentMapper;

    public static function setUpBeforeClass()
    {
        /// Objects construction
        /// Connexion to the database for tests
        self::$dal = new DataAccessLayer("mysql:host=localhost;dbname=hotspotmaptest", "root", "");
        self::$commentMapper  = new CommentMapper(self::$dal);
    }

    public static function tearDownAfterClass()
    {
        /// Objects destruction
        self::$dal = NULL;
        self::$commentMapper = NULL;
    }

    public function testInsertComment()
    {
        $comment = new \hotspotMap\model\Comment();
        /// Insertion with null...

        $errors = self::$commentMapper->persist($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with bad...
        $errors = self::$commentMapper->persist($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Insertion with good parameters...
        $errors = self::$commentMapper->persist($comment);

        $this->assertEmpty($errors);
        $this->assertNotNull($comment->getCommentId());
        ///

        /// Violated constraint ...
        $comment = new \hotspotMap\model\Comment();

        $errors = self::$userMapper->persist($comment);

        $this->assertNotEmpty($errors);
        ///
    }

    public function testUpdateComment()
    {
        /// Insertion
        $comment = new \hotspotMap\model\Comment();
        self::$commentMapper->persist($comment);
        ///

        /// Bad update with bad...
        $errors = self::$commentMapper->persist($comment);

        $this->assertNotEmpty($errors);
        ///

        /// Update with good parameters...
        $errors = self::$commentMapper->persist($comment);

        $this->assertEmpty($errors);
        ///
    }

    public function testDeleteComment()
    {
        /// Insertion
        $comment = new \hotspotMap\model\Comment();
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