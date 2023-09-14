<?php

namespace Dev\RestApi\Model;

use Magento\Framework\Model\AbstractModel;
use Dev\RestApi\Model\ResourceModel\Token as TokenResourceModel;

class Token extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(TokenResourceModel::class);
    }

    public function getTable()
    {
        return $this->getResource()->getTable('token_table');
    }
}
