<?php
namespace Dev\RestApi\Model;

use Dev\RestApi\Api\TestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;

class TestModel implements TestInterface
{
    protected $resultFactory;

    public function __construct(
        ResultFactory $resultFactory
    ) {
        $this->resultFactory = $resultFactory;
    }

    public function testMethod()
    {
        $data = [
            'name' => 'John',
            'age' => 30,
            'car' => null,
            'address' => [
                'street' => '123 Main St',
                'city' => 'Anytown',
                'zip' => '12345'
            ]
        ];

        $jsonResponse = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $jsonResponse->setData($data);

        return $jsonResponse;
    }
}
