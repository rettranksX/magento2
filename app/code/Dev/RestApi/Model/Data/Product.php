<?php

namespace Dev\RestApi\Model\Data;

use Dev\RestApi\Api\Data\ProductInterface;
use JMS\Serializer\Annotation as Serializer;


class Product implements ProductInterface
{
    private $sku;
    private $url;
    private $manufacturer;
    private $model;
    private $ean;
    private $price;
    private $stock;
    private $availability;
    private $itemAvailable;
    private $updateAt;

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
    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model)
    {
        $this->model = $model;
    }
    public function getEan(): ?string
    {
        return $this->ean;
    }

    public function setEan(?string $ean)
    {
        $this->ean = $ean;
    }
    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price)
    {
        $this->price = $price;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(?string $stock)
    {
        $this->stock = $stock;
    }
    
    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(?string $availability)
    {
        $this->availability = $availability;
    }
    public function getItemAvailable(): ?string
    {
        return $this->itemAvailable;
    }

    public function setItemAvailable(?string $itemAvailable)
    {
        $this->itemAvailable = $itemAvailable;
    }
    public function getUpdateAt(): ?string
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?string $updateAt)
    {
        $this->updateAt = $updateAt;
    }
}
