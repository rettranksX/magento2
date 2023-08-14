<?php
namespace Dev\RestApi\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Dev\RestApi\Model\ProductModelFactory;

class Index extends Action
{
    protected $productCollectionFactory;
    protected $productModelFactory;

    public function __construct(
        Context $context,
        ProductCollectionFactory $productCollectionFactory,
        ProductModelFactory $productModelFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productModelFactory = $productModelFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $method = $this->getRequest()->getParam('method');
        $details = $this->getRequest()->getParam('details');
        $offset = $this->getRequest()->getParam('offset');
        $count = $this->getRequest()->getParam('count');

        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect([
            'sku',
            'country_of_manufacture',
            'model',
            'ean',
            'price',
            'is_salable',
            'qty',
            'category_ids',
            'name',
            'description',
            'updated_at',
            'url_key'
        ]);
        $productCollection->setPageSize($count);
        $productCollection->setCurPage($offset);

        $productsData = [];

        if ($details == 0) {
            foreach ($productCollection as $product) {
                $productModel = $this->productModelFactory->create();
                $productModel->setData([
                    'sku' => $product->getSku(),
                    'url' => $product->getUrlKey(),
                    'manufacturer' => $product->getAttributeText('country_of_manufacture'),
                    'model' => $product->getModel(),
                    'ean' => $product->getEan(),
                    'price' => $product->getPrice(),
                    'availability' => $product->isSalable() ? 'InStock' : 'OutOfStock',
                    'itemsAvailable' => $product->getQty(),
                    'updated' => $product->getUpdatedAt(),
                ]);

                $productsData[] = $productModel->getData();
            }
        }

        $lastProductId = $productCollection->getLastItem()->getId();

        $result = [
            'prods' => $productsData,
            'lastId' => $lastProductId,
        ];

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(json_encode($result));
    }
}
