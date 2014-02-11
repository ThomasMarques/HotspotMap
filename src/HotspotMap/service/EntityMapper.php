<?php

namespace service;

require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../model/Place.php";
require_once __DIR__ . "/../model/Comment.php";

class EntityMapper {

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
     * @param \model\User $user
     * @return bool
     */
    public function persistUser(\model\User $user)
    {
        $errors = $this->checkUserAttribute($user);

        if(empty($errors))
        {
            $parameters = [];
            $isNew = null === $user->getUserId();
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

            $parameters["mailAddress"] = htmlentities($user->getMailAddress());
            $parameters["privilege"] = $user->getPrivilege();
            $parameters["displayName"] = htmlentities($user->getDisplayName());

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
     * @param \model\User $user
     * @return array
     */
    public function removeUser(\model\User $user)
    {
        $errors = [];

        if(null === $user->getUserId())
        {
            $errors["id"];
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
     * @param Place $place
     */
    public function persistPlace(Place $place)
    {

    }

    /**
     * @param Place $place
     */
    public function removePlace(Place $place)
    {

    }

    /**
     * @param Comment $comment
     */
    public function persistComment(Comment $comment)
    {

    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {

    }

    /**
     * @param \model\User $user
     * @return array
     */
    private function checkUserAttribute(\model\User $user)
    {
        $errors = [];

        if(!filter_var($user->getMailAddress(), FILTER_VALIDATE_EMAIL))
        {
            $errors["mailAddress"] = "mail incorrect";
        }

        return $errors;
    }
}