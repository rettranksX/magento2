<?php

namespace Dev\RestApi\Model\Api;

use Dev\RestApi\Api\ProductRepositoryInterface;
use Dev\RestApi\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class ProductRepository
 */
class ProductRepository implements ProductRepositoryInterface
{
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
     * @param CollectionFactory $productCollectionFactory
     * @param StoreManagerInterface $storeManager
     */

    // /**
    //  * @var ShippingConfig
    //  */
    // private $shippingConfig;

    // /**
    //  * @param ShippingConfig $shippingConfig
    //  */
    protected $_country;
    protected $_productRepositoryFactory;
    protected $request;


    protected $_countryFactory;


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
        // ShippingConfig $shippingConfig,
        CollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        // CategoryRepositoryInterface $categoryRepository,
        ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Model\Country $country,
        // \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        // RequestInterface $request,
        // WriterInterface $configWriter,
        // CollectionFactory $countryCollectionFactory,
        // ObjectManagerInterface $objectManager,
        // JsonFactory $jsonResultFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        // $this->categoryRepository = $categoryRepository;
        // $this->shippingConfig = $shippingConfig;
        $this->scopeConfig = $scopeConfig;
        $this->_country = $country;
        // $this->_productRepositoryFactory = $productRepositoryFactory;
        // $this->request = $request;
        // $this->configWriter = $configWriter;
        // $this->countryCollectionFactory = $countryCollectionFactory;
        // $this->objectManager = $objectManager;
        // $this->jsonResultFactory = $jsonResultFactory;
        $this->_countryFactory = $countryFactory;
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
    public function execute(): ProductInterface
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
    
        $productsData = [];
    
        if ($method == 'getProducts' && $actualToken == $token) {
            $productCollection = $this->productCollectionFactory->create();
            $productCollection->addAttributeToSelect([
                array('*')
            ]);
            $productCollection->setPageSize($count);
            $productCollection->setCurPage($offset);
    
            if ($details == 0) {
                foreach ($productCollection as $product) {
                    $countryName = $product->getAttributeText('country_of_manufacture');

                    $manufacturer = $this->getCountryCodeByFullName($countryName);
    
                    $productData = new \Dev\RestApi\Model\Data\Product();
                    $productData->setSku($product->getSku());
                    $productData->setUrl($product->getUrlKey());
                    $productData->setManufacturer($manufacturer);
                    $productData->setModel($product->getModel());
                    $productData->setEan($product->getEan());
                    $productData->setPrice($product->getPrice());
                    $productData->setAvailability($product->getIsSalable() ? 'InStock' : 'OutOfStock');
                    $productData->setItemsAvailable($product->getQty());
                    $productData->setUpdateAt($product->getUpdatedAt());

                    $productsData['prods'][] = $productData;
                }
            }

            return $productData;
        } else {
            return []; 
        }
    }
    
}
