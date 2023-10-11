<?php
namespace Dev\RestApi\Api;

interface ProductRepositoryInterface
{
    /**
     * Get a list of products.
     * @return string
     */
    public function execute();


    // /**
    //  * Get a list of products.
    // * @return string
    // */
    // public function getProducts($details, $offset, $count);

    // /**
    //   * Get a list of products.
    //   * @return string
    //  */
    // public function getProductsBySku($details, $skuArray, $offset, $count);

    // /**
    //   * Get a list of products.
    //   * @return string
    //  */
    // public function getCountryCodeByFullName($countryName);

}