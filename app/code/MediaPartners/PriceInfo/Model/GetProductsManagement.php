<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MediaPartners\PriceInfo\Model;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Webapi\Rest\Request;
use MediaPartners\PriceInfo\Api\GetProductsManagementInterface;
use MediaPartners\PriceInfo\Api\ProductsInterface;
use MediaPartners\PriceInfo\Helper\ProductAttributeTypes;
use MediaPartners\PriceInfo\Api\ProductsInterfaceFactory;

class GetProductsManagement implements GetProductsManagementInterface
{
    public $collectionProductLastId = null;

    public function __construct(
        private readonly Request $request,
        private readonly Json $json,
        private readonly CollectionFactory $collectionFactory,
        private readonly ProductAttributeTypes $productAttributeTypesHelper,
        private readonly ProductsInterfaceFactory $productsInterfaceFactory,
    ) {
    }


    public function execute(): ProductsInterface
    {
        $requestBody = $this->request->getContent();
        $result = $this->productsInterfaceFactory->create();

        if ($requestBody && $body = $this->json->unserialize($requestBody)) {
            if ($body['method'] === 'getProducts') {
                $result->setProds($this->getProducts($body));
                $result->setLastId($this->collectionProductLastId);

                return $result;
            }

            if ($body['method'] === 'getProductsBySku') {
                $result->setProds($this->getProductsBySku($body));
                $result->setLastId($this->collectionProductLastId);

                return $result;
            }
        }

        return $result->setProds([]);
    }

    public function getProducts($body)
    {
        $collection = $this->collectionFactory->create();

        if ($body['details'] === 0) {
            foreach (ProductAttributeTypes::PRODUCT_DETAILED_0 as $attribute) {
                $collection->addAttributeToSelect($attribute);
            }
        }

        if ($body['details'] === 1) {

        }

        $collection->setPageSize($body['count']);
        $collection->setCurPage($body['offset']);

        $collection->load();
        $data = [];

        foreach ($collection->getItems() as $item) {
            $data[] = $this->productAttributeTypesHelper->getRequestedData($item->getData());
        }

        $this->collectionProductLastId = $collection->getLastItem()->getId();

        return $data;
    }

    public function getProductsBySku($body)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('sku', ['in' => $body['sku']]);

        if ($body['details'] === 0) {
            foreach (ProductAttributeTypes::PRODUCT_DETAILED_0 as $attribute) {
                $collection->addAttributeToSelect($attribute);
            }
        }

        $collection->load();
        $data = [];

        foreach ($collection->getItems() as $item) {
            $data[] = $this->productAttributeTypesHelper->getRequestedData($item->getData());
        }

        $this->collectionProductLastId = $collection->getLastItem()->getId();

        return $data;
    }
}
