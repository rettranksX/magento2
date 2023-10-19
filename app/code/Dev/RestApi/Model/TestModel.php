<?php
namespace Dev\RestApi\Model;

use Dev\RestApi\Api\TestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class TestModel implements TestInterface
{
    protected $jsonFactory;

    public function __construct(    
        JsonFactory $jsonFactory
    )
    {
        $this->jsonFactory = $jsonFactory;
    }

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
    
        $jsonResponse = $this->jsonFactory->create();
        // $jsonResponse->setData($data);
        $jsonResponse->setData(['Test-Message' => 'text']);

        return $data;
    }
    
}
