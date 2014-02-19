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
     * @var \HotspotMap\dal\UserRepository
     */
    private $userRepository;

    /**
     * @var \HotspotMap\dal\PlaceRepository
     */
    private $placeRepository;

    /**
     * @param \HotspotMap\dal\ICommentMapper $commentMapper
     * @param \HotspotMap\dal\IFinder $finder
     */
    public function __construct(\HotspotMap\dal\ICommentMapper $commentMapper, \HotspotMap\dal\IFinder $finder)
    {
        $this->commentMapper = $commentMapper;
        $this->finder = $finder;
        $this->placeRepository = \HotspotMap\dal\DALFactory::getRepository("Place");
        $this->userRepository = \HotspotMap\dal\DALFactory::getRepository("User");
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
    private function createCommentFromData($commentData  = [])
    {
        ///`commentId`, `content`, `placeId`, `userId`, `authorDisplayName`
        $comment = new \Hotspotmap\model\Comment();
        $comment->setCommentId($commentData[0]);
        $comment->setContent($commentData[1]);
        $place = $this->placeRepository->findOneById(intval($commentData[2]));
        $comment->setPlace($place);
        if(null != $commentData[3])
        {
            $user = $this->userRepository->findOneById(intval($commentData[3]));
            $comment->setUser($user);
        }
        $this->placeRepository->findOneById($commentData[4]);
        return $comment;
    }
} 