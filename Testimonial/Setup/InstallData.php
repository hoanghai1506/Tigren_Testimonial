<?php

namespace Tigren\Testimonial\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {
    private $customerSetupFactory;

    public function __construct( CustomerSetupFactory $customerSetupFactory ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    public function install( ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {
        $setup->startSetup();

        $customerSetup = $this->customerSetupFactory->create( [ 'setup' => $setup ] );

        $customerSetup->addAttribute( Customer::ENTITY, 'is_created_testimonial', [
            'type'                     => 'int',
            'label'                    => 'Is Created Testimonial',
            'input'                    => 'boolean',
            'source'                   => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'required'                 => false,
            'default'                  => 0,
            'visible'                  => true,
            'user_defined'             => true,
            'system'                   => false,
            'position'                 => 100,
            'sort_order'               => 100,
            'is_used_in_grid'          => true,
            'is_visible_in_grid'       => true,
            'is_filterable_in_grid'    => true,
            'is_searchable_in_grid'    => true,
            'is_filterable'            => true,
            'is_searchable'            => true,
            'is_html_allowed_on_front' => false,
            'visible_on_front'         => false,
            'used_in_product_listing'  => true,
            'apply_to'                 => ''
        ] );

        $attribute = $customerSetup->getEavConfig()->getAttribute( Customer::ENTITY, 'is_created_testimonial' )
                                   ->addData( [
                                       'attribute_set_id'   => 1,
                                       'attribute_group_id' => 1,
                                       'used_in_forms'      => [ 'adminhtml_customer' ],
                                   ] );

        $attribute->save();

        $setup->endSetup();
    }
}
