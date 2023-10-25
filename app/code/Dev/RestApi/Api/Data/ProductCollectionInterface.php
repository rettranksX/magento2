<?php

namespace Dev\RestApi\Api\Data;

interface ProductCollectionInterface
{
    public function addProduct(ProductInterface $product);

    public function getProducts(): array;
}
