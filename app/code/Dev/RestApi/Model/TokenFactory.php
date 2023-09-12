<?php

namespace Dev\RestApi\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollectionFactory;

class TokenFactory
{
    protected $collectionFactory;
    protected $modelFactory;

    public function __construct(
        AbstractCollectionFactory $collectionFactory,
        AbstractModelFactory $modelFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->modelFactory = $modelFactory;
    }

    public function create()
    {
        return $this->modelFactory->create();
    }

    public function createCollection()
    {
        return $this->collectionFactory->create();
    }
}
