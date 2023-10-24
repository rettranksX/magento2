<?php
// namespace Dev\RestApi\Model;

// use Dev\RestApi\Api\TestInterface;
// use Magento\Framework\Controller\Result\JsonFactory;

// class TestModel implements TestInterface
// {
//     protected $jsonFactory;

//     public function __construct(    
//         JsonFactory $jsonFactory
//     )
//     {
//         $this->jsonFactory = $jsonFactory;
//     }

//     public function testMethod()
//     {

//         $data = [
//             'name' => 'John',
//             'age' => 30,
//             'car' => null,
//             'some' => null,
//             'address' => [
//                 'street' => '123 Main St',
//                 'city' => 'Anytown',
//                 'zip' => '12345'
//             ]
//         ];
    
//         $jsonResponse = $this->jsonFactory->create();
//         // $jsonResponse->setData($data);
//         $jsonResponse->setData(['Test-Message' => 'text']);

//         return $jsonResponse;
//     }
    
// }



// namespace Dev\RestApi\Model;

// use Dev\RestApi\Api\TestInterface;
// use Dev\RestApi\Api\Data\MainDataInterface;
// use Dev\RestApi\Model\MainData;

// class TestModel implements TestInterface
// {
//     /**
//      * @return \Dev\RestApi\Api\Data\MainDataInterface
//      */
//         public function testMethod(): MainDataInterface
//         {
//         $mainData = new MainData(new Address());

//         $mainData
//             ->setName('John')
//             ->setAge(30)
//             ->setCar(null)
//             ->setSome(null);

//         $address = new Address();
//         $address
//             ->setStreet('123 Main St')
//             ->setCity('Anytown')
//             ->setZip('12345');

//         $mainData->getAddress($address);

//         return $mainData;
//     }
// }


namespace Dev\RestApi\Model;

use Dev\RestApi\Api\TestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class TestModel implements TestInterface
{
    protected JsonFactory $jsonFactory;

    public function __construct(JsonFactory $jsonFactory)
    {
        $this->jsonFactory = $jsonFactory;
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

        $jsonResponse = $this->jsonFactory->create();
        $jsonResponse->setData($data);

        return $jsonResponse;
    }
}
