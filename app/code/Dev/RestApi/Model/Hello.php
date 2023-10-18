<?php
namespace Dev\RestApi\Model;

use Dev\RestApi\Api\HelloInterface;

class Hello implements HelloInterface
{
    public function getHelloMessage()
    {
        return 'Hello, Magento API!';
    }
}
