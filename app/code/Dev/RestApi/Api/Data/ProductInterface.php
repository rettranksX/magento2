<?php

namespace Dev\RestApi\Api\Data;

interface ProductInterface
{
    public function getSku(): ?string;
    public function getUrl(): ?string;
    public function getManufacturer(): ?string;
}
