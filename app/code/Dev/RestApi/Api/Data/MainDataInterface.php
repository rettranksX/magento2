<?php

namespace Dev\RestApi\Api\Data;

interface MainDataInterface
{
    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get age.
     *
     * @return int|null
     */
    public function getAge(): ?int;

    /**
     * Get car.
     *
     * @return string|null
     */
    public function getCar(): ?string;

    /**
     * Get some.
     *
     * @return string|null
     */
    public function getSome(): ?string;

    /**
     * Get address.
     *
     * @return \Dev\RestApi\Api\Data\AddressInterface
     */
    public function getAddress(): AddressInterface;
}