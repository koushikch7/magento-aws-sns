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
namespace CHK\AmazonSNS\Block;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class AmazonSNS extends \Magento\Customer\Block\Account\Dashboard
{

    protected $_template = 'form/sendsms.phtml';

    public function getIsSubscribed()
    {
        if (!$this->getCustomer()->getCustomAttribute('sms_subscription_status')) {
            return false;
        }
        else return $this->getCustomer()->getCustomAttribute('sms_subscription_status')->getValue();
    }

    public function getAction()
    {
        return $this->getUrl('sendsms/manage/save');
    }
}
