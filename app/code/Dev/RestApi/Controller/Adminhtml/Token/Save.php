<?php


namespace Dev\RestApi\Controller\Adminhtml\Token;

use Magento\Backend\App\Action;
use Dev\RestApi\Model\TokenFactory;

class Save extends Action
{
    protected $tokenFactory;

    public function __construct(
        Action\Context $context,
        TokenFactory $tokenFactory
    ) {
        parent::__construct($context);
        $this->tokenFactory = $tokenFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            try {
                $tokenModel = $this->tokenFactory->create();
                $tokenModel->setData($data);
                $tokenModel->save();

                $this->messageManager->addSuccessMessage(__('Token has been saved.'));
                $this->_redirect('*/*/index');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                echo 'TEST';
            }
        }

        $this->_redirect('*/*/index');
    }
}
