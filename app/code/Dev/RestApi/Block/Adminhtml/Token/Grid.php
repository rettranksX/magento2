<?php

namespace Dev\RestApi\Block\Adminhtml\Token;

use Magento\Backend\Block\Widget\Grid as WidgetGrid;

class Grid extends WidgetGrid
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('tokenGrid');
        $this->setDefaultSort('token_id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->_tokenFactory->create()->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'token_id',
            [
                'header' => __('Token ID'),
                'index' => 'token_id',
                'type' => 'number',
            ]
        );

        $this->addColumn(
            'token',
            [
                'header' => __('Token'),
                'index' => 'token',
            ]
        );

        return parent::_prepareColumns();
    }
}
