<?php

require_once __DIR__.'/../vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

use Symfony\Component\HttpFoundation\Request;
use HotspotMap\View\Renderer\RendererFactory;
use Silex\Provider\TwigServiceProvider;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use Hateoas\UrlGenerator\CallableUrlGenerator;
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

/*
 * Places Controller
 */
$app->get('/', 'HotspotMap\Controller\PlaceController::listAction')
    ->bind('home');
$app->get('/places', 'HotspotMap\Controller\PlaceController::listAction')
    ->bind('place_list');
$app->get('/places/{id}', 'HotspotMap\Controller\PlaceController::getAction')
    ->bind('place_get');
$app->post('/places', 'HotspotMap\Controller\PlaceController::postAction')
    ->bind('place_post');
$app->put('/places/{id}', 'HotspotMap\Controller\PlaceController::putAction')
    ->bind('place_put');
$app->delete('/places/{id}', 'HotspotMap\Controller\PlaceController::deleteAction')
    ->bind('place_delete');

$app->get('/geoloc/{address}', 'HotspotMap\Controller\GeolocController::findAddressAction')
    ->bind('geolocAddress');
$app->get('/geoloc/{lat}/{lon}', 'HotspotMap\Controller\GeolocController::findCoordinatesAction')
    ->bind('geolocCoordinates');

$app->before(function(Request $request) use ($app) {


    $app['geocoder.adapter']  = new \Geocoder\HttpAdapter\CurlHttpAdapter();
    $chain    = new \Geocoder\Provider\ChainProvider(array(
        new \Geocoder\Provider\GoogleMapsProvider($app['geocoder.adapter'], 'fr_FR', 'France', true)
    ));
    $app['geocoder.provider'] = $chain;


    date_default_timezone_set('Europe/Paris');


});