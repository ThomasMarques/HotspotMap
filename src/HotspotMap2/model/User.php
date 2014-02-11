<?php

namespace hotspotMap\model;

abstract class Privilege
{
    const user = 0;
    const moderator = 1;
    const Administrator = 2;
}

class User {

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $mailAddress;

    /**
     * @var int Privilege
     */
    private $privilege;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var array Comment
     */
    private $comments= [];

    public function __construct()
    {
        $this->userId = null;
        $this->mailAddress = null;
        $this->privilege = null;
        $this->displayName = null;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * @return enum Privilege (int)
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param enum Privilege (int) $privilege
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;
    }

    /**
    * @param string $mailAddress
    */
    public function setMailAddress($mailAddress)
    {
        $this->mailAddress = $mailAddress;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
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