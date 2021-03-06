<?php

namespace App\Action;

use App\Entity\MessageLog;
use App\Model\SaveGeoLog;
use Doctrine\ORM\EntityManager;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class MessageAction implements ServerMiddlewareInterface
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
        $success = false;
        $error = null;
        $dataParsed = $request->getParsedBody();
        $appUrl = dirname(dirname(dirname(__DIR__)));

        try {
            $motorcycle = $this->entityManager->getRepository('App\Entity\Motorcycle')
                ->findOneBy(['identifier' => $dataParsed['from']]);

            $messageLog = $this->saveMessageLog($dataParsed);

            if ($motorcycle) {
                $saveGeoLog = new SaveGeoLog($motorcycle, $messageLog, $this->entityManager);
                $success = $saveGeoLog->saveGeoLog($dataParsed);
            }
            //file_put_contents($appUrl."/data/log/log_Parsed_".date('Y_m_d_H_i_s').'.txt', print_r($dataParsed,true));
        } catch (\Exception $e) {
            $error = $e->getMessage();
            file_put_contents($appUrl."/data/log/log_".date('Y_m_d_H_i_s').'.txt', print_r($e,true));
        }

        return new JsonResponse([
            "payload" => [
                "success" => $success,
                "error" => $error,
            ],
        ]);
    }

    /**
     * @param array $dataParsed
     * @return MessageLog
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveMessageLog($dataParsed = array())
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
