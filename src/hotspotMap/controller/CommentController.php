<?php

namespace HotspotMap\Controller;

use HotspotMap\dal\DALFactory;
use HotspotMap\dal\CommentRepository;
use HotspotMap\dal\UserRepository;
use HotspotMap\dal\PlaceRepository;
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

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    public function __construct()
    {
        $this->commentRepository = DALFactory::getRepository("Comment");
        $this->userRepository = DALFactory::getRepository("User");
        $this->placeRepository = DALFactory::getRepository("Place");
    }

    public function listAction (Request $request, Application $app)
    {
        $argPage = $request->get("page", null);
        $page = (null != $argPage) ? intval($argPage) : 1;

        $argLimit = $request->get("limit", null);
        $limit = (null != $argLimit) ? intval($argLimit) : 20;

        $comments = $this->commentRepository->findAllByPage($page, $limit);
        $total = ceil($this->commentRepository->countComments() / $limit);

        $result = $app['collection-helper']->buildCollection($comments, 'comment_list', 'comments', $page, $limit, $total);

        return $app['renderer']->render($app, 200, $result);

    }

    public function listForPlaceAction (Request $request, Application $app, $place, $limit = 20)
    {

        $comments = $this->commentRepository->findAllByPlaceId($place);
        $total = ceil($this->commentRepository->countComments() / $limit);

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

        $errors = $this->commentRepository->save($comment);

        if(!empty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 201, $comment);
    }

    public function putAction (Request $request, Application $app, $id)
    {
        $comment = $this->commentRepository->findOneById($id);

        if(null == $comment)
        {
            $errors[] = "No comment found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        $comment = $this->fillCommentWithRequestAttribute($request, $comment);

        $errors = $this->commentRepository->save($comment);

        if(!empty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 204, null);
    }

    public function deleteAction (Request $request, Application $app, $id)
    {
        $comment = $this->commentRepository->findOneById($id);

        if(null == $comment)
        {
            $errors[] = "No comment found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        $errors = $this->commentRepository->remove($comment);

        if(!empty($errors))
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
        $content = $request->get("content", null);
        if(null != $content)
            $comment->setContent($content);

        $authorDisplayName = $request->get("authorDisplayName", null);
        if(null != $authorDisplayName)
            $comment->setAuthorDisplayName($authorDisplayName);

        $placeId = $request->get("placeId", null);
        if(null != $placeId)
        {
            $comment->setPlace($this->placeRepository->findOneById(intval($placeId)));
        }

        $authorId = $request->get("authorId", null);
        if(null != $authorId)
        {
            $comment->setUser($this->userRepository->findOneById(intval($authorId)));
        }

        return $comment;
    }
}