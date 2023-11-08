<?php

namespace Dev\RestApi\Api\Data;

use Dev\RestApi\Api\Data\ProductInterface;

interface MainDataInterface
{
    /**
     * @return ProductInterface[]
     */
    public function execute(): string;
}