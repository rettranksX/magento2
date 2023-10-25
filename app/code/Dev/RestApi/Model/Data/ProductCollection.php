<?php

namespace Dev\RestApi\Model\Data;

use Dev\RestApi\Api\Data\ProductCollectionInterface;
use Dev\RestApi\Api\Data\ProductInterface;

class ProductCollection implements ProductCollectionInterface
{
    private $products = [];

    public function addProduct(ProductInterface $product)
    {
        $this->products[] = $product;
        return $this;
    }

    public function getProducts()
    {
        return $this->products;
    }
}
