<?php

namespace PriceInfo\MyModule\Controller\Rest;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Context;

class ProductsController extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface
{
    protected $jsonFactory;

    public function __construct(Context $context, JsonFactory $jsonFactory)
    {
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = [
            "prods" => [
                [
                    "sku" => "12345678",
                    "url" => "https://example.com",
                    "manufacturer" => "Example Manufacturer",
                    "model" => "Example Model",
                    "ean" => "1234567890123",
                    "price" => 9.99,
                    "availability" => "InStock",
                    "itemsAvailable" => 42,
                    "updated" => "2020-02-20 15:44:49"
                ]
            ],
            "lastId" => 1234
        ];

        $result = $this->jsonFactory->create();
        return $result->setData($data);
    }
}
