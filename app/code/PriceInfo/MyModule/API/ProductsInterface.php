<?php

namespace PriceInfo\MyModule\Api;

class Products implements ProductsInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProducts()
    {
        echo 'hi';
    }
}
