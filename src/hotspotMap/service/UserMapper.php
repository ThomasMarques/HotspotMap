<?php

namespace hotspotMap\service;

require_once __DIR__ . "/../model/User.php";

class UserMapper
{

    /**
     * @var DataAccessLayer
     */
    private $dal;

    /**
     * @param DataAccessLayer $dal
     */
    public function __construct(DataAccessLayer $dal)
    {
        $this->dal = $dal;
    }

    /**
     * @param \hotspotmap\model\User $user
     * @return array
     */
    public function persist(\hotspotmap\model\User $user)
    {
        $errors = $this->checkAttribute($user);

        if(empty($errors))
        {
            $parameters = [];
            $isNew = (null == $user->getUserId());
            if($isNew)
            {
                // Insert
                $query = <<<SQL
INSERT INTO User
(userId, mailAddress, privilege, displayName)
VALUES (NULL, :mailAddress, :privilege, :displayName)
SQL;
            }
            else
            {
                // Update
                $query = <<<SQL
UPDATE User
SET mailAddress = :mailAddress,
privilege = :privilege,
displayName = :displayName
WHERE userId = :userId
SQL;
                $parameters["userId"] = $user->getUserId();
            }

            /// Filling all parameters
            $parameters["mailAddress"] = $user->getMailAddress();
            $parameters["privilege"] = $user->getPrivilege();
            $parameters["displayName"] = $user->getDisplayName();
            ///

            $success = $this->dal->executeQuery($query, $parameters);
            if($success)
            {
                if($isNew)
                {
                    $user->setUserId((int)$this->dal->lastInsertId());
                }
            }
            else
            {
                $errors = $this->dal->errorInfo();
            }
        }

        return $errors;
    }

    /**
     * @param \hotspotmap\model\User $user
     * @return array
     */
    public function remove(\hotspotmap\model\User $user)
    {
        $errors = [];

        if(null == $user->getUserId())
        {
            $errors["id"] = "Missing Id";
        }
        else
        {
            $parameters = [];
            $query = <<<SQL
DELETE FROM User
WHERE userId = :userId
SQL;
            $parameters["userId"] = $user->getUserId();
            if(!$this->dal->executeQuery($query, $parameters))
            {
                $errors = $this->dal->errorInfo();
            }
        }
        return $errors;
    }

    /**
     * @param \hotspotmap\model\User $user
     * @return array
     */
    private function checkAttribute(\hotspotmap\model\User $user)
    {
        $errors = [];

        if(!filter_var($user->getMailAddress(), FILTER_VALIDATE_EMAIL))
        {
            $errors["mailAddress"] = "mail incorrect";
        }

        return $errors;
    }
}