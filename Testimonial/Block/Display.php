<?php
namespace Tigren\Testimonial\Block;
use Magento\Framework\Data\Form\FormKey;
class Display extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context , FormKey $formKey)
    {
        $this->formKey = $formKey;
        parent::__construct($context);
    }
    public function getTestimonial()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $testimonial = $objectManager->create('Tigren\Testimonial\Model\Testimonial')->getCollection();
        return $testimonial;
    }
    public function getFormAction()
    {
        return $this->getUrl('testimonial/index/save');
    }
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }
}
