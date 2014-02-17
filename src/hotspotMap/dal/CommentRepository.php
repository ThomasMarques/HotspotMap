<?php

namespace HotspotMap\dal;

require_once "ICommentMapper.php";
require_once "IFinder.php";

class CommentRepository
{
    /**
     * @var \HotspotMap\dal\ICommentMapper
     */
    private $commentMapper;

    /**
     * @var \HotspotMap\dal\IFinder
     */
    private $finder;

    /**
     * @param \HotspotMap\dal\ICommentMapper $commentMapper
     * @param \HotspotMap\dal\IFinder $finder
     */
    public function __construct(\HotspotMap\dal\ICommentMapper $commentMapper, \HotspotMap\dal\IFinder $finder)
    {
        $this->commentMapper = $commentMapper;
        $this->finder = $finder;
    }

    /**
     * @param int $id
     * @return \Hotspotmap\model\Comment
     */
    public function findOneById($id)
    {
        $data = $this->finder->select(array("*"))
            ->from(array("Comment"))
            ->where("commentId = :CommentId", ["CommentId" => $id])
            ->getResults();

        $comment = null;
        if(sizeof($data) == 1)
        {
            $comment = $this->createCommentFromData($data[0]);
        }
        return $comment;
    }

    /**
     * @param \Hotspotmap\model\Comment $comment
     * @return bool
     */
    public function save(\Hotspotmap\model\Comment $comment)
    {
        return $this->commentMapper->persist($comment);
    }

    /**
     * @param \Hotspotmap\model\Comment $comment
     * @return bool
     */
    public function remove(\Hotspotmap\model\Comment $comment)
    {
        return $this->commentMapper->remove($comment);
    }

    /**
     * @param array $commentData
     * @return \Hotspotmap\model\Comment
     */
    private function createUserFromData($commentData  = [])
    {
        $comment = new \Hotspotmap\model\Comment();
        $comment->setCommentId($commentData[0]);
        $comment->setContent($commentData[1]);
        return $comment;
    }
} 