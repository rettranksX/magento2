<?php

namespace Dev\RestApi\Model\Api\Test;

use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Dev\RestApi\Api\Data\ProductInterface;

/**
 * Class ProductRepository
 */
class ProductRepository implements ProductInterface
{
    private $productAction;
    private $productCollectionFactory;
    private $storeManager;
    private $categoryRepository;
    private $shippingConfig;
    private $scopeConfig;
    private $country;
    private $productRepositoryFactory;
    private $request;
    private $configWriter;
    private $countryCollectionFactory;
    private $objectManager;
    private $jsonResultFactory;
    private $countryFactory;

    public function __construct(
        Action $productAction,
        ShippingConfig $shippingConfig,
        CollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository,
        ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Model\Country $country,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        RequestInterface $request,
        WriterInterface $configWriter,
        CollectionFactory $countryCollectionFactory,
        ObjectManagerInterface $objectManager,
        JsonFactory $jsonResultFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        $this->productAction = $productAction;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
        $this->country = $country;
        $this->productRepositoryFactory = $productRepositoryFactory;
        $this->request = $request;
        $this->configWriter = $configWriter;
        $this->countryCollectionFactory = $countryCollectionFactory;
        $this->objectManager = $objectManager;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->countryFactory = $countryFactory;
    }

    public function getCountryCodeByFullName($countryName) {
        $countryCollection = $this->countryFactory->create()->getCollection();
        foreach ($countryCollection as $country) {
            if ($countryName == $country->getName()) {
                return $country->getCountryId();
            }
        }
        return '';
    }

    public function execute()
    {
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

        $storeManager = $this->storeManager;
        $siteUrl = $storeManager->getStore()->getBaseUrl();
        $siteUrl = str_replace("\\", "/", $siteUrl);

        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect(['*']);

        $productsData = [];

        if ($method == 'getProducts' && $actualToken == $token) {
            $productCollection->setPageSize($count);
            $productCollection->setCurPage($offset);

            if ($details == 0) {
                foreach ($productCollection as $product) {
                    $countryName = $product->getAttributeText('country_of_manufacture');
                    $manufacturer = $this->getCountryCodeByFullName($countryName);

                    $productData = new Product(); // Создаем объект данных о продукте
                    $productData->setSku($product->getSku());
                    $productData->setUrl($product->getUrlKey());
                    $productData->setManufacturer($manufacturer);
                    $productData->setModel($product->getModel());
                    $productData->setEan($product->getEan());
                    $productData->setPrice($product->getPrice());
                    $productData->setAvailable($product->isSalable());
                    $productData->setQuantity($product->getQty());
                    $productData->setUpdatedAt($product->getUpdatedAt());

                    $productsData[] = $productData;
                }
            }

            $lastProductId = $productCollection->getLastItem()->getId();

            $responseData = [
                'prods' => $productsData,
                'lastId' => $lastProductId,
            ];

            return $responseData;
        } else {
            return 'Incorrect Method or Token';
        }
    }
}
