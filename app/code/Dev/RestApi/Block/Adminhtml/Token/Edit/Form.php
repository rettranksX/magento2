<?php

namespace Dev\RestApi\Block\Adminhtml\Token\Edit;

use Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    // 'action' => $this->getUrl('*/*/save'),
                    'action' => $this->getUrl('adminhtml/controller/save'),
                    'method' => 'post'
                ],
            ]
        );

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Token Information')]
        );

        $fieldset->addField(
            'token',
            'text',
            [
                'name' => 'token',
                'label' => __('Token'),
                'title' => __('Token'),
                'required' => true,
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
