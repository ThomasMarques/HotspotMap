<?php
/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 09/03/14
 * Time: 13:37
 */

namespace HotspotMap\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GeolocController {

    public function findAddressAction (Request $request, Application $app, $address = '')
    {

        $geocoder = $app['geocoder'];
        $result = $geocoder->geocode($address);

        return $app['renderer']->render($app, 200, $result);

    }

    public function findCoordinatesAction (Request $request, Application $app, $lat = '', $lon = '')
    {

        $geocoder = $app['geocoder'];
        $result = $geocoder->reverse(str_replace(',','.',$lat), str_replace(',','.',$lon));

        return $app['renderer']->render($app, 200, $result);

    }

} 