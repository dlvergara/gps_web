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
        $this->router = $router;
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
        $fecha = $this->getAttr($request, 'fecha');
        $fechaFin = $this->getAttr($request, 'fechaFin');

        $messages = $this->getData($fecha, $fechaFin);

        $data = [];
        $data['geo_log'] = $messages;
        $data['map_key'] = $config['map_key'];

        return new HtmlResponse($this->template->render('app::home', $data));
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    public function getAttr(ServerRequestInterface $request, $paramName)
    {
        $fecha = "";
        $fechaUrl = $request->getAttribute($paramName);
        $fechaQuery = $request->getQueryParams();
        if (empty($fechaUrl)) {
            if (isset($fechaQuery[$paramName])) {
                $fecha = $fechaQuery[$paramName];
            }
        }

        return $fecha;
    }

    /**
     * @param $fecha
     * @param $fechaInicio
     * @return array
     */
    public function getData($fecha, $fechaFin)
    {
        $alias = 't';
        $order = ['motorcycle_id_motorcycle' => "ASC", 'procesed_date_time' => "DESC",];

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select($alias)
            ->from('App\Entity\GeoLog', $alias)
            ->where($alias . '.latitude IS NOT NULL');

        if (!empty($fecha) && !empty($fechaFin)) {
            $qb->andWhere($alias . '.procesed_date_time BETWEEN :fecha AND :fechaFin');
            $qb->setParameter('fecha', $fecha);
            $qb->setParameter('fechaFin', $fechaFin);
        } else {
            if (!empty($fecha)) {
                $qb->andWhere($alias . '.procesed_date_time >= :fecha');
                $qb->setParameter('fecha', $fecha);
            }
            if (!empty($fechaFin)) {
                $qb->andWhere($alias . '.procesed_date_time <= :fechaFin');
                $qb->setParameter('fechaFin', $fechaFin);
            }
        }

        foreach ($order as $key => $info) {
            $qb->addOrderBy($alias . '.' . $key, $info);
        }
        
        $messages = $qb->getQuery()->getResult();

        return $messages;
    }
}
