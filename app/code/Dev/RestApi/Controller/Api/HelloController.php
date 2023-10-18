<?php
namespace Dev\RestApi\Controller\Api;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\Json;
use Dev\RestApi\Api\HelloInterface;

class HelloController extends Action
{
    protected $helloInterface;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        HelloInterface $helloInterface
    ) {
        parent::__construct($context);
        $this->helloInterface = $helloInterface;
    }

    public function execute()
    {
        $jsonData = $this->helloInterface->getHelloMessage();
        
        $result = $this->resultFactory->create(Json::class);
        $result->setData(['data' => $jsonData]);

        $result->setHeader('Content-Type', 'application/json');
        
        return $result;
    }
}
