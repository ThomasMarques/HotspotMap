<?php

namespace HotspotMap\dal\MySqlImplementation;

require_once __DIR__ . "/../../model/Comment.php";
require_once __DIR__ . "/../ICommentMapper.php";

class MySqlCommentMapper extends \HotspotMap\dal\ICommentMapper
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
     * @param \Hotspotmap\model\Comment $comment
     * @return array
     */
    public function persist(\Hotspotmap\model\Comment $comment)
    {
        $errors = $this->checkAttribute($comment);

        if(empty($errors))
        {
            $parameters = [];
            $isNew = (null == $comment->getCommentId());
            if($isNew)
            {
                // Insert
                $query = <<<SQL
INSERT INTO comment
(commentId, content, placeId, userId, authorDisplayName)
VALUES (NULL, :content, :placeId, :userId, :authorDisplayName)
SQL;
            }
            else
            {
                // Update
                $query = <<<SQL
UPDATE Comment
SET content = :content,
placeId = :placeId,
userId = :userId,
authorDisplayName = :authorDisplayName
WHERE commentId = :commentId
SQL;
                $parameters["commentId"] = $comment->getCommentId();
            }

            /// Filling all parameters
            $parameters["content"] = $comment->getContent();
            $parameters["placeId"] = $comment->getPlace()->getPlaceId();
            $parameters["userId"] = null;
            if(null != $comment->getUser())
            {
                $parameters["userId"] = $comment->getUser()->getUserId();
            }
            $parameters["authorDisplayName"] = $comment->getAuthorDisplayName();
            ///

            $success = $this->connexion->executeQuery($query, $parameters);
            if($success)
            {
                if($isNew)
                {
                    $comment->setCommentId(intval($this->connexion->lastInsertId()));
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
     * @param \Hotspotmap\model\Comment $comment
     * @return array
     */
    public function remove(\Hotspotmap\model\Comment $comment)
    {
        $errors = [];

        if(null == $comment->getCommentId())
        {
            $errors["id"] = "Missing Id";
        }
        else
        {
            $parameters = [];
            $query = <<<SQL
DELETE FROM Comment
WHERE commentId = :commentId
SQL;
            $parameters["commentId"] = $comment->getCommentId();
            if(!$this->connexion->executeQuery($query, $parameters))
            {
                $errors = $this->connexion->errorInfo();
            }
        }
        return $errors;
    }
} 