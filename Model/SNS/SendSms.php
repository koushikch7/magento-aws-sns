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
namespace CHK\AmazonSNS\Model\SNS;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class SendSms
 * @package CHK\AmazonSNS\Model\Textmarketer
 */
class SendSms
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    protected $zendClientFactory;

    /**
     * SendSms constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\HTTP\ZendClientFactory $zendClientFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\HTTP\ZendClientFactory $zendClientFactory)
    {
        $this->_scopeConfig = $scopeConfig;
        $this->zendClientFactory = $zendClientFactory;
    }
    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->_scopeConfig->getValue('sendsms/sns/enabled');
    }

    public function getSNSId()
    {
        return $this->_scopeConfig->getValue('sendsms/sns/sid');
    }

    /**
     * Getting TextmarketerSMS API Password
     * @return string
     */

    public function getSNSToken()
    {
        return $this->_scopeConfig->getValue('sendsms/sns/token');
    }

    public function getSNSPhoneNumber()
    {
        return $this->_scopeConfig->getValue('sendsms/sns/from');
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->_scopeConfig->getValue('sendsms/sns/sender');
    }

    /**
     * @return mixed
     */
    public function getContentSMS()
    {
        return $this->_scopeConfig->getValue('sendsms/sns/content');
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->_scopeConfig->getValue('sendsms/sns/number');
    }


    public function sendSMS($code, $mobiNumber)
    {
        $sid = urlencode($this->getSNSId());

        $token = urlencode($this->getSNSToken());

        $from = $this->getSNSPhoneNumber();

        $client = $this->zendClientFactory->create();

        $uri = 'https://api.sns.com/2010-04-01/Accounts/' . $sid .':'.$token. '/Messages';
        $data =
            '&To=+'.urlencode($mobiNumber).
            '&From=+'.urlencode($from).
            '&Body='.urlencode($code);

         $client->setUri($uri);

         $client->setConfig([ 'timeout' => 300]);

         $method   = \Zend_Http_Client::GET;

         $response = $client->request($method)->getBody();

         $data = json_decode($response, true);

         return $data;
    }
}
