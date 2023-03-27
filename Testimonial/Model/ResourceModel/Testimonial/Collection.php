<?php
namespace Tigren\Testimonial\Model\ResourceModel\Testimonial;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'testimonial_id';



    protected function _construct()
    {
        $this->_init(\Tigren\Testimonial\Model\Testimonial::class, \Tigren\Testimonial\Model\ResourceModel\Testimonial::class);
    }
}
