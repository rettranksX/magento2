<?php

namespace Dev\RestApi\Model;

use Dev\RestApi\Api\ProductRepositoryInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;



class ProductModel implements ProductRepositoryInterface
{
    protected $productAction;
    protected $productCollectionFactory;
    protected $storeManager;
    protected $_countryFactory;
    protected $_productRepositoryFactory;
    private $shippingConfig;
    private $scopeConfig;
    private $categoryRepository;



    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Action $productAction,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        ShippingConfig $shippingConfig,
        ScopeConfigInterface $scopeConfig,
        CategoryRepositoryInterface $categoryRepository,
    ) {
        $this->productAction = $productAction;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->_countryFactory = $countryFactory;
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
        $this->categoryRepository = $categoryRepository;
    }

    public function getProducts($details, $offset, $count) {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect([
            array('*')
        ]);
        $productCollection->setPageSize($count);
        $productCollection->setCurPage($offset);

        $productsData = [];

        if ($details == 0) {
            foreach ($productCollection as $product) {
                $countryName = $product->getAttributeText('country_of_manufacture');
                $manufacturer = $this->getCountryCodeByFullName($countryName);
                $productData = [
                    'sku' => $product->getSku(),
                    'url' => $product->getUrlKey(),
                    'manufacturer' => $manufacturer,
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
                $manufacturer = $this->getCountryCodeByFullName($countryName);            

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
                    "country" => $manufacturer,
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
                    'manufacturer' => $manufacturer,
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

        return $responseData;
    }

    public function getProductsBySku($details, $skuArray, $offset, $count) {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect([
            array('*')
        ]);
        $productCollection->setPageSize($count);
        $productCollection->setCurPage($offset);

        $productsData = [];

        if ($details == 0) {
            foreach ($productCollection as $product) {
                $countryName = $product->getAttributeText('country_of_manufacture');
                $manufacturer = $this->getCountryCodeByFullName($countryName);
                if (in_array($product->getSku(), $skuArray)) {
                    $productData = [
                        'sku' => $product->getSku(),
                        'url' => $product->getUrlKey(),
                        'manufacturer' => $manufacturer,
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

                    $manufacturer = $this->getCountryCodeByFullName($countryName);

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
                        "country" => $manufacturer,
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
                        'manufacturer' => $manufacturer,
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

        return $responseData;
    } 

    public function getCountryCodeByFullName($countryName) {
        $countryCollection = $this->_countryFactory->create()->getCollection();
        foreach ($countryCollection as $country) {
            if ($countryName == $country->getName()) {
                return $country->getCountryId();
            }
        }
        return '';
    }

}