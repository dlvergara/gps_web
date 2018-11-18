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
 * @ORM\Table(name="motorcycle")
 */
class Motorcycle implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_motorcycle", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id_motorcycle;

    /**
     * @ORM\Column(name="placa", type="string", length=20)
     * @var string
     */
    private $placa;

    /**
     * @ORM\Column(name="identifier", type="string", length=20)
     * @var string
     */
    private $identifier;

    /**
     * @ORM\Column(name="type", type="string", length=10)
     * @var string
     */
    private $type;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getIdMotorcycle()
    {
        return $this->id_motorcycle;
    }

    /**
     * @return string
     */
    public function getPlaca()
    {
        return $this->placa;
    }

    /**
     * @param string $placa
     */
    public function setPlaca($placa)
    {
        $this->placa = $placa;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }


}