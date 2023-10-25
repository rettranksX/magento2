<?php

namespace Dev\RestApi\Model\Data;

use Dev\RestApi\Api\Data\ProductInterface;

class Product implements ProductInterface
{
    private $sku;
    private $url;
    private $manufacturer;

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku)
    {
        $this->sku = $sku;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url)
    {
        $this->url = $url;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }
}
