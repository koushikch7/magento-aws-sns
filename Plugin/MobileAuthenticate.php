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
namespace CHK\AmazonSNS\Plugin;

use CHK\AmazonSNS\Helper\Data;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Class MobileAuthenticate
 * @package CHK\AmazonSNS\Plugin
 */
class MobileAuthenticate
{
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param Data $helper
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        Data $helper
    ) {
        $this->_helper = $helper;
        $this->customerRepository =$customerRepository;
    }

    /**
     * @param \Magento\Customer\Model\AccountManagement $subject
     * @param $username
     * @param $password
     * @return array|null
     */
    // @codingStandardsIgnoreStart
    public function beforeAuthenticate(\Magento\Customer\Model\AccountManagement $subject,$username, $password)
    // @codingStandardsIgnoreStart 
    {
        if ($this->_helper->checkExistingEmail($username))
        {
            return null;
        }
        $email =$this->_helper->getEmailByMobile($username);
        if ($email)
        {
            return[$email,$password];
        }else
        {
            return null;
        }
    }
}