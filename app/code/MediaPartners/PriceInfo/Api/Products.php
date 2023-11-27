<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MediaPartners\PriceInfo\Api;

use Magento\Framework\Api\AbstractSimpleObject;

class Products extends AbstractSimpleObject implements ProductsInterface
{
    const PRODS = 'prods';
    const LAST_ID = 'lastId';

    /**
     * @return array
     */
    public function getProds(): array
    {
        return $this->_get(self::PRODS) === null ? [] : $this->_get(self::PRODS);
    }

    /**
     * @param array $prods
     * @return Products
     */
    public function setProds(array $prods): Products
    {
        return $this->setData(self::PRODS, $prods);
    }

    /**
     * Get total count
     *
     * @return string
     */
    public function getLastId(): string
    {
        return $this->_get(self::LAST_ID);
    }

    /**
     * Set total count
     *
     * @param string $id
     * @return Products
     */
    public function setLastId(string $id): Products
    {
        return $this->setData(self::LAST_ID, $id);
    }
}
