<?php

namespace Dev\RestApi\Api\Data;

interface ProductInterface
{

    /**
     * Get the array of products.
     *
     * @return \Dev\RestApi\Api\Data\ProductInterface[]
     */
    public function getProducts(): array;

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
    /**
     * Get model of the product.
     *
     * @return string|null
     */
    public function getModel(): ?string;
    /**
     * Get ean of the product.
     *
     * @return string|null
     */
    public function getEan(): ?string;
    /**
     * Get price of the product.
     *
     * @return string|null
     */
    public function getPrice(): ?string;

    /**
     * Get stock status of the product.
     *
     * @return string|null
     */
    public function getAvailability(): ?string;
    /**
     * Get availability of the product.
     *
     * @return string|null
     */
    public function getItemsAvailable(): ?string;

    /**
     * Get update time of the product.
     *
     * @return string|null
     */
    public function getUpdateAt(): ?string;
}
