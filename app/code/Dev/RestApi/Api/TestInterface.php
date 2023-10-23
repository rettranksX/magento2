<?php
namespace Dev\RestApi\Api;

use Dev\RestApi\Api\Data\MainDataInterface;


interface TestInterface
{
    // /**
    // * @return \Magento\Framework\Controller\Result\Json
    // */
    // public function testMethod();

    /**
     * @return MainDataInterface
     */
    public function testMethod(): MainDataInterface;
}
