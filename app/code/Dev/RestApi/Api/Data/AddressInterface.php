<?php

namespace Dev\RestApi\Api\Data;

interface AddressInterface
{
    /**
     * Get street.
     *
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * Get city.
     *
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * Get zip code.
     *
     * @return string|null
     */
    public function getZip(): ?string;
}