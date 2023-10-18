<?php
namespace Dev\RestApi\Model;

use Dev\RestApi\Api\TestInterface;

class TestModel implements TestInterface
{
    public function testMethod()
    {

        $data = [
            'name' => 'John',
            'age' => 30,
            'car' => null,
            'some' => null,
            'address' => [
                'street' => '123 Main St',
                'city' => 'Anytown',
                'zip' => '12345'
            ]
        ];


        return $data;
    }
}
