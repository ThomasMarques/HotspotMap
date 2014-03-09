<?php

require_once __DIR__.'/../vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

use Symfony\Component\HttpFoundation\Request;
use HotspotMap\View\Renderer\RendererFactory;
use Silex\Provider\TwigServiceProvider;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use Hateoas\UrlGenerator\CallableUrlGenerator;

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

/*
 * Users Controller
 */
$app->get('/', 'HotspotMap\Controller\UserController::listAction')
    ->bind('home');
$app->get('/users', 'HotspotMap\Controller\UserController::listAction')
    ->bind('user_list');
$app->get('/users/{id}', 'HotspotMap\Controller\UserController::getAction')
    ->bind('user_get');
$app->post('/users', 'HotspotMap\Controller\UserController::postAction')
    ->bind('user_post');
$app->put('/users/{id}', 'HotspotMap\Controller\UserController::putAction')
    ->bind('user_put');
$app->delete('/users/{id}', 'HotspotMap\Controller\UserController::deleteAction')
    ->bind('user_delete');

/*
 * Comments Controller
 */
$app->get('/', 'HotspotMap\Controller\CommentController::listAction')
    ->bind('home');
$app->get('/comments', 'HotspotMap\Controller\CommentController::listAction')
    ->bind('comment_list');
$app->get('/comments/{id}', 'HotspotMap\Controller\CommentController::getAction')
    ->bind('comment_get');
$app->post('/comments', 'HotspotMap\Controller\CommentController::postAction')
    ->bind('comment_post');
$app->put('/comments/{id}', 'HotspotMap\Controller\CommentController::putAction')
    ->bind('comment_put');
$app->delete('/comments/{id}', 'HotspotMap\Controller\CommentController::deleteAction')
    ->bind('comment_delete');

$app->before(function(Request $request) use ($app) {

});