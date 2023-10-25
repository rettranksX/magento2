<?php

namespace Dev\RestApi\Api\Data;

interface MainDataInterface
{
    /**
     * @return \Dev\RestApi\Api\Data\ProductCollectionInterface
     */
    public function execute();
}
