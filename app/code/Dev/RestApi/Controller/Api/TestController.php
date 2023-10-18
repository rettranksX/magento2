<?php

namespace Dev\RestApi\Controller;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Action;
use Dev\RestApi\Model\TestModel;

class TestController extends Action
{
    protected $jsonResultFactory;
    protected $testModel;

    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        YourModel $testModel
    ) {
        parent::__construct($context);
        $this->jsonResultFactory = $jsonResultFactory;
        $this->testModel = $testModel;
    }

    public function execute()
    {
        $jsonData = $this->testModel->testMethod();
        $jsonResponse = json_encode($jsonData);
        
        $response = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        $response->setData(['result' => $jsonResponse]);
        
        return $response;
        
    }
}
