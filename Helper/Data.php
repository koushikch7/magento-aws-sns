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

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;

/**
 * Class Data
 * @package CHK\AmazonSNS\Helper
 */
class Data extends AbstractHelper
{

    const XML_PATH_CONFIG_STATUS = 'chk/mobile_login_option/status';

    const XML_PATH_CONFIG_MOBILE_LOGIN = 'chk/mobile_login_option/mobile_login_enabled';

    const XML_PATH_CONFIG_MOBILE_ENABLE = 'chk/mobile_login_option/enable';
    
    const XML_PATH_CONFIG_MOBILE_REQUIRED = 'chk/mobile_login_option/is_required';


    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var CollectionFactory
     */
    protected $_customersFactory;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManagerInterface
     * @param CollectionFactory $customersFactory
     * @param CustomerFactory $customerFactory
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManagerInterface,
        CollectionFactory $customersFactory,
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
     * @return RequestInterface
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
     * @throws LocalizedException
     */
    public function getEmailByMobile($mobile)
    {
        return $this->_customersFactory->create()->addAttributeToFilter('mobile_numbers',$mobile)->getFirstItem()->getEmail();
    }

    /**
     * @param $email
     * @return bool
     * @throws LocalizedException
     */
    public function checkExistingEmail($email)
    {
        /**
         * @var Customer $customer
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