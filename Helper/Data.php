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
namespace CHK\AmazonSNS\Helper;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;

/**
 * Class Data
 * @package CHK\AmazonSNS\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_PATH_CONFIG_STATUS = 'sendsms/mobile_login_option/status';

    const XML_PATH_CONFIG_MOBILE_LOGIN = 'sendsms/mobile_login_option/mobile_login_enabled';

    const XML_PATH_CONFIG_MOBILE_ENABLE = 'sendsms/mobile_login_option/enable';
    
    const XML_PATH_CONFIG_MOBILE_REQUIRED = 'sendsms/mobile_login_option/is_required';


    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $_customersFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManagerInterface
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customersFactory
     * @param CustomerFactory $customerFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManagerInterface,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customersFactory,
        CustomerFactory $customerFactory
    )
    {

        $this->_customersFactory = $customersFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        $this->customerFactory = $customerFactory;
        $this->_storeManager = $storeManagerInterface;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_CONFIG_STATUS);
    }
    /**
     * @return bool
     */
    public function getIsEnableMobileInput()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_CONFIG_MOBILE_ENABLE);
    }
    public function getIsEnableMobileLogin()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_CONFIG_MOBILE_LOGIN);
    }
    /**
     * @return \Magento\Framework\App\RequestInterface
     */
    public function getRequest()
    {
        return $this->_request;

    }

    /**
     * @return bool
     */
    public function getIsMobileInputRequire()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_CONFIG_MOBILE_REQUIRED);
    }

    /**
     * @param $mobile
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEmailByMobile($mobile)
    {
        /**
         * @var \Magento\Customer\Model\Customer $customer
         */
        $mobile = '8880010730';
        return $this->_customersFactory->create()->addAttributeToFilter('mobile_numbers',$mobile)->getFirstItem()->getEmail();
    }

    /**
     * @param $email
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkExistingEmail($email)
    {
        /**
         * @var \Magento\Customer\Model\Customer $customer
         */
        // @codingStandardsIgnoreStart
        $customer = $this->_customersFactory->create()->addAttributeToFilter(
            'email',
            $email
        )->getFirstItem();
        // @codingStandardsIgnoreStart
        if ($customer->getEmail()) {
            return true;
        }
        return false;
    }
}