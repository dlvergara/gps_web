<?php
/**
 * Created by PhpStorm.
 * User: David Leonardo V
 * Date: 18/11/2018
 * Time: 2:24 PM
 */

namespace App\Model;

use App\Entity\MessageLog;
use App\Entity\Motorcycle;
use App\Entity\GeoLog;
use Doctrine\ORM\EntityManager;

class SaveGeoLog
{
    const GPS_MODEL_1 = "GPS_CH_1";
    const GPS_MODEL_2 = "GPS_USA_1";

    /**
     * @var Motorcycle
     */
    private $moto;

    /**
     * @var MessageLog
     */
    private $messageLog;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(Motorcycle $moto, MessageLog $messageLog, EntityManager $entityManager)
    {
        $this->moto = $moto;
        $this->messageLog = $messageLog;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $originalMessage
     * @return bool
     */
    public function saveGeoLog($originalMessage)
    {
        $typeMoto = $this->moto->getType();
        $result = false;
        try{
            switch ($typeMoto) {
                case self::GPS_MODEL_1:
                    $this->saveModel1($originalMessage);
                    break;
                case self::GPS_MODEL_2:
                    $this->saveModel2($originalMessage);
                    break;
            }
            $result = true;
        } catch (\Exception $exception) {
            var_dump( $exception->getMessage() ); exit;
            error_log($exception);
        }
        return $result;
    }

    /**
     * @param $originalMessage
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveModel1($originalMessage)
    {
        $messageData = explode(" ", $originalMessage['message']);

        $geoLog = new GeoLog();
        $geoLog->setLink($messageData[0]);
        $date = explode(":", $messageData[1]);
        $geoLog->setDate($date[1]);
        $time = explode(":", $messageData[2]);
        $geoLog->setTime($time[1]);
        $speed = explode(":", $messageData[5]);
        $geoLog->setSpeed($speed[1]);
        $bat = explode(":", $messageData[6]);
        $geoLog->setBat($bat[1]);

        $geoLog->setMessageLogIdMessageLog($this->messageLog->getId());
        $geoLog->setMotorcycleIdMotorcycle($this->moto->getIdMotorcycle());

        $this->entityManager->persist($geoLog);
        $this->entityManager->flush();
    }

    /**
     * 0 -> GPS!
     * 1 -> lat:-12.11394
     * 2 -> long:-77.00701
     * 3 -> speed:000.4
     * 4 -> T:11/18/18
     * 5 -> 20:10
     * 5 -> http://maps.google.com/maps?f=q&q=-12.11394,-77.00701&z=16
     * @param $originalMessage
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveModel2($originalMessage)
    {
        $sep = " ";
        $messageData = explode($sep, $originalMessage['message']);

        $geoLog = new GeoLog();
        $geoLog->setMessageLogIdMessageLog($this->messageLog->getId());
        $geoLog->setMotorcycleIdMotorcycle($this->moto->getIdMotorcycle());

        $lat = explode(":", $messageData[1]);
        $geoLog->setLatitude($lat[1]);

        $long = explode(":", $messageData[2]);
        $geoLog->setLongitude($long[1]);

        $geoLog->setLink($messageData[5]);

        $date = explode(":", $messageData[4]);
        $date = $date[1].' '.$messageData[5];
        $processedDate = date( "Y-m-d H:i:s", strtotime( $date ) - 5 * 3600 );

        $geoLog->setProcesedDateTime($processedDate);
        $geoLog->setTime($messageData[5]);
        $geoLog->setDate($date);

        $speed = explode(":", $messageData[3]);
        $geoLog->setSpeed(intval($speed[1]));

        $this->entityManager->persist($geoLog);
        $this->entityManager->flush();
    }
}