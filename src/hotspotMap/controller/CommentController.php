<?php

namespace HotspotMap\Controller;

use HotspotMap\dal\DALFactory;
use HotspotMap\dal\CommentRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use HotspotMap\model\Comment;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

class CommentController extends Controller
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function __construct()
    {
        $this->commentRepository = DALFactory::getRepository("Comment");
    }

    public function listAction (Request $request, Application $app, $page = 1)
    {
        $limit=20;

        $comments = $this->commentRepository->findAllByPage($page, $limit);
        $total = $this->commentRepository->countComments();

        $result = $app['collection-helper']->buildCollection($comments, 'comment_list', 'comments', $page, $limit, $total);

        return $app['renderer']->render($app, 200, $result);

    }

    public function getAction (Request $request, Application $app, $id)
    {

        $comment = $this->commentRepository->findOneById($id);

        if(null == $comment)
        {
            $errors[] = "No comment found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 200, $comment);
    }

    public function postAction (Request $request, Application $app)
    {
        $comment = $this->fillCommentWithRequestAttribute($request, new Comment());

        /// Not editable, only fix by us at creation.
        $comment->setPrivilege(0);

        $errors = $this->commentRepository->save($comment);

        if(!isEmpty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 201, $comment);
    }

    public function putAction (Request $request, Application $app, $id)
    {
        /// Checking rights
        /// Only Author, moderator and admin
        /// 401 if not connected
        /// 405 if connected but not allowed

        $comment = $this->commentRepository->findOneById($id);

        if(null == $comment)
        {
            $errors[] = "No comment found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        $comment = $this->fillCommentWithRequestAttribute($request, $comment);

        /// TODO -> TO THINK
        /// $comment->setValidate(false);

        $errors = $this->commentRepository->save($comment);

        if(!isEmpty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 204, null);
    }

    public function deleteAction (Request $request, Application $app, $id)
    {
        /// Checking rights
        /// Only Author, moderator and admin
        /// 401 if not connected
        /// 405 if connected but not allowed

        $comment = $this->commentRepository->findOneById($id);

        if(null == $comment)
        {
            $errors[] = "No comment found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        $errors = $this->commentRepository->remove($comment);

        if(!isEmpty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 204, null);
    }

    /**
     * @param Request $request
     * @param Comment $comment
     * @return Comment
     */
    private function fillCommentWithRequestAttribute(Request $request, Comment $comment)
    {
        $mailAddress = $request->get("mailAddress", null);
        if(null != $mailAddress)
            $comment->setMailAddress($mailAddress);

        $displayName = $request->get("displayName", null);
        if(null != $displayName)
            $comment->setDisplayName($displayName);

        return $comment;
    }
}