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
     * @param int $id
     * @return \Hotspotmap\model\User
     */
    public function findOneById($id)
    {
        $data = $this->finder->select(array("mailAddress", "privilege", "displayName"))
            ->from(array("user"))
            ->where("userId = :userId", ["userId" => $id])
            ->getResults();

        $user = null;
        if(sizeof($data) == 1)
        {
            $user = new \Hotspotmap\model\User();
            $user->setUserId($id);
            $user->setMailAddress($data[0][0]);
            $user->setPrivilege(intval($data[0][1]));
            $user->setDisplayName($data[0][2]);
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
} 