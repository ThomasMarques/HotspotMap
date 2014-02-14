<?php

namespace HotspotMap\dal\MySqlImplementation;

require_once __DIR__ . "/../../model/Comment.php";
require_once __DIR__ . "/../ICommentMapper.php";

class MySqlCommentMapper extends \HotspotMap\dal\ICommentMapper
{

    /**
     * @var Connexion
     */
    private $dal;

    /**
     * @param Connexion $dal
     */
    public function __construct(Connexion $dal)
    {
        $this->dal = $dal;
    }

    /**
     * @param \hotspotmap\model\Comment $comment
     * @return array
     */
    public function persist(\hotspotmap\model\Comment $comment)
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
            $parameters["placeId"] = $comment->getPlaceId();
            $parameters["userId"] = $comment->getUserId();
            $parameters["authorDisplayName"] = $comment->getAuthorDisplayName();
            ///

            $success = $this->dal->executeQuery($query, $parameters);
            if($success)
            {
                if($isNew)
                {
                    $comment->setCommentId(intval($this->dal->lastInsertId()));
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
     * @param \hotspotmap\model\Comment $comment
     * @return array
     */
    public function remove(\hotspotmap\model\Comment $comment)
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
            if(!$this->dal->executeQuery($query, $parameters))
            {
                $errors = $this->dal->errorInfo();
            }
        }
        return $errors;
    }
} 