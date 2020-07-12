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
namespace CHK\AmazonSNS\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use CHK\AmazonSNS\Model\SNS\SendSms as SNS;
use Magento\Framework\Controller\Result\JsonFactory;

class Check extends Action
{
    protected $_connector;

    protected $_SNS;
   
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        SNS $SNS,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->_SNS = $SNS;
        $this->resultJsonFactory = $jsonFactory;
    }

    
    public function errorResult()
    {
        $result['error'] = 1;
        $result['description'] = 'Invalid username or password';
        return $result;
    }

    public function sucessResult()
    {
        $result['error'] = 0;
        $result['description'] = 'Connected success';
        return $result;
    }

    public function checkSNS($message,$number)
    {
        $response = $this->_SNS->sendSMS($message, $number);

        if ($response['status'] == 'queued') {
            return $this->sucessResult();
        } else {
            return $this->errorResult();
        }
    }

    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $data = $this->getRequest()->getPostValue();

        $status = 'Hello Customer Welcome to CHK';

        if ($data['number']) {
            if ($this->_SNS->isEnabled()) {
                $result = $this->checkSNS($status, $data['number']);
            }

            $resultJson->setData(json_encode($result));
            return $resultJson;

        }
    }
}
