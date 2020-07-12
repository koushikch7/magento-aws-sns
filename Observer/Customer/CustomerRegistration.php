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
namespace CHK\AmazonSNS\Observer\Customer;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use CHK\AmazonSNS\Model\SNS\SendSms as SNS;
use CHK\AmazonSNS\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class CustomerRegistration
 * @package CHK\AmazonSNS\Observer\Customer
 */
class CustomerRegistration implements ObserverInterface
{

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var SNS
     */
    protected $_SNS;


    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param SNS $SNS
     * @param Data $helperData
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SNS $SNS,
        Data $helperData
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_SNS = $SNS;
    }

    /**
     * Handler for 'customer_logout' event.
     *
     * @param  Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var CustomerInterface $customer */
        $customer = $observer->getEvent()->getCustomer();
        $customerEmail = $customer->getEmail();
        $customerName = $customer->getFirstname();
        $mobile_number = $customer->getCustomAttribute('mobile_numbers')->getValue();
        if ($this->_scopeConfig->getValue('chk/content/enabled_register')) {
            $content = $this->_scopeConfig
            ->getValue('chk/content/customer_register');
        }
        $status = $this->_scopeConfig
        ->getValue('chk/mobile_login_option/status');
        $replaceString = [
            '{{customer_name}}' => $customerName,
            '{{customer_email}}' => $customerEmail
        ];
        if ($mobile_number && isset($content) && $status) {
            $newContent = strtr($content, $replaceString);
            if ($this->_SNS->isEnabled()) {
                $this->_SNS->sendSMS($newContent, $mobile_number);
            }
        }

    }
}