<?php

namespace Dev\RestApi\Controller\Adminhtml\Token;

use Magento\Backend\App\Action;

class Index extends Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}