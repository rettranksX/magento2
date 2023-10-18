<?php
namespace Dev\RestApi\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends Action
{
    protected $jsonResultFactory;

    public function __construct(Context $context, JsonFactory $jsonResultFactory)
    {
        parent::__construct($context);
        $this->jsonResultFactory = $jsonResultFactory;
    }

    public function execute()
    {
        $data = ['message' => 'Hello, Magento API!'];

        $result = $this->jsonResultFactory->create();
        $result->setData($data);

        return $result;
    }
}
