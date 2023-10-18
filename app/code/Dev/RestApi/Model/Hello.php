<?php
namespace Dev\RestApi\Model;

use YourVendor\YourModule\Api\HelloInterface;

class Hello implements HelloInterface
{
    public function getHelloMessage()
    {
        return 'Hello, Magento API!';
    }
}
