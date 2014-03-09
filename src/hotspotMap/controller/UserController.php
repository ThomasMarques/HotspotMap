<?php

namespace HotspotMap\Controller;

use HotspotMap\dal\DALFactory;
use HotspotMap\dal\UserRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use HotspotMap\model\User;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = DALFactory::getRepository("User");
    }

    public function listAction (Request $request, Application $app, $page = 1)
    {
        $limit=20;

        $users = $this->userRepository->findAllByPage($page, $limit);
        $total = $this->userRepository->countUsers();

        $result = $app['collection-helper']->buildCollection($users, 'user_list', 'users', $page, $limit, $total);

        return $app['renderer']->render($app, 200, $result);

    }

    public function getAction (Request $request, Application $app, $id)
    {

        $user = $this->userRepository->findOneById($id);

        if(null == $user)
        {
            $errors[] = "No user found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 200, $user);
    }

    public function postAction (Request $request, Application $app)
    {
        $user = $this->fillUserWithRequestAttribute($request, new User());

        /// Not editable, only fix by us at creation.
        $user->setPrivilege(0);

        $errors = $this->userRepository->save($user);

        if(!isEmpty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 201, $user);
    }

    public function putAction (Request $request, Application $app, $id)
    {
        /// Checking rights
        /// Only Author, moderator and admin
        /// 401 if not connected
        /// 405 if connected but not allowed

        $user = $this->userRepository->findOneById($id);

        if(null == $user)
        {
            $errors[] = "No user found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        $user = $this->fillUserWithRequestAttribute($request, $user);

        /// TODO -> TO THINK
        /// $user->setValidate(false);

        $errors = $this->userRepository->save($user);

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

        $user = $this->userRepository->findOneById($id);

        if(null == $user)
        {
            $errors[] = "No user found with id : " . $id;
            return $this->renderErrors($app, $errors);
        }

        $errors = $this->userRepository->remove($user);

        if(!isEmpty($errors))
        {
            return $this->renderErrors($app, $errors);
        }

        return $app['renderer']->render($app, 204, null);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return User
     */
    private function fillUserWithRequestAttribute(Request $request, User $user)
    {
        $mailAddress = $request->get("mailAddress", null);
        if(null != $mailAddress)
            $user->setMailAddress($mailAddress);

        $displayName = $request->get("displayName", null);
        if(null != $displayName)
            $user->setDisplayName($displayName);

        return $user;
    }
}