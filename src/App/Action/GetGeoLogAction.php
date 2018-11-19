<?php

namespace App\Action;

use App\Entity\MessageLog;
use Doctrine\ORM\EntityManager;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class GetGeoLogAction implements ServerMiddlewareInterface
{
    private $container;
    private $entityManager;

    /**
     * MessageAction constructor.
     * @param $container
     * @param EntityManager $entityManager
     */
    public function __construct($container, EntityManager $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return \Psr\Http\Message\ResponseInterface|HtmlResponse|JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $messages = $this->entityManager->getRepository('App\Entity\GeoLog')
            ->findBy([], ['procesed_date_time' => "DESC",], 10);
        
        return new JsonResponse([
            "data" => [
                $messages
            ],
        ]);
    }

    /**
     * @param array $dataParsed
     * @return MessageLog
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveMessageLog($dataParsed=array())
    {
        $messageLog = new MessageLog();
        $messageLog->setFrom($dataParsed['from']);
        $messageLog->setMessage($dataParsed['message']);
        $messageLog->setSentTimestamp($dataParsed['sent_timestamp']);
        $messageLog->setMessageId($dataParsed['message_id']);
        $messageLog->setDeviceId($dataParsed['device_id']);
        $messageLog->setDate(date('Y-m-d H:i:s'));

        $this->entityManager->persist($messageLog);
        $this->entityManager->flush();

        return $messageLog;
    }

}
