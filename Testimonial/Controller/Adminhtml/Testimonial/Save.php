<?php


namespace Tigren\Testimonial\Controller\Adminhtml\Testimonial;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action {
    protected $_fileUploaderFactory;
    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor        = $dataPersistor;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        parent::__construct( $context );
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        dd($this->getRequest()->getPostValue());
        $data           = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        // save Profile_image
        $image = $data['Profile_image'][0];
        if ( isset( $image['name'] ) && $image['name'] != '' ) {
            try {
                $uploader = $this->_fileUploaderFactory->create( [ 'fileId' => 'Profile_image' ] );
                $uploader->setAllowedExtensions( [ 'jpg', 'jpeg', 'gif', 'png' ] );
                $uploader->setAllowRenameFiles( true );
                $uploader->setFilesDispersion( true );
                $path = $this->_objectManager->get( 'Magento\Store\Model\StoreManagerInterface' )
                                             ->getStore()
                                             ->getBaseUrl( \Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
                $result = $uploader->save( $path . 'Profile_image' );
                $data['Profile_image'] = $result['file'];
            } catch ( \Exception $e ) {
                $data['Profile_image'] = $image['name'];
            }
        } else {
            if ( isset( $image['value'] ) ) {
                $data['Profile_image'] = $image['value'];
            } else {
                $data['Profile_image'] = '';
            }
        }


        // save data
        if ( $data ) {
            $id    = $this->getRequest()->getParam( 'testimonial_id' );
            $model = $this->_objectManager->create( \Tigren\Testimonial\Model\Testimonial::class )->load( $id );
            if ( ! $model->getId() && $id ) {
                $this->messageManager->addErrorMessage( __( 'This Testimonial no longer exists.' ) );

                return $resultRedirect->setPath( '*/*/' );
            }

            $model->setData( $data );

            try {
                $model->save();
                $this->messageManager->addSuccessMessage( __( 'You saved the Testimonial.' ) );
                $this->dataPersistor->clear( 'tigren_testimonial_testimonial' );

                if ( $this->getRequest()->getParam( 'back' ) ) {
                    return $resultRedirect->setPath( '*/*/edit', [ 'testimonial_id' => $model->getId() ] );
                }

                return $resultRedirect->setPath( '*/*/' );
            } catch ( LocalizedException $e ) {
                $this->messageManager->addErrorMessage( $e->getMessage() );
            } catch ( \Exception $e ) {
                $this->messageManager->addExceptionMessage( $e, __( 'Something went wrong while saving the Testimonial.' ) );
            }

            $this->dataPersistor->set( 'tigren_testimonial_testimonial', $data );

            return $resultRedirect->setPath( '*/*/edit', [ 'testimonial_id' => $this->getRequest()->getParam( 'testimonial_id' ) ] );
        }
        return $resultRedirect->setPath( '*/*/' );

    }


}


