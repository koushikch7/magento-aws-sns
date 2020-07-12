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
namespace CHK\AmazonSNS\Block\Account;

use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session as CustomerSession;

class MobileNumber extends \Magento\Framework\View\Element\Template
{

   
    protected $helper;

    protected $customerFactory;

    protected $_customerSession;

    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \CHK\AmazonSNS\Helper\Data $dataHelper,
        CustomerSession $customerSession,
        CustomerFactory $customerFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_customerSession = $customerSession;
        $this->helper = $dataHelper;
        $this->customerFactory = $customerFactory;

    }

   
    public function isMobileRequired()
    {
        return $this->helper->getIsMobileInputRequire();

    }

    public function getAccountInfo()
    {
        $customerInfo = $this->customerFactory->create()->load($this->getCustomerId());
        return $customerInfo->getMobileNumbers();
    }

    
    public function getCustomerId()
    {
        $customerId = $this->_customerSession->getCustomerId();

        return $customerId;
    }
}
