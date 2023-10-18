<?php
namespace Dev\RestApi\Model;

use Dev\RestApi\Api\HelloInterface;

class Hello implements HelloInterface
{
    public function getHelloMessage()
    {

        $data = [
            'name' => 'John',
            'age' => 30,
            'car' => null
        ];
        return $data;
    }
}
