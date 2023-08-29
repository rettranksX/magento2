<?php
namespace Dev\RestApi\Api;

interface ProductRepositoryInterface
{
    /**
     * Get a list of products.
     *
     * @param int $details
     * @return string
     */
    public function getProducts(int $details): string;

}