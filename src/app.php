<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use HotspotMap\View\Renderer\RendererFactory;
use Silex\Provider\TwigServiceProvider;

$app = new Silex\Application();
$app['mime-types'] = [ 'text/html', 'application/xml', 'application/json' ];
$app['debug'] = true;

/**
 * Renderer
 */
$app['renderer'] = $app->share(function () use ($app) {
    return new \HotspotMap\View\ViewRenderer($app, $_SERVER, new RendererFactory());
});

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../web/views',
));

/*
 * Places Controller
 */
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

$app->before(function(Request $request) use ($app) {

});