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
            $isNew = null === $comment->getCommentId();
            if($isNew)
            {
                // Insert
                $query = <<<SQL
INSERT INTO comment
(commentId)
VALUES (NULL)
SQL;
            }
            else
            {
                // Update
                $query = <<<SQL
UPDATE Comment
SET mailAddress = :mailAddress,
WHERE userId = :userId
SQL;
                $parameters["commentId"] = $comment->getCommentId();
            }

            /// Filling all parameters
            $parameters[""] = htmlentities("");
            ///

            $success = $this->dal->executeQuery($query, $parameters);
            if($success)
            {
                if($isNew)
                {
                    $comment->setCommentId((int)$this->dal->lastInsertId());
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

        if(null === $comment->getCommentId())
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

        return $errors;
    }
} 