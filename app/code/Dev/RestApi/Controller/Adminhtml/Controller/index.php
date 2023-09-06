<?php

namespace Dev\RestApi\Controller\Adminhtml\Controller;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Dev Module Page'));

        $block = $resultPage->getLayout()->getBlock('content');
        if ($block) {
            $block->setTemplate('Dev_RestApi::token/form.phtml');
        }

        return $resultPage;
    }
}


// namespace Dev\RestApi\Controller\Adminhtml\Controller;

// use Magento\Backend\App\Action;
// use Magento\Backend\App\Action\Context;
// use Magento\Framework\App\Action\HttpGetActionInterface;
// use Magento\Framework\View\Result\Page;
// use Magento\Framework\View\Result\PageFactory;

// /**
//  * Class Index
//  */
// class Index extends Action implements HttpGetActionInterface
// {
//     const MENU_ID = 'Dev_RestApi::dev_module_PriceInfo_Module';

//     /**
//      * @var PageFactory
//      */
//     protected $resultPageFactory;

//     /**
//      * Index constructor.
//      *
//      * @param Context $context
//      * @param PageFactory $resultPageFactory
//      */
//     public function __construct(
//         Context $context,
//         PageFactory $resultPageFactory
//     ) {
//         parent::__construct($context);

//         $this->resultPageFactory = $resultPageFactory;
//     }

//     /**
//      * Load the page defined in view/adminhtml/layout/token_index.xml
//      *
//      * @return Page
//      */
//     public function execute()
//     {
//         $resultPage = $this->resultPageFactory->create();
//         $resultPage->setActiveMenu(static::MENU_ID);
//         $resultPage->getConfig()->getTitle()->prepend(__('Dev Module Page'));

//         return $resultPage;
//     }
// }
