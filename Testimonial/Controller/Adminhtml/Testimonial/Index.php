<?php

namespace Tigren\Testimonial\Controller\Adminhtml\Testimonial;

class Index extends \Magento\Backend\App\Action
{

    protected $resultPageFactory = false;

    public function __construct(

        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Testimonial')));

        return $resultPage;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_Testimonial::menu');
    }


}
