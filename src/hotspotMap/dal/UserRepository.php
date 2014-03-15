<?php

namespace HotspotMap\dal;

require_once "IUserMapper.php";
require_once "IFinder.php";

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use HotspotMap\model\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserRepository implements UserProviderInterface
{
    /**
     * @var \HotspotMap\dal\IUserMapper
     */
    private $userMapper;

    /**
     * @var \HotspotMap\dal\IFinder
     */
    private $finder;

    /**
     * @param \HotspotMap\dal\IUserMapper $userMapper
     * @param \HotspotMap\dal\IFinder $finder
     */
    public function __construct(\HotspotMap\dal\IUserMapper $userMapper, \HotspotMap\dal\IFinder $finder)
    {
        $this->userMapper = $userMapper;
        $this->finder = $finder;
    }

    /**
     * @return int
     */
    public function countUsers()
    {
        $data = $this->finder->select(array("count(*)"))
            ->from(array("User"))
            ->getResults();

        return intval($data[0][0]);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array of User
     */
    public function findAllByPage($page, $limit)
    {
        $data = $this->finder->select(array("*"))
            ->from(array("User"))
            ->limit(($page-1) * $limit, $limit)
            ->getResults();

        $users = [];
        for( $i = 0 ; $i < sizeof($data) ; ++$i )
        {
            $user = $this->createUserFromData($data[$i]);
            $users[] = $user;
        }
        return $users;
    }

    /**
     * @param int $id
     * @return User
     */
    public function findOneById($id)
    {
        $data = $this->finder->select(array("*"))
            ->from(array("user"))
            ->where("userId = :userId", ["userId" => $id])
            ->getResults();

        $user = null;
        if(sizeof($data) == 1)
        {
            $user = $this->createUserFromData($data[0]);
        }
        return $user;
    }

    public function findByMailAddress($mailAddress)
    {
        $data = $this->finder->select(array("*"))
            ->from(array("user"))
            ->where("mailAddress = :mailAddress", ["mailAddress" => $mailAddress])
            ->getResults();

        $user = null;
        if(sizeof($data) == 1)
        {
            $user = $this->createUserFromData($data[0]);
        }
        return $user;
    }

    /**
     * @param User $user
     * @return array
     */
    public function save(User $user)
    {
        return $this->userMapper->persist($user);
    }

    /**
     * @param User $user
     * @return array
     */
    public function remove(User $user)
    {
        return $this->userMapper->remove($user);
    }

    /// UserProviderInterface
    public function loadUserByUsername($mailAddress)
    {
        $user = $this->findByMailAddress($mailAddress);

        if($user == null)
            throw new UsernameNotFoundException(sprintf('Mail address "%s" does not exist.', $mailAddress));

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'HotspotMap\model\User';
    }

    /**
     * @param array $userData
     * @return User
     */
    private function createUserFromData($userData  = [])
    {
        $user = new User();
        $user->setUserId($userData[0]);
        $user->setMailAddress($userData[1]);
        $user->setDisplayName($userData[2]);
        $user->setPassword($userData[3]);
        $user->setSalt($userData[4]);
        $user->setRoles(explode(",", $userData[5]));

        return $user;
    }
} 