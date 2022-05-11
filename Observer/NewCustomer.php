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

namespace Lof\SlackIntegration\Observer;
use Magento\Framework\Event\ObserverInterface;

class NewCustomer implements ObserverInterface{

    protected $slack;

    protected $storeManager;

    public function __construct(Slack $slack,
    \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->slack = $slack;
        $this->storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $newCustomer = $observer->getEvent()->getCustomer();
        $customerData = [];

        $customerData['customerId'] = $newCustomer->getId();
        $customerData['customerName'] = $newCustomer->getFirstname() . " " . $newCustomer->getMiddlename() . " " . $newCustomer->getLastname();
        $customerData['customerName'] = preg_replace('/\s+/', ' ', trim($customerData['customerName']));
        $customerData['email'] = $newCustomer->getEmail();
        $customerData['store'] = $this->storeManager->getStore()->getName();

        $this->slack->sendMessage('new_customer', $customerData);

    }
}
