<?php

namespace Dev\RestApi\Api\Data;

interface ProductCollectionInterface
{
    /**
     * Get the array of products.
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array;
}
