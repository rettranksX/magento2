<?php
namespace Dev\RestApi\Model;

use Magento\Framework\Model\AbstractModel;

class Token extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Dev\RestApi\Model\ResourceModel\Token');
    }
}
