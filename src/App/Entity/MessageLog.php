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
 * @ORM\Table(name="message_log")
 */
class MessageLog implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_message_log", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id_message_log;

    /**
     * @ORM\Column(name="message_from", type="string", length=255)
     * @var string
     */
    private $from;

    /**
     * @ORM\Column(name="message", type="string", length=255)
     * @var string
     */
    private $message;

    /**
     * @ORM\Column(name="sent_timestamp", type="string", length=255)
     * @var string
     */
    private $sent_timestamp;

    /**
     * @ORM\Column(name="message_id", type="string", length=255)
     * @var string
     */
    private $message_id;

    /**
     * @ORM\Column(name="device_id", type="string", length=255)
     * @var string
     */
    private $device_id;

    /**
     * @ORM\Column(name="date", type="string", length=20)
     * @var string
     */
    private $date;

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
     * @return int
     */
    public function getId()
    {
        return $this->id_message_log;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getSentTimestamp()
    {
        return $this->sent_timestamp;
    }

    /**
     * @param string $sent_timestamp
     */
    public function setSentTimestamp($sent_timestamp)
    {
        $this->sent_timestamp = $sent_timestamp;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @param string $message_id
     */
    public function setMessageId($message_id)
    {
        $this->message_id = $message_id;
    }

    /**
     * @return string
     */
    public function getDeviceId()
    {
        return $this->device_id;
    }

    /**
     * @param string $device_id
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;
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

}