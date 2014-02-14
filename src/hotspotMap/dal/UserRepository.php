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
     * @return \hotspotmap\model\User
     */
    public function findOneById($id)
    {
    }

    /**
     * @param \hotspotmap\model\User $user
     * @return bool
     */
    public function save(\hotspotmap\model\User $user)
    {
        $this->userMapper->persist($user);
    }

    /**
     * @param \hotspotmap\model\User $user
     * @return bool
     */
    public function remove(\hotspotmap\model\User $user)
    {
        $this->userMapper->remove($user);
    }
} 