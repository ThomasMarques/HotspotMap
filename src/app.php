<?php

require_once __DIR__.'/../vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

use Symfony\Component\HttpFoundation\Request;
use HotspotMap\View\Renderer\RendererFactory;
use Silex\Provider\TwigServiceProvider;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use Hateoas\UrlGenerator\CallableUrlGenerator;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Geocoder\Provider\GeocoderServiceProvider;

$app = new Silex\Application();
$app['mime-types'] = [ 'text/html', 'application/xml', 'application/json' ];
$app['debug'] = true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

/**
 * Renderer
 */
$app['renderer'] = $app->share(function () use ($app) {
    return new \HotspotMap\View\ViewRenderer($app, $_SERVER, new RendererFactory());
});

$app['collection-helper'] = $app->share(function () use ($app) {
    return new \HotspotMap\Helper\CollectionHelper();
});

$app['serializer'] = $app->share(function() use ($app) {
    return HateoasBuilder::create()
        ->setUrlGenerator(null, new SymfonyUrlGenerator($app['url_generator']))
        ->build();
});

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/HotspotMap/View/Templates/',
));

$app->register(new GeocoderServiceProvider());

$app['security.firewalls'] = array(
    'login' => array(
        'pattern' => '^/login$',
        'anonymous' => true,
    ),
    'secured' => array(
        'anonymous' => true,
        'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
        'logout' => array('logout_path' => '/logout'),
        'users' => $app->share(function () use ($app) {
                return HotspotMap\dal\DALFactory::getRepository('User');
            }),
    )
);

$app['security.access_rules'] = array(
    array('^/admin', 'ROLE_ADMIN'),
);

$app['security.encoder.digest'] = $app->share(function ($app) {
    return new MessageDigestPasswordEncoder('sha512', true, 5000);
});

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => $app['security.firewalls'],
    'security.access_rules' => $app['security.access_rules']
));

/// Login page
$app->get('/login', function (Request $request) use ($app) {
    return $app['twig']->render('users/login.html', array(
        'error'         => $app['security.last_error']($request)
    ));
});

/*
 * Places Controller
 */
$app->get('/', 'HotspotMap\Controller\PlaceController::listAction')
    ->bind('home');
$app->get('/places', 'HotspotMap\Controller\PlaceController::listAction')
    ->bind('place_list');
$app->get('/places/{id}', 'HotspotMap\Controller\PlaceController::getAction')
    ->assert('id', '\d+')
    ->bind('place_get');
$app->get('/places/{address}', 'HotspotMap\Controller\PlaceController::addressAction')
    ->bind('place_address');
$app->get('/places/{address}/{distance}', 'HotspotMap\Controller\PlaceController::nearestAddressAction')
    ->assert('distance', '\d+')
    ->bind('place_nearestAddress');
$app->get('/places/{lat}/{lon}', 'HotspotMap\Controller\PlaceController::coordinatesAction')
    ->bind('place_coordinates');
$app->get('/places/{lat}/{lon}/{distance}', 'HotspotMap\Controller\PlaceController::nearestAction')
    ->assert('distance', '\d+')
    ->bind('place_nearest');
$app->post('/places', 'HotspotMap\Controller\PlaceController::postAction')
    ->bind('place_post');
/*$app->put('/places/{id}', 'HotspotMap\Controller\PlaceController::putAction')
    ->bind('place_put');*/

$app->get('/admin/places', 'HotspotMap\Controller\PlaceController::adminListAction')
    ->bind('admin_place_list');
$app->put('/admin/places/{id}', 'HotspotMap\Controller\PlaceController::validateAction')
    ->bind('put_validate');
$app->delete('/admin/places/{id}', 'HotspotMap\Controller\PlaceController::deleteAction')
    ->bind('place_delete');

$app->get('/geoloc/{address}', 'HotspotMap\Controller\GeolocController::findAddressAction')
    ->bind('geolocAddress');
$app->get('/geoloc/{lat}/{lon}', 'HotspotMap\Controller\GeolocController::findCoordinatesAction')
    ->bind('geolocCoordinates');

/*
 * Users Controller
 */
$app->get('/users', 'HotspotMap\Controller\UserController::listAction')
    ->bind('user_list');
$app->get('/users/{id}', 'HotspotMap\Controller\UserController::getAction')
    ->assert('id', '\d+')
    ->bind('user_get');
$app->post('/users', 'HotspotMap\Controller\UserController::postAction')
    ->bind('user_post');

$app->put('/admin/users/{id}', 'HotspotMap\Controller\UserController::putAction')
    ->bind('user_put');
$app->delete('/admin/users/{id}', 'HotspotMap\Controller\UserController::deleteAction')
    ->bind('user_delete');

/*
 * Comments Controller
 */
$app->get('/comments', 'HotspotMap\Controller\CommentController::listAction')
    ->bind('comment_list');
$app->get('/comments/{place}', 'HotspotMap\Controller\CommentController::listForPlaceAction')
    ->assert('id', '\d+')
    ->bind('comment_listForPlace');
/*$app->get('/comments/{id}', 'HotspotMap\Controller\CommentController::getAction')
    ->bind('comment_get');*/
$app->post('/comments', 'HotspotMap\Controller\CommentController::postAction')
    ->bind('comment_post');

$app->put('/admin/comments/{id}', 'HotspotMap\Controller\CommentController::putAction')
    ->bind('comment_put');
$app->delete('/admin/comments/{id}', 'HotspotMap\Controller\CommentController::deleteAction')
    ->bind('comment_delete');

$app->before(function(Request $request) use ($app) {


    $app['geocoder.adapter']  = new \Geocoder\HttpAdapter\CurlHttpAdapter();
    $chain    = new \Geocoder\Provider\ChainProvider(array(
        new \Geocoder\Provider\GoogleMapsProvider($app['geocoder.adapter'], 'fr_FR', 'France', true)
    ));
    $app['geocoder.provider'] = $chain;


    date_default_timezone_set('Europe/Paris');


});