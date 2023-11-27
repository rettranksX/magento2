<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MediaPartners\PriceInfo\Api;

use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Api\AbstractExtensibleObject;

interface ProductsInterface
{
    /**
     * @return array
     */
    public function getProds(): array;

    /**
     * @param array $prods
     * @return ProductsInterface
     */
    public function setProds(array $prods): ProductsInterface;

    /**
     * @return string
     */
    public function getLastId(): string;

    /**
     * Set total count.
     *
     * @param string $id
     * @return ProductsInterface
     */
    public function setLastId(string $id): ProductsInterface;
}
