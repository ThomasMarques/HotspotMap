<?php

namespace hotspotMap\model;


class Comment {

    /**
     * @var int
     */
    private $commentId;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $placeId;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $authorDisplayName;

    public function __construct()
    {
        $this->commentId = null;
        $this->content = null;
        $this->placeId = null;
        $this->userId = null;
        $this->authorDisplayName = null;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $commentId
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;
    }

    /**
     * @return int
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param int $placeId
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;
    }

    /**
     * @return int
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * @param string $authorDisplayName
     */
    public function setAuthorDisplayName($authorDisplayName)
    {
        $this->authorDisplayName = $authorDisplayName;
    }

    /**
     * @return string
     */
    public function getAuthorDisplayName()
    {
        return $this->authorDisplayName;
    }
}