<?php

namespace HotspotMap\dal;

abstract class IUserMapper
{
    /**
     * @param \Hotspotmap\model\User $user
     * @return array
     */
    abstract public function persist(\Hotspotmap\model\User $user);

    /**
     * @param \Hotspotmap\model\User $user
     * @return array
     */
    abstract public function remove(\Hotspotmap\model\User $user);

    /**
     * @param \Hotspotmap\model\User $user
     * @return array
     */
    protected function checkAttribute(\Hotspotmap\model\User $user)
    {
        $errors = [];

        if(!filter_var($user->getMailAddress(), FILTER_VALIDATE_EMAIL))
        {
            $errors["mailAddress"] = "mail incorrect";
        }

        return $errors;
    }
} 