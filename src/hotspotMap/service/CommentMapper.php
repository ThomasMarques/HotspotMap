<?php

namespace hotspotMap\service;

require_once __DIR__ . "/../model/Comment.php";

class CommentMapper
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
(commentId, content, placeId, userId, displayName)
VALUES (NULL, :content, :placeId, :userId, :displayName)
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
displayName = :displayName
WHERE commentId = :commentId
SQL;
                $parameters["commentId"] = $comment->getCommentId();
            }

            /// Filling all parameters
            $parameters["content"] = $comment->getContent();
            $parameters["placeId"] = $comment->getPlaceId();
            $parameters["userId"] = $comment->getUserId();
            $parameters["displayName"] = $comment->getDisplayName();
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

    /**
     * @param \hotspotmap\model\Comment $comment
     * @return array
     */
    private function checkAttribute(\hotspotmap\model\Comment $comment)
    {
        $errors = [];

        if(null == $comment->getContent() || strlen($comment->getContent()) < 1)
        {
            $errors["name"] = "The attribute content cannot be null or empty.";
        }
        if(null == $comment->getPlaceId())
        {
            $errors["placeId"] = "The attribute placeId cannot be null.";
        }
        if(null == $comment->getUserId() && null == $comment->getDisplayName())
        {
            $errors["userId"] = "The attribute userId cannot be null if the display name is null.";
            $errors["displayName"] = "The attribute displayName cannot be null if the user id is null.";
        }
        if(null != $comment->getUserId() && null != $comment->getDisplayName())
        {
            $errors["userId"] = "The attribute userId must be null if the display name is not null.";
            $errors["displayName"] = "The attribute displayName must be null if the user id is not null.";
        }

        return $errors;
    }
} 