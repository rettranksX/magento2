<?php
namespace Dev\RestApi\Model\Api;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Dev\RestApi\Model\Api\ProductModel;
use Magento\Framework\Controller\Result\JsonFactory;

class ProductController extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{
    private $productModel;
    private $jsonResultFactory;

    public function __construct(
        Context $context,
        ProductModel $productModel,
        JsonFactory $jsonResultFactory
    ) {
        parent::__construct($context);
        $this->productModel = $productModel;
        $this->jsonResultFactory = $jsonResultFactory;
    }

    public function execute()
    {
        $count = $this->getRequest()->getParam('count');
        $offset = $this->getRequest()->getParam('offset');
        $details = $this->getRequest()->getParam('details');
        
        $products = $this->productModel->getProducts($count, $offset, $details);
        
        $result = $this->jsonResultFactory->create();
        return $result->setData($products);
    }

}