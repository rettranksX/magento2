<?php

namespace Dev\RestApi\Controller;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;
use Dev\RestApi\Model\YourModel;

class YourController extends Action
{
    protected $jsonResultFactory;
    protected $yourModel;

    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        YourModel $yourModel
    ) {
        parent::__construct($context);
        $this->jsonResultFactory = $jsonResultFactory;
        $this->yourModel = $yourModel;
    }

    public function execute()
    {
        $data = $this->yourModel->yourMethod();
        $result = $this->jsonResultFactory->create();
        $result->setData($data);

        return $result;
    }
}
