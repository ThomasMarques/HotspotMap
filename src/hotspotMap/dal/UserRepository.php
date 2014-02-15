<?php

namespace HotspotMap\dal;

require_once "IUserMapper.php";
require_once "IFinder.php";

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
     * @return \HotspotMap\model\User
     */
    public function findOneById($id)
    {
        $this->finder->select(array("mailAddress", "privilege", "displayName"))
            ->from(array("user"))
            ->where("user.id = :userId", ["userId" => $id])
            ->getResults();
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