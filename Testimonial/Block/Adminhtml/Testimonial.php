<?php
namespace Tigren\Testimonial\Block\Adminhtml;

class Testimonial extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {
        $this->_controller = 'adminhtml_testimonial';
        $this->_blockGroup = 'Tigren_Testimonial';
        $this->_headerText = __('Testimonial');
        $this->_addButtonLabel = __('Create New Post');
        parent::_construct();
    }
}
