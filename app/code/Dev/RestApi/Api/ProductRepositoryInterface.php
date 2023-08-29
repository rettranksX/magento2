<?php
namespace Dev\RestApi\Api;

interface ProductRepositoryInterface
{
    /**
     * Get a list of products.
     *
     * @param int $details
     */
    public function getProducts(int $details);

}