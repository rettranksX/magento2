<?php

namespace Dev\RestApi\Api\Data;

use Dev\RestApi\Api\Data\ProductInterface;

interface MainDataInterface
{
    /**
     * @return array
     */
    public function execute(): ProductInterface;
}