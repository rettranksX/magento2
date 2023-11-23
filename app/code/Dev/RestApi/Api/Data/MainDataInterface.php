<?php

namespace Dev\RestApi\Api\Data;

use Dev\RestApi\Api\Data\ProductInterface;

interface MainDataInterface
{
    /**
     * @return ProductInterface|null
     */
    public function execute(): ?ProductInterface;
}