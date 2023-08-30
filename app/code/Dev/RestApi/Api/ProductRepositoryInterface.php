<?php
namespace Dev\RestApi\Api;

interface ProductRepositoryInterface
{
    /**
     * Get a list of products.
     * @return string
     */
    public function execute();

}