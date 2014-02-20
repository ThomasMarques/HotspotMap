<?php

namespace HotspotMap\View;

use Symfony\Component\HttpFoundation\Response;
use Negotiation\Negotiator;

/**
 * Class ViewRenderer
 * @package hotspotMap\View
 * Renderer for JSON / XML / HTML
 */
class ViewRenderer
{

    private $application;
    private $negotiator;
    private $header;
    private $hateoas;
    private $rendererFactory;

    public function __construct($app, $headers = [], $factory)
    {
        $this->application = $app;
        $this->negotiator = new Negotiator();
        $this->header = $headers;
        $this->rendererFactory = $factory;
    }

    public function render($app, $statusCode = 200, $data)
    {

        $response = new Response();
        $accept = $this->header['HTTP_ACCEPT'];
        $mimes = $this->application['mime-types'];
        $format = $this->negotiator->getBest($accept, $mimes);
        $format = $format->getValue();

        if( !in_array($format, $this->application['mime-types']) ) {
            $statusCode = 406;
        }

        $rendererType = '';
        switch($format) {
            case 'application/json':
                $rendererType = 'Json';
                break;
            case 'application/xml':
                $rendererType = 'Xml';
                break;
            case 'text/html':
                $rendererType = 'Html';
                break;
        }

        $content = '';
        if ($rendererType != '') {
            $renderer = $this->rendererFactory->getRenderer($rendererType);
            $content = $renderer->render($app, '', $data);
        }

        $response->setContent($content);
        $response->setStatusCode($statusCode);
        $response->headers->add([ 'Content-Type' => $format ]);
        return $response;

    }

} 