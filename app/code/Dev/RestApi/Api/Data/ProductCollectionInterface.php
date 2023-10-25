<?php

namespace Dev\RestApi\Api\Data;

use Dev\RestApi\Api\Data\ProductInterface;

interface ProductCollectionInterface
{
    /**
     * Add a product to the collection
     *
     * @param ProductInterface $product
     * @return $this
     */
    public function addProduct(ProductInterface $product);

    /**
     * Get the list of products in the collection
     *
     * @return ProductInterface[]
     */
    public function getProducts();
}
