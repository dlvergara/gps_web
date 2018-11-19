<?php

namespace App\Action;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Plates\PlatesRenderer;

class HomeAction implements ServerMiddlewareInterface
{
    private $router;
    private $template;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * HomeAction constructor.
     * @param Router\RouterInterface $router
     * @param Template\TemplateRendererInterface|null $template
     * @param EntityManager $entityManager
     * @param ContainerInterface $container
     */
    public function __construct(Router\RouterInterface $router, Template\TemplateRendererInterface $template = null, EntityManager $entityManager, ContainerInterface $container)
    {
        $this->router   = $router;
        $this->template = $template;
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface|HtmlResponse
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $config = $this->container->get('config');
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
        $data['map_key'] = $config['map_key'];

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
