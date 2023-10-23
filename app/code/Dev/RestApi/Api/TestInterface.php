<?php
namespace Dev\RestApi\Api;

use Dev\RestApi\Api\Data\MainDataInterface;

interface TestInterface
{
    /**
     * @return MainDataInterface
     */
    public function testMethod(): MainDataInterface;
}