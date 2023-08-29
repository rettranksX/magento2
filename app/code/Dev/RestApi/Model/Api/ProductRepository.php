<?php
namespace Dev\RestApi\Model\Api;

use Dev\RestApi\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;


/**
 * Class ProductRepository
 */
class ProductRepository implements ProductRepositoryInterface
{

    /**
     * @var Action
     */
    private $productAction;
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    private $categoryRepository;
    /**
     * @param Action $productAction
     * @param CollectionFactory $productCollectionFactory
     * @param StoreManagerInterface $storeManager
     */

    /**
     * @var ShippingConfig
     */
    private $shippingConfig;

    /**
     * @param ShippingConfig $shippingConfig
     */
    protected $_country;
    protected $_productRepositoryFactory;
    protected $request;
    /**
     * @var CountryFactory
     */
    private $countryFactory;

        /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    protected $configWriter;
    private $countryCollectionFactory;
    private $objectManager;
     /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $jsonResultFactory;
    public function __construct(
        Action $productAction,
        ShippingConfig $shippingConfig,
        CollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository,
        ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\Country $country,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        RequestInterface $request,
        WriterInterface $configWriter,
        CollectionFactory $countryCollectionFactory,
        ObjectManagerInterface $objectManager,
        JsonFactory $jsonResultFactory
    ) {
        $this->productAction = $productAction;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
        $this->countryFactory = $countryFactory;
        $this->_country = $country;
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->request = $request;
        $this->configWriter = $configWriter;
        $this->countryCollectionFactory = $countryCollectionFactory;
        $this->objectManager = $objectManager;
        $this->jsonResultFactory = $jsonResultFactory;
    }
    // /**
    //  * {@inheritDoc}
    //  * @param int $details
    //  * @return string
    //  */
    public function getProducts(int $details)
    {

        $actualToken = '8db80264ec5dec920a66562d774b509c';

        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];

        if (preg_match('/Bearer\s+(.*)/', $authorizationHeader, $matches)) {
            $token = $matches[1];
        }

        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);

        $method = isset($requestData['method']) ? $requestData['method'] : null;
        $offset = isset($requestData['offset']) ? $requestData['offset'] : null;
        $count = isset($requestData['count']) ? $requestData['count'] : null;


        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect([
            array('*')
        ]);

        // $productsData = [];

        if ($method == 'getProducts' && $actualToken == $token) {
            $productsData = [];
            $productCollection->setPageSize($count);
            $productCollection->setCurPage($offset);

            if ($details == 0) {
                foreach ($productCollection as $product) {

                    // $countryCollection = $this->countryCollectionFactory->create();
                    // $countryCollection->addFieldToFilter('default_name', $product->getAttributeText('country_of_manufacture'));
                    // $country = $countryCollection->getFirstItem();
                    // $isoCountryCode = $country->getIso2Code();

                    // echo $isoCountryCode;

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
            } else {
                echo 'Incorrect "details" value!';
            }

            // $lastProductId = $productCollection->getLastItem()->getId();
            // $response = [
            //     "prods" => $productsData,
            //     "lastId" => $lastProductId,
            // ];
            
            // $json_data = json_encode($response, JSON_PRETTY_PRINT);
            // return $json_data;

            $lastProductId = $productCollection->getLastItem()->getId();
            $response = [
                "prods" => $productsData,
                "lastId" => $lastProductId,
            ];
            
            $jsonResponse = json_encode($response, JSON_PRETTY_PRINT);
            print($jsonResponse);
        
            // print(json_encode($response));
            // print(json_encode($response, JSON_PRETTY_PRINT) . "\n");

            // return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            // $jsonResult = $this->jsonResultFactory->create();
            // $jsonResult->setData($response);
            // return $jsonResult;

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
            $response = [
                "prods" => $productsData,
                "lastId" => $lastProductId,
            ];
            $json_data = json_encode($response);
            return $json_data;
        } else {
            return 'Incorrect Method or Token';
        }
        
    }

}