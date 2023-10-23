<?php

namespace Dev\RestApi\Model;

use Dev\RestApi\Api\Data\MainDataInterface;
use Dev\RestApi\Api\Data\AddressInterface;

class MainData implements MainDataInterface
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var int|null
     */
    private $age;

    /**
     * @var string|null
     */
    private $car;

    /**
     * @var string|null
     */
    private $some;

    /**
     * @var AddressInterface
     */
    private $address;

    public function __construct(AddressInterface $address)
    {
        $this->address = $address;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     * @return MainDataInterface
     */
    public function setName(?string $name): MainDataInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set age.
     *
     * @param int|null $age
     * @return MainDataInterface
     */
    public function setAge(?int $age): MainDataInterface
    {
        $this->age = $age;
        return $this;
    }

    /**
     * Set car.
     *
     * @param string|null $car
     * @return MainDataInterface
     */
    public function setCar(?string $car): MainDataInterface
    {
        $this->car = $car;
        return $this;
    }

    /**
     * Set some.
     *
     * @param string|null $some
     * @return MainDataInterface
     */
    public function setSome(?string $some): MainDataInterface
    {
        $this->some = $some;
        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get age.
     *
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * Get car.
     *
     * @return string|null
     */
    public function getCar(): ?string
    {
        return $this->car;
    }

    /**
     * Get some.
     *
     * @return string|null
     */
    public function getSome(): ?string
    {
        return $this->some;
    }

    /**
     * Get address.
     *
     * @return \Dev\RestApi\Api\Data\AddressInterface
     */
    public function getAddress(): AddressInterface
    {
        return $this->address;
    }
}