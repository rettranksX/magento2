<?php

namespace Dev\RestApi\Model;

use Dev\RestApi\Api\Data\ProductInterface;

class Product implements ProductInterface
{
    private $sku;
    private $url;
    private $manufacturer;
    private $model;
    private $ean;
    private $price;
    private $available;
    private $quantity;
    private $updatedAt;
}
