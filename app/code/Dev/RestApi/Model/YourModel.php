<?php
namespace Dev\RestApi\Model;

use Dev\RestApi\Api\YourInterface;

class YourModel implements YourInterface
{
    public function yourMethod()
    {

        $data = [
            'name' => 'John',
            'age' => 30,
            'car' => null,
            'some' => null
        ];

        // $jsonResponse = json_encode($data);

        return $data;
    }
}
