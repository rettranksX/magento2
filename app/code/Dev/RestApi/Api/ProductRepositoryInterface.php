<?php
namespace Dev\RestApi\Api;

interface ProductRepositoryInterface
{
    /**
     * Get a list of products.
     *
     * @param int $details
     * @return object
     */
    public function execute();

}