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
namespace CHK\AmazonSNS\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field as FormField;
use Magento\Framework\Data\Form\Element\AbstractElement;

class SNS extends FormField
{
    protected $_sid = 'chk_sns_sid';

    protected $_token = 'chk_sns_token';


    protected $_from = 'chk_sns_from';

    protected $_number = 'chk_sns_number';

    protected $_checkButtonLabel = 'Check Connect Amazon SNS';

    public function setNumber($number)
    {
        $this->_number = $number;
        return $this;
    }

    public function getNumber()
    {
        return $this->_number;
    }

    public function setSNSId($sid)
    {
        $this->_sid = $sid;
        return $this;
    }

    public function getSNSId()
    {
        return $this->_sid;
    }

    public function setSNSToken($token)
    {
        $this->_token = $token;
        return $this;
    }

    public function getSNSToken()
    {
        return $this->_token;
    }

    public function setButtonLabel($getCheckButtonLabel)
    {
        $this->_checkButtonLabel = $getCheckButtonLabel;
        return $this;

    }
	
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/SNS/check.phtml');
        }

        return $this;
    }

    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);

    }

    protected function _getElementHtml(AbstractElement $element)
    {
        $originalData = $element->getOriginalData();
        $buttonLabel  = !empty($originalData['button_label']) ? $originalData['button_label'] : $this->_checkButtonLabel;
        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'html_id'      => $element->getHtmlId(),
                'ajax_url'     => $this->_urlBuilder->getUrl('chk/system_config/check'),
            ]
        );

        return $this->_toHtml();
    }
}
