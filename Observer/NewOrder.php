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

class NewOrder implements ObserverInterface
{

    protected $orderInfo;
    protected $slack;
    protected $storeManager;

    public function __construct(
        \Magento\Sales\Api\Data\OrderInterface $orderInfo,
        Slack $slack,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->orderInfo = $orderInfo;
        $this->slack = $slack;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderData = [];
        $id = $observer->getEvent()->getOrder_ids()[0];
        $storeName = $this->storeManager->getStore()->getName();
        if($id) {
            $order = $this->orderInfo->loadByIncrementId($id);
        } else {
            $order = null;
        }

        if(!$order || ($order && !$order->getId())) {
            $order = $this->orderInfo->load((int)$id);
        }
        if(!$order->getCustomerIsGuest()) {
            $customerName = $order->getCustomerFirstname() . " " .
                $order->getCustomerMiddlename() . " " .
                $order->getCustomerLastname();
        } else {
            $customerName = __("Guest");
        }
        $email = $order->getCustomerEmail();
        $shippingData = $order->getShippingAddress();
        $telephone = '';
        if($shippingData) {
            $telephone = $shippingData->getTelephone();
        }


        $subTotal = "Subtotal : " . $order->getSubtotal();
        $grandTotal = "Grand Total : " . $order->getGrandTotal();
        $shippingMethod = $order->getShippingMethod();
        $shippingAmount = "Shipping Amount : " . $order->getShippingAmount();
        $taxAmount = "Tax Amount : " . $order->getTaxAmount();
        $discountAmount = "Discount Amount : " . $order->getDiscountAmount();
        $total = $subTotal . "\n" . $shippingAmount . "\n" . $taxAmount . "\n" . $discountAmount . "\n" . $grandTotal;

        $orderData['order_id'] = $order->getIncrementId();
        $orderData['store_id'] = $storeName;
        $orderData['order_products'] = $order->getAllItems();
        $orderData['customer_name'] = $customerName;
        $orderData['customer_name'] = preg_replace('/\s+/', ' ', trim($orderData['customer_name']));;
        $orderData['email'] = $email;
        $orderData['telephone'] = $telephone;
        $orderData['shipping_method'] = $shippingMethod;
        $orderData['total'] = $total;

        $this->slack->sendMessage("new_order", $orderData);

    }
}
