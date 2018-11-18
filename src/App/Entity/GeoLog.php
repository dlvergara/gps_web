<?php
/**
 * Created by PhpStorm.
 * User: David Leonardo V
 * Date: 13/11/2018
 * Time: 11:00 PM
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="geo_log")
 */
class GeoLog implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_geo_log", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id_geo_log;

    /**
     * @ORM\Column(name="motorcycle_id_motorcycle", type="integer")
     * @var int
     */
    protected $motorcycle_id_motorcycle;

    /**
     * @ORM\Column(name="message_log_id_message_log", type="integer")
     * @var int
     */
    protected $message_log_id_message_log;

    /**
     * @ORM\Column(name="latitude", type="string", length=45)
     * @var string
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="string", length=45)
     * @var string
     */
    private $longitude;

    /**
     * @ORM\Column(name="speed", type="integer")
     * @var int
     */
    private $speed;

    /**
     * @ORM\Column(name="bat", type="float")
     * @var int
     */
    private $bat;

    /**
     * @ORM\Column(name="date", type="string", length=20)
     * @var string
     */
    private $date;

    /**
     * @ORM\Column(name="time", type="string", length=30)
     * @var string
     */
    private $time;

    /**
     * @ORM\Column(name="link", type="string", length=255)
     * @var string
     */
    private $link;

    /**
     * @ORM\Column(name="procesed_date_time", type="string", length=30)
     * @var string
     */
    private $procesed_date_time;

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach (get_class_vars(get_class($this)) as $index => $get_class_var) {
            $data[$index] = $this->$index;
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getProcesedDateTime()
    {
        return $this->procesed_date_time;
    }

    /**
     * @param string $procesed_date_time
     */
    public function setProcesedDateTime($procesed_date_time)
    {
        $this->procesed_date_time = $procesed_date_time;
    }

    /**
     * @return int
     */
    public function getIdGeoLog()
    {
        return $this->id_geo_log;
    }

    /**
     * @return Motorcycle
     */
    public function getMotorcycleIdMotorcycle()
    {
        return $this->motorcycle_id_motorcycle;
    }

    /**
     * @param Motorcycle $motorcycle_id_motorcycle
     */
    public function setMotorcycleIdMotorcycle($motorcycle_id_motorcycle)
    {
        $this->motorcycle_id_motorcycle = $motorcycle_id_motorcycle;
    }

    /**
     * @return MessageLog
     */
    public function getMessageLogIdMessageLog()
    {
        return $this->message_log_id_message_log;
    }

    /**
     * @param MessageLog $message_log_id_message_log
     */
    public function setMessageLogIdMessageLog($message_log_id_message_log)
    {
        $this->message_log_id_message_log = $message_log_id_message_log;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return int
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @param int $speed
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;
    }

    /**
     * @return int
     */
    public function getBat()
    {
        return $this->bat;
    }

    /**
     * @param int $bat
     */
    public function setBat($bat)
    {
        $this->bat = $bat;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

}