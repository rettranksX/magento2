<?php

namespace Dev\RestApi\Api\Data;

interface ProductInterface
{
    public function getSku(): ?string;
    public function getUrl(): ?string;
    public function getManufacturer(): ?string;
    public function getModel(): ?string;
    public function getEan(): ?string;
    public function getPrice(): ?float;
    public function isAvailable(): bool;
    public function getQuantity(): ?int;
    public function getUpdatedAt(): ?string;
}
