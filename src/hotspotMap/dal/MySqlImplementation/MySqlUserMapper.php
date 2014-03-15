<?php

namespace HotspotMap\dal\MySqlImplementation;

require_once __DIR__ . "/../../model/User.php";
require_once __DIR__ . "/../IUserMapper.php";

class MySqlUserMapper extends \HotspotMap\dal\IUserMapper
{
    /**
     * @var Connexion
     */
    private $connexion;

    /**
     * @param Connexion $connexion
     */
    public function __construct(Connexion $connexion)
    {
        $this->connexion = $connexion;
    }

    /**
     * @param \Hotspotmap\model\User $user
     * @return array
     */
    public function persist(\Hotspotmap\model\User $user)
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
(userId, mailAddress, displayName, password, salt, roles)
VALUES (NULL, :mailAddress, :displayName, :password, :salt, :roles)
SQL;
            }
            else
            {
                // Update
                $query = <<<SQL
UPDATE User
SET mailAddress = :mailAddress,
displayName = :displayName,
password = :password,
salt = :salt,
roles = :roles
WHERE userId = :userId
SQL;
                $parameters["userId"] = $user->getUserId();
            }

            /// Filling all parameters
            $parameters["mailAddress"] = $user->getMailAddress();
            $parameters["displayName"] = $user->getDisplayName();
            $parameters["password"] = $user->getDisplayName();
            $parameters["salt"] = $user->getSalt();
            $parameters["roles"] = implode(",", $user->getRoles());
            ///

            $success = $this->connexion->executeQuery($query, $parameters);
            if($success)
            {
                if($isNew)
                {
                    $user->setUserId((int)$this->connexion->lastInsertId());
                }
            }
            else
            {
                $errors = $this->connexion->errorInfo();
            }
        }

        return $errors;
    }

    /**
     * @param \Hotspotmap\model\User $user
     * @return array
     */
    public function remove(\Hotspotmap\model\User $user)
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
            if(!$this->connexion->executeQuery($query, $parameters))
            {
                $errors = $this->connexion->errorInfo();
            }
        }
        return $errors;
    }
}