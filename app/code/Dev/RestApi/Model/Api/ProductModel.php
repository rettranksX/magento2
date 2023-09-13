<?php
namespace Dev\RestApi\Model\Api;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class ProductModel
{
    protected $productCollectionFactory;
    protected $storeManager;

    public function __construct(
        CollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
    }

    public function getProducts($count, $offset, $details)
    {
        $actualToken = '8db80264ec5dec920a66562d774b509c';

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

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect([
            array('*')
        ]);

        $productsData = [];

        if ($method == 'getProducts' && $actualToken == $token) {
            $productCollection->setPageSize($count);
            $productCollection->setCurPage($offset);

            if ($details == 0) {
                foreach ($productCollection as $product) {
                    $productData = [
                        'sku' => $product->getSku(),
                        'url' => $product->getUrlKey(),
                        'manufacturer' => $product->getAttributeText('country_of_manufacture'),
                        'model' => $product->getModel(),
                        'ean' => $product->getEan(),
                        'price' => $product->getPrice(),
                        'availability' => $product->isSalable() ? 'InStock' : 'OutOfStock',
                        'itemsAvailable' => $product->getQty(),
                        'updated' => $product->getUpdatedAt(),
                    ];

                    $productsData[] = $productData;
                }
            } elseif ($details == 1) {
                foreach ($productCollection as $product) {
                    $deliveryOptions = [];

                    $productImage = $this->_productRepositoryFactory->create()->getById($product->getId());
                    $image = $productImage->getData('image');
                    $thumbnail = $productImage->getData('thumbnail');
                    $smallImage = $productImage->getData('small_image');
                    $images = [$image, $thumbnail, $smallImage];
                    $countryName = $product->getAttributeText('country_of_manufacture');
            
                    $countryModel = $this->countryFactory->create();
                    $countryCollection = $countryModel->getCollection();
                    $country = $countryCollection->addFieldToFilter('default_name', $countryName)->getFirstItem();
        
                    if ($country->getId()) {
                        $isoCountryCode = $country->getData('iso2_code');
                    } else {
                        $isoCountryCode = $countryName;
                    }


                    $availableMethods = [];
                    $carriers = $this->shippingConfig->getActiveCarriers();

                    foreach ($carriers as $carrierCode => $carrierModel) {
                        $pathPrice = "carriers/{$carrierCode}/price";
                        $pathEstimateTime = "carriers/{$carrierCode}/estimated_delivery_time";
                        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                        $availableMethods[] = [
                            'name' => $carrierCode,
                            'shippingRate' => $this->scopeConfig->getValue($pathPrice, $storeScope),
                            'deliveryDays' => $this->scopeConfig->getValue($pathEstimateTime, $storeScope),
                        ];
                    }

                    $deliveryOptions[] = [
                        "country" => $isoCountryCode,
                        "carriers" => $availableMethods,
                    ];

                    $categoryNames = [];
                    $categoryIds = $product->getCategoryIds();

                    foreach ($categoryIds as $categoryId) {
                        $category = $this->categoryRepository->get($categoryId);
                        $categoryNames[] = $category->getName();
                    }

                    $productData = [
                        "sku" => $product->getSku(),
                        "url" => $product->getUrlKey(),
                        'manufacturer' => $isoCountryCode,
                        "model" => $product->getModel(),
                        "ean" => $product->getEan(),
                        "price" => $product->getPrice(),
                        'availability' => $product->isSalable() ? 'InStock' : 'OutOfStock',
                        'itemsAvailable' => $product->getQty(),
                        "itemCondition" => "NewCondition",
                        "category" => $categoryNames,
                        "name" => $product->getName(),
                        "description" => $product->getDescription(),
                        'updated' => $product->getUpdatedAt(),
                        'delivery' => $deliveryOptions,
                        'images' => $siteUrl . ltrim($image, '/')
                    ];

                    $productsData[] = $productData;
                }
            } else {
                echo 'Incorrect "details" value!';
            }

            $lastProductId = $productCollection->getLastItem()->getId();

            $responseData = [
                'prods' => $productsData,
                'lastId' => $lastProductId,
            ];

            $jsonResponse = json_encode($responseData, JSON_PRETTY_PRINT);
            return $jsonResponse;

        } elseif ($method == 'getProductsBySku' && $actualToken == $token) {
            $skuArray = $requestData['sku'] ?? [];

            $offset = $requestData['offset'] ?? 0;
            $count = $requestData['count'] ?? 10;

            if ($details == 0) {
                foreach ($productCollection as $product) {
                    if (in_array($product->getSku(), $skuArray)) {
                        $productData = [
                            'sku' => $product->getSku(),
                            'url' => $product->getUrlKey(),
                            'manufacturer' => $product->getAttributeText('country_of_manufacture'),
                            'model' => $product->getModel(),
                            'ean' => $product->getEan(),
                            'price' => $product->getPrice(),
                            'availability' => $product->isSalable() ? 'InStock' : 'OutOfStock',
                            'itemsAvailable' => $product->getQty(),
                            'updated' => $product->getUpdatedAt(),
                        ];

                        $productsData[] = $productData;
                    }
                }
            } elseif ($details == 1) {
                foreach ($productCollection as $product) {
                    if (in_array($product->getSku(), $skuArray)) {
                        $deliveryOptions = [];

                        $productImage = $this->_productRepositoryFactory->create()->getById($product->getId());
                        $image = $productImage->getData('image');
                        $thumbnail = $productImage->getData('thumbnail');
                        $smallImage = $productImage->getData('small_image');
                        $images = [$image, $thumbnail, $smallImage];

                        $countryName = $product->getAttributeText('country_of_manufacture');

                        $countryModel = $this->countryFactory->create();
                        $countryCollection = $countryModel->getCollection();
                        $country = $countryCollection->addFieldToFilter('iso2_code', $countryName)->getFirstItem();

                        if ($country->getId()) {
                            $isoCountryCode = $country->getIso2Code();
                        } else {
                            $isoCountryCode = $countryName;
                        }

                        $availableMethods = [];
                        $carriers = $this->shippingConfig->getActiveCarriers();

                        foreach ($carriers as $carrierCode => $carrierModel) {
                            $pathPrice = "carriers/{$carrierCode}/price";
                            $pathEstimateTime = "carriers/{$carrierCode}/estimated_delivery_time";
                            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                            $availableMethods[] = [
                                'name' => $carrierCode,
                                'shippingRate' => $this->scopeConfig->getValue($pathPrice, $storeScope),
                                'deliveryDays' => $this->scopeConfig->getValue($pathEstimateTime, $storeScope),
                            ];
                        }

                        $deliveryOptions[] = [
                            "country" => $isoCountryCode,
                            "carriers" => $availableMethods,
                        ];

                        $categoryNames = [];
                        $categoryIds = $product->getCategoryIds();

                        foreach ($categoryIds as $categoryId) {
                            $category = $this->categoryRepository->get($categoryId);
                            $categoryNames[] = $category->getName();
                        }

                        $productData = [
                            "sku" => $product->getSku(),
                            "url" => $product->getUrlKey(),
                            'manufacturer' => $product->getAttributeText('country_of_manufacture'),
                            "model" => $product->getModel(),
                            "ean" => $product->getEan(),
                            "price" => $product->getPrice(),
                            'availability' => $product->isSalable() ? 'InStock' : 'OutOfStock',
                            'itemsAvailable' => $product->getQty(),
                            "itemCondition" => "NewCondition",
                            "category" => $categoryNames,
                            "name" => $product->getName(),
                            "description" => $product->getDescription(),
                            'updated' => $product->getUpdatedAt(),
                            'delivery' => $deliveryOptions,
                            'images' => $images
                        ];

                        $productsData[] = $productData;
                    }
                }
            } else {
                echo 'Incorrect "details" value!';
            }

            $lastProductId = $productCollection->getLastItem()->getId();

            $responseData = [
                'prods' => $productsData,
                'lastId' => $lastProductId,
            ];

            $jsonResponse = json_encode($responseData, JSON_PRETTY_PRINT);
            return $jsonResponse;
        } 
        else {
            // $response = [];
            return 'Incorrect Method or Token';
        }    }
}
