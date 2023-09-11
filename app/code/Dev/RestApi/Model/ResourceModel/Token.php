<?php
namespace Dev\RestApi\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Token extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('dev_restapi_tokens', 'token_id');
    }
}
