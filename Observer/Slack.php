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

class Slack
{
    /**
     * @var string
     */
    protected $hookUrl;

    /**
     * @var string
     */
    protected $generalChannel;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var mixed
     */
    protected $footer;

    /**
     * @var \Lof\SlackIntegration\Helper\ConfigData
     */
    protected $configData;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $curlClient;

    /**
     * construct
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\HTTP\Client\Curl $curlClient
     * @param \Lof\SlackIntegration\Helper\ConfigData $configData
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\HTTP\Client\Curl $curlClient,
        \Lof\SlackIntegration\Helper\ConfigData $configData
    ) {
        $this->objectManager = $objectManager;
        $this->jsonEncoder = $jsonEncoder;
        $this->curlClient = $curlClient;
        $this->configData = $configData;
        $this->hookUrl = $this->configData->getGeneralConfig('url');
        $this->username = $this->configData->getGeneralConfig('username');
        $this->generalChannel = $this->configData->getGeneralConfig('channel');
        $this->footer = $this->configData->getGeneralConfig('footer');
    }

    /**
     * Send message
     *
     * @param string $type
     * @param mixed $data
     */
    public function sendMessage($type, $data)
    {
        if ($this->configData->isModuleEnabled()) {
            switch ($type) {
                case "new_order":
                    if( $this->configData->getNotificationConfig('new_order_notification/enable') ) {
                        $channel = $this->configData->getNotificationConfig('new_order_notification/channel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $color = $this->configData->getNotificationConfig('new_order_notification/color');

                        $orderId = $data['order_id'];
                        $storeId = $data['store_id'];
                        $orderProducts = $data['order_products'];
                        $customerName = $data['customer_name'];
                        $email = $data['email'];
                        $telephone = $data['telephone'];
                        $shippingMethod = $data['shipping_method'];
                        $total = $data['total'];

                        $messageData = $this->objectManager->create('Lof\SlackIntegration\Model\Notification\BaseNotification');
                        $messageData->setFallback("#New Order with id: $orderId");
                        $messageData->setColor($color);
                        $messageData->setPretext("#New Order with id: $orderId");
                        $messageData->setTitle("Order Information");
                        $messageData->setFooter($this->footer);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Store Name');
                        $field->setValue($storeId);
                        $field->setShort(false);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Customer Name');
                        $field->setValue($customerName);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Email');
                        $field->setValue($email);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Telephone');
                        $field->setValue($telephone);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Shipping Method');
                        $field->setValue($shippingMethod);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Total');
                        $field->setValue($total);
                        $field->setShort(false);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Product List');
                        $count = 1;
                        $productList = "";
                        foreach ($orderProducts as $product) {
                            if($product->getParentItem())
                                continue;
                            $productList .= $count++ . ". " . $product->getName() . "  ( SKU: " . $product->getSKU() . ", Qty: " . $product->getQtyOrdered() . " )" . "\n";
                        }
                        $field->setValue($productList);
                        $field->setShort(false);
                        $messageData->addField($field);

                        $this->send($messageData, $channel);
                    }
                    break;
                case "new_customer":
                    if ( $this->configData->getNotificationConfig('new_customer_notification/enable') ) {
                        $channel = $this->configData->getNotificationConfig('new_customer_notification/channel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $color = $this->configData->getNotificationConfig('new_customer_notification/color');

                        $customerId = $data['customerId'];
                        $customerName = $data['customerName'];
                        $email = $data['email'];
                        $store = $data['store'];

                        $messageData = $this->objectManager->create('Lof\SlackIntegration\Model\Notification\BaseNotification');
                        $messageData->setFallback("#New Customer with id: $customerId");
                        $messageData->setColor($color);
                        $messageData->setPretext("#New Customer with id: $customerId");
                        $messageData->setTitle("Customer Information");
                        $messageData->setFooter($this->footer);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Store Name');
                        $field->setValue($store);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Customer Name');
                        $field->setValue($customerName);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Email');
                        $field->setValue($email);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $this->send($messageData, $channel);
                    }
                    break;
                case "new_review":
                    if ( $this->configData->getNotificationConfig('new_review_notification/enable') ) {
                        $channel = $this->configData->getNotificationConfig('new_review_notification/channel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $color = $this->configData->getNotificationConfig('new_review_notification/color');

                        $customerName = $data['customerName'];
                        $productName = $data['productName'];
                        $productSKU = $data['productSKU'];
                        $rating = $data['rating'];
                        $title = $data['title'];
                        $detail = $data['detail'];
                        $store = $data['store'];

                        $messageData = $this->objectManager->create('Lof\SlackIntegration\Model\Notification\BaseNotification');
                        $messageData->setFallback("#New Review for $productName");
                        $messageData->setColor($color);
                        $messageData->setPretext("#New Review for $productName");
                        $messageData->setTitle("Review Information");
                        $messageData->setFooter($this->footer);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Store Name');
                        $field->setValue($store);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Customer Name');
                        $field->setValue($customerName);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Product Name');
                        $field->setValue($productName);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Product SKU');
                        $field->setValue($productSKU);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Rating');
                        $field->setValue($rating);
                        $field->setShort(false);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Title');
                        $field->setValue($title);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Detail');
                        $field->setValue($detail);
                        $field->setShort(false);
                        $messageData->addField($field);

                        $this->send($messageData, $channel);
                    }
                    break;
                case "new_contact":
                    if ( $this->configData->getNotificationConfig('new_contact_notification/enable') ) {
                        $channel = $this->configData->getNotificationConfig('new_contact_notification/channel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $color = $this->configData->getNotificationConfig('new_contact_notification/color');

                        $customerName = $data['customerName'];
                        $email = $data['email'];
                        $telephone = $data['telephone'];
                        $comment = $data['comment'];
                        $store = $data['store'];

                        $messageData = $this->objectManager->create('Lof\SlackIntegration\Model\Notification\BaseNotification');
                        $messageData->setFallback("#New Contact");
                        $messageData->setColor($color);
                        $messageData->setPretext("#New Contact");
                        $messageData->setTitle("Contact Information");
                        $messageData->setFooter($this->footer);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Store Name');
                        $field->setValue($store);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Customer Name');
                        $field->setValue($customerName);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Email');
                        $field->setValue($email);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Telephone');
                        $field->setValue($telephone);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Comment');
                        $field->setValue($comment);
                        $field->setShort(false);
                        $messageData->addField($field);

                        $this->send($messageData, $channel);
                    }
                    break;
                case "new_chat":
                    if ( $this->configData->getNotificationConfig('new_chat_notification/enable') ) {
                        $channel = $this->configData->getNotificationConfig('new_chat_notification/channel');
                        $channel = $channel ? $channel : $this->generalChannel;
                        $color = $this->configData->getNotificationConfig('new_chat_notification/color');

                        $customerName = $data['name'];
                        $email = $data['email'];
                        $comment = $data['comment'];
                        $store = $data['store'];

                        $messageData = $this->objectManager->create('Lof\SlackIntegration\Model\Notification\BaseNotification');
                        $messageData->setFallback("#New Chat Message");
                        $messageData->setColor($color);
                        $messageData->setPretext("#New Chat Message");
                        $messageData->setTitle("Chat User Information");
                        $messageData->setFooter($this->footer);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Store Name');
                        $field->setValue($store);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Customer Name');
                        $field->setValue($customerName);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Email');
                        $field->setValue($email);
                        $field->setShort(true);
                        $messageData->addField($field);

                        $field = $this->objectManager->create('Lof\SlackIntegration\Model\Field');
                        $field->setTitle('Message');
                        $field->setValue($comment);
                        $field->setShort(false);
                        $messageData->addField($field);

                        $this->send($messageData, $channel);
                    }
                    break;
            }
        }
    }

    /**
     * send message data
     * @param mixed $messageData
     * @param string $channel
     * @return void
     */
    protected function send($messageData, $channel)
    {
        try {
            $message = $this->objectManager->create('Lof\SlackIntegration\Model\Message');
            $message->channel = $channel;
            $message->username = $this->username;
            $message->attachments[] = $messageData;
            $payload = $this->jsonEncoder->encode($message);
            $this->curlClient->post($this->hookUrl, ["payload" => $payload]);
        } catch (Exception $e){
            echo "Err";
        }
    }
}
