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
namespace Lof\SlackIntegration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class ConfigData extends AbstractHelper
{
    protected $storeManager;
    protected $objectManager;

    const PATH_MODULE = 'lof_slack_integration/';
    const PATH_GENERAL = 'lof_slack_integration/general/';
    const PATH_CHANNEL = 'lof_slack_integration/channel/';

    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager
    ) {
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get config value
     *
     * @param string $field
     * @param mixed|null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get general config value
     *
     * @param string $field
     * @param mixed|null $storeId
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::PATH_GENERAL . $code, $storeId);
    }

    /**
     * Get notification config value
     *
     * @param string $field
     * @param mixed|null $storeId
     * @return mixed
     */
    public function getNotificationConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::PATH_MODULE . $code, $storeId);
    }

    /**
     * check module is enabled
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return (bool)$this->getGeneralConfig('enable');
    }
}
?>
