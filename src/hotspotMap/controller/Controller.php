<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 09/03/14
 * Time: 19:54
 */

namespace HotspotMap\Controller;

use Silex\Application;


abstract class Controller {

    protected function renderErrors(Application $app, $errors = [])
    {
        return $app['renderer']->render($app, 400, $errors);
    }
} 