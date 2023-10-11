<?php
namespace Dev\RestApi\Api;

interface ProductRepositoryInterface
{
    // /**
    //  * Get a list of products.
    //  * @return string
    //  */
    // public function execute();

    public function getProducts($details, $offset, $count);

    public function getProductsBySku($details, $skuArray, $offset, $count);

    public function getCountryCodeByFullName($countryName);

}