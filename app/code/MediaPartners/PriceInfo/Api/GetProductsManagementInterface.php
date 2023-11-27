<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MediaPartners\PriceInfo\Api;

use Magento\Framework\Controller\ResultInterface;
use MediaPartners\PriceInfo\Api\ProductsInterface;

interface GetProductsManagementInterface
{

    /**
     * POST for GetProducts api
     * @return ProductsInterface
     */
    public function execute(): ProductsInterface;
}

