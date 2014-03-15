<?php

namespace HotspotMap\model;

use Symfony\Component\Security\Core\User\UserInterface;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Hateoas\Relation("self", href = "expr('/users/' ~ object.getUserId())")
 */
class User implements UserInterface {

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
        private $mailAddress;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $roles = [];

    public function __construct()
    {
        $this->userId = null;
        $this->mailAddress = null;
        $this->privilege = null;
        $this->authorDisplayName = null;
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
     * @return int Privilege
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

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getUsername()
    {
        return $this->mailAddress;
    }

    public function eraseCredentials()
    {
        $this->setPassword(null);
        $this->setSalt(null);
    }
} 