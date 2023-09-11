<?php
namespace Dev\RestApi\Model;

use Magento\Framework\Model\AbstractModel;
use Dev\RestApi\Api\Data\TokenInterface;
use Dev\RestApi\Model\ResourceModel\Token as TokenResourceModel;

class Token extends AbstractModel implements TokenInterface
{
    protected function _construct()
    {
        $this->_init(TokenResourceModel::class);
    }

    public function getId()
    {
        return $this->_getData(self::TOKEN_ID);
    }

    public function getTokenValue()
    {
        return $this->_getData(self::TOKEN_VALUE);
    }

    public function setTokenValue($value)
    {
        return $this->setData(self::TOKEN_VALUE, $value);
    }
}
