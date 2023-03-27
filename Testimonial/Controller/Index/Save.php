<?php


namespace Tigren\Testimonial\Controller\Index;


use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Action\Context;
class Save extends \Magento\Framework\App\Action\Action {
    protected $_fileUploaderFactory;
    protected $dataPersistor;


    /**
     * @param UploaderFactory $fileUploaderFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        parent::__construct( $context );
        $this->dataPersistor        = $dataPersistor;
        $this->_fileUploaderFactory = $fileUploaderFactory;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $data           = $this->getRequest()->getPostValue();
        $parameters = new \Laminas\Stdlib\Parameters($_FILES);
        $image = $parameters->get('Profile_image')['name'];
        $resultRedirect = $this->resultRedirectFactory->create();

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
                $fullPath = $path . 'Profile_image' . $result['file'];
                $tempPath = $result['tmp_name'];
                move_uploaded_file($tempPath, $fullPath);$fullPath = $path . 'Profile_image' . $result['file'];
                $tempPath = $result['tmp_name'];
                move_uploaded_file($tempPath, $fullPath);
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

                return $resultRedirect->setPath( '*/*/create' );
            }

            $model->setData( $data );

            try {
                $model->save();
                $this->messageManager->addSuccessMessage( __( 'You saved the Testimonial.' ) );
                $this->dataPersistor->clear( 'tigren_testimonial_testimonial' );

                if ( $this->getRequest()->getParam( 'back' ) ) {
                    return $resultRedirect->setPath( '*/*/create', [ 'testimonial_id' => $model->getId() ] );
                }

                return $resultRedirect->setPath( '*/*/create' );
            } catch ( LocalizedException $e ) {
                $this->messageManager->addErrorMessage( $e->getMessage() );
            } catch ( \Exception $e ) {
                $this->messageManager->addExceptionMessage( $e, __( 'Something went wrong while saving the Testimonial.' ) );
            }
//
            $this->dataPersistor->set( 'tigren_testimonial_testimonial', $data );

            return $resultRedirect->setPath( '*/*/create', [ 'testimonial_id' => $this->getRequest()->getParam( 'testimonial_id' ) ] );
        }
        return $resultRedirect->setPath( '*/*/create' );






    }


}



