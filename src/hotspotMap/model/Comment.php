<?php

namespace HotspotMap\model;


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
    private $place;

    /**
     * @var int
     */
    private $user;

    /**
     * @var string
     */
    private $authorDisplayName;

    public function __construct()
    {
        $this->commentId = null;
        $this->content = null;
        $this->place = null;
        $this->user = null;
        $this->authorDisplayName = null;
    }

    /**
     * @param \HotspotMap\model\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \HotspotMap\model\User
     */
    public function getUser()
    {
        return $this->user;
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
     * @param \HotspotMap\model\Place $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @return \HotspotMap\model\Place
     */
    public function getPlace()
    {
        return $this->place;
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