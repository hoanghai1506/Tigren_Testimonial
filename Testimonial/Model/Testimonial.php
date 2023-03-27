<?php
namespace Tigren\Testimonial\Model;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Testimonial extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'testimonial';
    protected $_cacheTag = 'tigren_testimonial_testimonial';
    protected $_eventPrefix = 'tigren_testimonial_testimonial';

    protected function _construct()
    {
        $this->_init('Tigren\Testimonial\Model\ResourceModel\Testimonial');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
