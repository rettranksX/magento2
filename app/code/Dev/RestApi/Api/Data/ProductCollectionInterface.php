<?php

namespace Dev\RestApi\Api\Data;

interface ProductCollectionInterface
{
    /**
     * Add a product to the collection.
     *
     * @param ProductInterface $product
     * @return $this
     */
    public function addProduct(ProductInterface $product);

    /**
     * Get the products in the collection.
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array;
}
