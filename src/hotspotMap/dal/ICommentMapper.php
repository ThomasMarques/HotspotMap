<?php

namespace HotspotMap\dal;


abstract class ICommentMapper
{

    /**
     * @param \hotspotmap\model\Comment $comment
     * @return array
     */
    abstract public function persist(\hotspotmap\model\Comment $comment);

    /**
     * @param \hotspotmap\model\Comment $comment
     * @return array
     */
    abstract public function remove(\hotspotmap\model\Comment $comment);

    /**
     * @param \hotspotmap\model\Comment $comment
     * @return array
     */
    protected function checkAttribute(\hotspotmap\model\Comment $comment)
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
        if(null == $comment->getUserId() && null == $comment->getAuthorDisplayName())
        {
            $errors["userId"] = "The attribute userId cannot be null if the display name is null.";
            $errors["displayName"] = "The attribute displayName cannot be null if the user id is null.";
        }
        if(null != $comment->getUserId() && null != $comment->getAuthorDisplayName())
        {
            $errors["userId"] = "The attribute userId must be null if the display name is not null.";
            $errors["displayName"] = "The attribute displayName must be null if the user id is not null.";
        }

        return $errors;
    }
} 