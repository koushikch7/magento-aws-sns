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

use Aws\Exception\AwsException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Aws\Sns\SnsClient;
use Psr\Log\LoggerInterface;

/**
 * Class SendSms
 * @package CHK\AmazonSNS\Model\SNS
 */
class SendSms
{
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * SendSms constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }
    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->_scopeConfig->getValue('chk/sns/enabled');
    }

    /**
     * @return mixed
     */
    protected function getSNSRegion()
    {
        return $this->_scopeConfig->getValue('chk/sns/region');
    }

    /**
     * @return mixed
     */
    protected function getSNSKey()
    {
        return $this->_scopeConfig->getValue('chk/sns/sid');
    }

    /**
     * Getting AmazonSNS API Password
     * @return string
     */

    protected function getSNSSecretKey()
    {
        return $this->_scopeConfig->getValue('chk/sns/token');
    }

    /**
     * @return mixed
     */
    protected function getSender()
    {
        return $this->_scopeConfig->getValue('chk/sns/sender');
    }

    /**
     * @return mixed
     */
    public function getContentSMS()
    {
        return $this->_scopeConfig->getValue('chk/sns/content');
    }

    /**
     * @return mixed
     */
    protected function getNumber()
    {
        return $this->_scopeConfig->getValue('chk/sns/number');
    }

    /**
     * @return mixed
     */
    protected function getVersion()
    {
        return $this->_scopeConfig->getValue('chk/sns/version');
    }

    /**
     * @param $code
     * @param $mobileNumber
     * @return mixed|string
     */
    public function sendSMS($code, $mobileNumber)
    {
        if($this->isEnabled()) {
            $config = array(
                'region' => $this->getSNSRegion(),
                'version' => $this->getVersion()?$this->getVersion():'latest',
                'credentials' => array('key' => $this->getSNSKey(), 'secret' => $this->getSNSSecretKey()),
            );
            $senderId = $this->getSender() ? $this->getSender() :'CHKAWSSNS';
            try {
                $amazonSNS = new SnsClient($config);

                return $amazonSNS->publish([
                    'Message' => $code,
                    'PhoneNumber' => $mobileNumber,
                    'MessageAttributes' => array(
                        'AWS.SNS.SMS.SenderID' => array(
                            'DataType' => 'String',
                            'StringValue' => $senderId,
                        )
                    )
                ]);
            } catch (AwsException $e) {
                $this->logger->error($e->getMessage());
                return $e->getMessage();
            }
        }
        return __('Please Enable and Save the Configure to Proceed');
    }
}
