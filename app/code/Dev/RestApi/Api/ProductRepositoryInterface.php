<?php
namespace Dev\RestApi\Api;

interface ProductRepositoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \Dev\RestApi\Api\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id);
    /**
     * Set descriptions for the products.
     *
     * @param \Dev\RestApi\Api\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products);


    /**
     * Get a list of products.
     *
     * @param int $details
     * @return string
     */
    public function getProducts(int $details): string;


    // /**
    //  * Get products by SKU.
    //  *
    //  * @param int $details
    //  * @return array
    //  */
    // public function getProductsBySku(int $details): array;

}