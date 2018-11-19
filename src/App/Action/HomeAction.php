<?php

namespace App\Action;

use Doctrine\ORM\EntityManager;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\Twig\TwigRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

class HomeAction implements ServerMiddlewareInterface
{
    private $router;
    private $entityManager;
    private $template;

    public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null, EntityManager $entityManager)
    {
        $this->router   = $router;
        $this->template = $template;
        $this->entityManager = $entityManager;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $fecha = $this->getFecha($request);
        $order = ['motorcycle_id_motorcycle' => "ASC",'procesed_date_time' => "DESC",];
        $where = [];
        if( !empty($fecha) ) {
            //$where['procesed_date_time'] = $fecha;
        }
        $messages = $this->entityManager->getRepository('App\Entity\GeoLog')
            ->findBy($where, $order, 60);

        $data = [];
        $data['geo_log'] = $messages;
        $data['map_key'] = 'AIzaSyCF4kjurk2CXSD-QfrvyugkwZQjAMyDEWM';

        return new HtmlResponse($this->template->render('app::home', $data));
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function getFecha(ServerRequestInterface $request)
    {
        $fecha = "";
        $fechaUrl = $request->getAttribute("fecha");
        $fechaQuery = $request->getQueryParams();
        if( empty($fechaUrl) ) {
            if ( isset($fechaQuery['fecha']) ) {
                $fecha = $fechaQuery['fecha'];
            }
        }

        return $fecha;
    }
}
