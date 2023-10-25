<?php

namespace Dev\RestApi\Api\Data;

interface ProductInterface
{
    /**
     * Get SKU of the product.
     *
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * Get URL of the product.
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Get manufacturer of the product.
     *
     * @return string|null
     */
    public function getManufacturer(): ?string;
}
