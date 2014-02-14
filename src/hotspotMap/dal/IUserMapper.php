<?php

namespace HotspotMap\dal;


abstract class IUserMapper
{
    /**
     * @param \hotspotmap\model\User $user
     * @return array
     */
    abstract public function persist(\hotspotmap\model\User $user);

    /**
     * @param \hotspotmap\model\User $user
     * @return array
     */
    abstract public function remove(\hotspotmap\model\User $user);

    /**
     * @param \hotspotmap\model\User $user
     * @return array
     */
    protected function checkAttribute(\hotspotmap\model\User $user)
    {
        $errors = [];

        if(!filter_var($user->getMailAddress(), FILTER_VALIDATE_EMAIL))
        {
            $errors["mailAddress"] = "mail incorrect";
        }

        return $errors;
    }
} 