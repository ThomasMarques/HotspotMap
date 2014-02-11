<?php

namespace model;


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
     * @var array Comment
     */
    private $comments= [];

    public function __construct()
    {

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
     * @return array Comment
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }

    /**
     * @param array $comments
     */
    public function addComments($comments = [])
    {
        foreach ($this->parameters as $value)
        {
            $this->addComment($value);
        }
    }

    public function clearComments()
    {
        unset($this->comments);
    }
}