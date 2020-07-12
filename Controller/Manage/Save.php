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
namespace CHK\AmazonSNS\Controller\Manage;

use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\StoreManagerInterface;

class Save extends \CHK\AmazonSNS\Controller\Manage
{
    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var SubscriberFactory
     */
    protected $subscriberFactory;

    /**
     * Initialize dependencies.
     *
     * @param Context $context
     * @param Session $customerSession
     * @param Validator $formKeyValidator
     * @param StoreManagerInterface $storeManager
     * @param CustomerRepository $customerRepository
     * @param SubscriberFactory $subscriberFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        Validator $formKeyValidator,
        StoreManagerInterface $storeManager,
        CustomerRepository $customerRepository,
        SubscriberFactory $subscriberFactory
    ) {
        $this->storeManager = $storeManager;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerRepository = $customerRepository;
        $this->subscriberFactory = $subscriberFactory;
        parent::__construct($context, $customerSession);
    }

    /**
     * Save newsletter subscription preference action
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $this->_redirect('customer/account/');
        }

        $customerId = $this->_customerSession->getCustomerId();
        if ($customerId === null) {
            $this->messageManager->addErrorMessage(__('Something went wrong while saving your Send SMS subscription.'));
        } else {
            try {
                $customer = $this->customerRepository->getById($customerId);
                $storeId = $this->storeManager->getStore()->getId();
                $customer->setStoreId($storeId);
                $this->customerRepository->save($customer);
                if ((boolean)$this->getRequest()->getParam('is_subscribed', false)) {
                    $customer->setCustomAttribute('sms_subscription_status', '1');
                    $this->messageManager->addSuccessMessage(__('We saved your Send SMS subscription.'));
                } else {
                    $customer->setCustomAttribute('sms_subscription_status', '0');
                    $this->messageManager->addSuccessMessage(__('We removed your Send SMS subscription.'));
                }
                $this->customerRepository->save($customer);
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving your Send SMS subscription.'));
            }
        }
        $this->_redirect('customer/account/');
    }
}
