<?php

namespace Lof\SlackIntegration\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;

class TestButton extends Field
{
    /** @var UrlInterface */
    protected $_urlBuilder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_urlBuilder = $context->getUrlBuilder();

    }

    /**
     * Set template
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Lof_SlackIntegration::system/config/testbutton.phtml');
    }

    /**
     * Generate button html
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'lof_test_connection',
                'label' => __('Test Notification'),
                'onclick' => 'javascript:SlackIntegrationTest(); return false;',
            ]
        );

        return $button->toHtml();
    }

    public function getAdminUrl(){
        return $this->_urlBuilder->getUrl('lofslagento/test', ['store' => $this->_request->getParam('store')]);
    }

    /**
     * Render button
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Return element html
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
}