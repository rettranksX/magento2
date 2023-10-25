<?php

namespace Dev\RestApi\Model\Api;

use Dev\RestApi\Api\Data\ProductCollectionInterface;
use Dev\RestApi\Api\ProductRepositoryInterface;
use Dev\RestApi\Api\Data\ProductInterface;
use Dev\RestApi\Api\Data\MainDataInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class ProductRepository
 */
class ProductRepository implements MainDataInterface
{
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    private $countryCollectionFactory;
    private $jsonResultFactory;
    private $countryFactory;

    public function __construct(
        CollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->countryFactory = $countryFactory;
    }

    public function getCountryCodeByFullName($countryName)
    {
        $countryCollection = $this->countryFactory->create()->getCollection();
        foreach ($countryCollection as $country) {
            if ($countryName == $country->getName()) {
                return $country->getCountryId();
            }
        }
        return '';
    }

    public function execute(): ProductCollectionInterface
    {
        $actualToken = $this->scopeConfig->getValue(
            'priceinfo_module/general/token_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

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

        $productCollection = new \Dev\RestApi\Model\Data\ProductCollection();

        if ($method == 'getProducts' && $actualToken == $token) {
            $productCollectionModel = $this->productCollectionFactory->create();
            $productCollectionModel->setPageSize($count);
            $productCollectionModel->setCurPage($offset);

            if ($details == 0) {
                foreach ($productCollectionModel as $product) {
                    $countryName = $product->getAttributeText('country_of_manufacture');
                    $manufacturer = $this->getCountryCodeByFullName($countryName);

                    $productData = new \Dev\RestApi\Model\Data\Product();
                    $productData->setSku($product->getSku());
                    if (!empty($product->getUrlKey())) {
                        $productData->setUrl($product->getUrlKey());
                    } else {
                        $productData->setUrl('');
                    }

                    $productData->setManufacturer($manufacturer);

                    $productCollection->addProduct($productData);
                }
            }

            $lastProductId = $productCollectionModel->getLastItem()->getId();

            return $productCollection;
        } else {
            return new \Dev\RestApi\Model\Data\ProductCollection();
        }
    }
}
