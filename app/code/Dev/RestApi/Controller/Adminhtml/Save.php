<?php
namespace Dev\RestApi\Controller\Adminhtml\Token;

use Magento\Backend\App\Action;
use Dev\RestApi\Api\TokenRepositoryInterface;

class Save extends Action
{
    private $tokenRepository;

    public function __construct(
        Action\Context $context,
        TokenRepositoryInterface $tokenRepository
    ) {
        parent::__construct($context);
        $this->tokenRepository = $tokenRepository;
    }

    public function execute()
    {
        $tokenValue = $this->getRequest()->getParam('token_value');
        if ($tokenValue) {
            $this->tokenRepository->save($tokenValue);
            $this->messageManager->addSuccessMessage(__('Token has been saved.'));
        } else {
            $this->messageManager->addErrorMessage(__('Token could not be saved.'));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
