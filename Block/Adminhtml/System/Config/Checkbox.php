<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_SlackIntegration
 * @copyright  Copyright (c) 2022 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\SlackIntegration\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;

/**
 * Class Checkbox
 * @package Lof\SlackIntegration\Block\Adminhtml\System\Config
 */
class Checkbox extends Field
{
    const CONFIG_PATH = 'lof_slack_integration/general/';

    protected $_template = 'Lof_SlackIntegration::system/config/checkbox.phtml';

    /**
     * @var mixed|null
     */
    protected $_values = null;

    /**
     * Checkbox constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Retrieve element HTML markup.
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->setNamePrefix($element->getName())
            ->setHtmlId($element->getHtmlId());

        return $this->_toHtml();
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = [];
        $optionArray = \Lof\SlackIntegration\Model\Config\Source\Checkbox::toOptionArray();
        foreach ($optionArray as $value) {
            $values[$value['value']] = $value['label'];

        }
        return $values;
    }

    /**
     * Get checked value.
     * @return boolean
     */
    public function isChecked()
    {
        $name = $this->getHtmlId();
        $name = explode('_', $name);
        $name = $name[sizeof($name) - 1];
        if (is_null($this->_values)) {
            $data = $this->getConfigData();
            if (isset($data[self::CONFIG_PATH . $name])) {
                return true;
            } else {
                return false;
            }
        }
    }

}
