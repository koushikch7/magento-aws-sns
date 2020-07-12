<?php
/**
 * Copyright © 2019 CHK. All rights reserved.
 * See COPYING.txt for license details.
 *
 * CHK_AmazonSNS extension
 * NOTICE OF LICENSE
 *
 * @category SMS
 * @package  CHK_AmazonSNS
 * @author   Koushik CH <info@chkoushik.com>
 */
namespace CHK\AmazonSNS\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package CHK\AmazonSNS\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
                                CustomerSetupFactory $customerSetupFactory,
                                AttributeSetFactory $attributeSetFactory
                           )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    // @codingStandardsIgnoreStart
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    // @codingStandardsIgnoreStart
    {
        $installer = $setup;
        $installer->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'mobile_numbers',
            array(
                'type'     => 'varchar',
                'backend'  => '',
                'label'    => 'Mobile Number',
                'input'    => 'text',
                'source'   => '',
                'visible'  => true,
                'required' => false,
                'default'  => '',
                'frontend' => '',
                'unique'   => false,
                'note'     => '',
            )
        );
        $my_attribute    = $customerSetup->getEavConfig()->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'mobile_numbers');
        $used_in_forms[] = 'adminhtml_customer';
        $used_in_forms[] = 'checkout_register';
        $used_in_forms[] = 'customer_account_create';
        $used_in_forms[] = 'customer_account_edit';
        $used_in_forms[] = 'adminhtml_checkout';
        $used_in_forms[] = 'sendsms_manage_index';
        $used_in_forms[] = 'sendsms_manage_save';
        $my_attribute->setData('used_in_forms', $used_in_forms)->setData('is_used_for_customer_segment', true)->setData('is_system', 0)
            ->setData('is_user_defined', 1)->setData('is_visible', 1)->setData('sort_order', 100) ->setData("is_used_in_grid", 1)
            ->setData("is_visible_in_grid", 1)
            ->setData("is_filterable_in_grid", 1)
            ->setData("is_searchable_in_grid", 1);
        $my_attribute->save();
        
        $customerSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'sms_subscription_status',
            array(
                'type'     => 'varchar',
                'backend'  => '',
                'label'    => 'Sms subscription status',
                'input'    => 'text',
                'source'   => '',
                'visible'  => true,
                'required' => false,
                'default'  => '1',
                'frontend' => '',
                'unique'   => false,
                'note'     => '',
            )
        );
        $my_attribute1    = $customerSetup->getEavConfig()->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'sms_subscription_status');
        $my_attribute1->setData('used_in_forms', $used_in_forms)->setData('is_used_for_customer_segment', true)->setData('is_system', 0)
            ->setData('is_user_defined', 1)->setData('is_visible', 1)->setData('sort_order', 110) ->setData("is_used_in_grid", 1)
            ->setData("is_visible_in_grid", 1)
            ->setData("is_filterable_in_grid", 1)
            ->setData("is_searchable_in_grid", 1);
        $my_attribute1->save();
        /**
         * Install eav entity types to the eav/entity_type table
         */

        $installer->endSetup();
    }
}