<?php
namespace Dev\RestApi\Api\Data;

interface MainDataInterface
{
    /**
     * @return \Dev\RestApi\Api\Data\ProductInterface[]
     */
    public function execute(): array;
}
