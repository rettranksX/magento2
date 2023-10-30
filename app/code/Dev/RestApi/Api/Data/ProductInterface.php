<?php

namespace Dev\RestApi\Api\Data;

interface ProductInterface
{

    /**
     * Get the array of products.
     *
     * @return array
     */
    public function getProducts(): array;

    /**
     * Get the last identifier.
     *
     * @return int
     */
    public function getLastId(): int;

    /**
     * Set the array of products.
     *
     * @return array $products
     */
    public function setProducts(array $products);

    /**
     * Set the last identifier.
     *
     * @return int $lastId
     */
    public function setLastId(int $lastId);
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
