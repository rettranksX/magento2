<?php


namespace Dev\RestApi\Block\Adminhtml\Token;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends Container
{
    protected $_coreRegistry = null;

    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'token_id';
        $this->_blockGroup = 'Dev_RestApi';
        $this->_controller = 'adminhtml_token';

        parent::_construct();
    }

    protected function _prepareLayout()
    {
        $this->addChild(
            'form',
            'Dev\RestApi\Block\Adminhtml\Token\Edit\Form'
        );

        return parent::_prepareLayout();
    }
}
