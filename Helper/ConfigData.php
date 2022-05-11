<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the venustheme.com license that is
 * available through the world-wide-web at this URL:
 * http://venustheme.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_SlackIntegration
 * @copyright  Copyright (c) 2018 Landofcoder (http://www.venustheme.com/)
 * @license    http://www.venustheme.com/LICENSE-1.0.html
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

    public function __construct(Context $context,
                                ObjectManagerInterface $objectManager,
                                StoreManagerInterface $storeManager)
    {
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getConfigValue($filed, $storeId = null)
    {
        return $this->scopeConfig->getValue($filed, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::PATH_GENERAL . $code, $storeId);
    }

    public function getNotificationConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::PATH_MODULE . $code, $storeId);
    }

    public function isModuleEnabled()
    {
        return $this->getGeneralConfig('enable');
    }
}

?>