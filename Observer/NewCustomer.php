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

namespace Lof\SlackIntegration\Observer;
use Magento\Framework\Event\ObserverInterface;

class NewCustomer implements ObserverInterface
{
    protected $slack;
    protected $storeManager;

    public function __construct(
        Slack $slack,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->slack = $slack;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
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
