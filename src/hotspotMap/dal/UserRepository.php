<?php

namespace HotspotMap\dal;

require_once "IUserMapper.php";
require_once "IFinder.php";
require_once __DIR__ . "/../model/User.php";

class UserRepository
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

        return intval($data[0]);
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
            ->limit(($page-1) * $limit, $page * $limit)
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
     * @return \Hotspotmap\model\User
     */
    public function findOneById($id)
    {
        $data = $this->finder->select(array("userId", "mailAddress", "privilege", "displayName"))
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

    /**
     * @param \Hotspotmap\model\User $user
     * @return array
     */
    public function save(\Hotspotmap\model\User $user)
    {
        return $this->userMapper->persist($user);
    }

    /**
     * @param \Hotspotmap\model\User $user
     * @return array
     */
    public function remove(\Hotspotmap\model\User $user)
    {
        return $this->userMapper->remove($user);
    }

    /**
     * @param array $userData
     * @return \Hotspotmap\model\User
     */
    private function createUserFromData($userData  = [])
    {
        $user = new \Hotspotmap\model\User();
        $user->setUserId($userData[0]);
        $user->setMailAddress($userData[1]);
        $user->setPrivilege(intval($userData[2]));
        $user->setDisplayName($userData[3]);
        return $user;
    }
} 