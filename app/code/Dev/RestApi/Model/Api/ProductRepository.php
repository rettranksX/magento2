<?php
namespace Dev\RestApi\Model\Api;


use Dev\RestApi\Api\ProductRepositoryInterface;
use Dev\RestApi\Api\RequestItemInterfaceFactory;
use Dev\RestApi\Api\ResponseItemInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Shipping\Model\Config as ShippingConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;


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
     * @var RequestItemInterfaceFactory
     */
    private $requestItemFactory;
    /**
     * @var ResponseItemInterfaceFactory
     */
    private $responseItemFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    private $categoryRepository;
    /**
     * @param Action $productAction
     * @param CollectionFactory $productCollectionFactory
     * @param RequestItemInterfaceFactory $requestItemFactory
     * @param ResponseItemInterfaceFactory $responseItemFactory
     * @param StoreManagerInterface $storeManager
     */

    /**
     * @var ShippingConfig
     */
    private $shippingConfig;

    /**
     * @param ShippingConfig $shippingConfig
     */
    private $scopeConfig;
    protected $countryFactory;
    protected $_country;
    protected $_productRepositoryFactory;
    protected $request;



    public function __construct(
        Action $productAction,
        ShippingConfig $shippingConfig,
        CollectionFactory $productCollectionFactory,
        RequestItemInterfaceFactory $requestItemFactory,
        ResponseItemInterfaceFactory $responseItemFactory,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository,
        ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\Country $country,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        RequestInterface $request

    ) {
        $this->productAction = $productAction;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->requestItemFactory = $requestItemFactory;
        $this->responseItemFactory = $responseItemFactory;
        $this->storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
        $this->countryFactory = $countryFactory;
        $this->_country = $country;
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->request = $request;
    }
    /**
     * {@inheritDoc}
     *
     * @param int $id
     * @return ResponseItemInterface
     * @throws NoSuchEntityException
     */
    public function getItem(int $id): mixed
    {
        $collection = $this->getProductCollection()
            ->addAttributeToFilter('entity_id', ['eq' => $id]);
        /** @var ProductInterface $product */
        $product = $collection->getFirstItem();
        if (!$product->getId()) {
            throw new NoSuchEntityException(__('Product not found'));
        }
        return $this->getResponseItemFromProduct($product);
    }
    /**
     * {@inheritDoc}
     *
     * @param RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products): void
    {
        foreach ($products as $product) {
            $this->setDescriptionForProduct(
                $product->getId(),
                $product->getDescription()
            );
        }
    }
    /**
     * @return Collection
     */
    private function getProductCollection(): mixed
    {
        /** @var Collection $collection */
        $collection = $this->productCollectionFactory->create();
        $collection
            ->addAttributeToSelect(
                [
                    'entity_id',
                    ProductInterface::SKU,
                    ProductInterface::NAME,
                    'description'
                ],
                'left'
            );
        return $collection;
    }
    /**
     * @param ProductInterface $product
     * @return ResponseItemInterface
     */
    private function getResponseItemFromProduct(ProductInterface $product): mixed
    {
        /** @var ResponseItemInterface $responseItem */
        $responseItem = $this->responseItemFactory->create();
        $responseItem->setId($product->getId())
            ->setSku($product->getSku())
            ->setName($product->getName())
            ->setDescription($product->getDescription() ?? '');
        return $responseItem;
    }
    /**
     * Set the description for the product.
     *
     * @param int $id
     * @param string $description
     * @return void
     */
    private function setDescriptionForProduct(int $id, string $description): void
    {
        $this->productAction->updateAttributes(
            [$id],
            ['description' => $description],
            $this->storeManager->getStore()->getId()
        );
    }


    /**
     * {@inheritDoc}
     * @param int $details
     * @param int $offset
     * @param int $count
     * @return array
     */
    public function getProducts(int $details, int $offset, int $count): array
    {
        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);

        $method = isset($requestData['method']) ? $requestData['method'] : null;

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect([
            array('*')
        ]);
        // $productCollection->setPageSize($count);
        // $productCollection->setCurPage($offset);

        $productsData = [];

        if($method == 'getProducts'){
            // if ($offset == null || $count == null) {
            //     return ['error' => 'Offset and count are required for method getProducts'];
            // }

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
            }
            if ($details == 1) {
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
            }
        }
        elseif($method == 'getProductsBySku'){
            $sku = isset($requestData['sku']) ? $requestData['sku'] : null;

        } 
        else {
            return ['error' => 'Incorrect Method!'];
        }
        
        $lastProductId = $productCollection->getLastItem()->getId();
        
        $response = [
            'prods' => $productsData,
            'lastId' => $lastProductId,
        ];
        
        return $response;
        
    }

    // public function getProductsBySku(int $details, array $skus): array
    // {

    // }

}