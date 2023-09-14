<?php

namespace Dev\RestApi\Model\ResourceModel\Token;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dev\RestApi\Model\Token as TokenModel;
use Dev\RestApi\Model\ResourceModel\Token as TokenResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'token_id';

    protected function _construct()
    {
        $this->_init(TokenModel::class, TokenResourceModel::class);
    }
}
