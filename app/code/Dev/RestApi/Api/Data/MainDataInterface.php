<?php

namespace Dev\RestApi\Api\Data;

use Dev\RestApi\Api\Data\ProductInterface;

interface MainDataInterface
{

    public function execute(): ?ProductInterface;
}