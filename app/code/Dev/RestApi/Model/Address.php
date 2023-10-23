<?php

namespace Dev\RestApi\Model;

use Dev\RestApi\Api\Data\AddressInterface;

class Address implements AddressInterface
{
    /**
     * @var string|null
     */
    private $street;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $zip;

    /**
     * Set street.
     *
     * @param string|null $street
     * @return AddressInterface
     */
    public function setStreet(?string $street): AddressInterface
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Set city.
     *
     * @param string|null $city
     * @return AddressInterface
     */
    public function setCity(?string $city): AddressInterface
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Set zip code.
     *
     * @param string|null $zip
     * @return AddressInterface
     */
    public function setZip(?string $zip): AddressInterface
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Get street.
     *
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * Get city.
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Get zip code.
     *
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }
}