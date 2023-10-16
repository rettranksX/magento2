<?php

namespace Dev\RestApi\Controller\Api;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\RequestInterface;
use Dev\RestApi\Model\ProductModel;

class ProductController extends Action
{
    protected $jsonResultFactory;
    protected $request;
    protected $productModel;

    public function __construct(
        JsonFactory $jsonResultFactory,
        RequestInterface $request,
        ProductModel $productModel
    ) {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->request = $request;
        $this->productModel = $productModel;
    }

    public function execute() {

        $actualToken = $this->scopeConfig->getValue('priceinfo_module/general/token_text', 
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];

        if (preg_match('/Bearer\s+(.*)/', $authorizationHeader, $matches)) {
            $token = $matches[1];
        }

        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);

        $details = isset($requestData['details']) ? $requestData['details'] : null;
        $method = isset($requestData['method']) ? $requestData['method'] : null;
        $offset = isset($requestData['offset']) ? $requestData['offset'] : null;
        $count = isset($requestData['count']) ? $requestData['count'] : null;

        $storeManager = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Store\Model\StoreManagerInterface::class);
        $siteUrl = $storeManager->getStore()->getBaseUrl();
        $siteUrl = str_replace("\\", "/", $siteUrl);

        if ($method == 'getProducts' && $actualToken == $token) {
            $result = $this->productModel->getProducts($details, $offset, $count);

            // var_dump($result);

            $jsonResponse = $this->jsonResultFactory->create();
            $jsonResponse->setData(['result' => $result]);
        
            return $jsonResponse;
        }
    }
}
