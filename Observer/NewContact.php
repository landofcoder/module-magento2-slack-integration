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

class NewContact implements ObserverInterface{

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
        $event = $observer->getEvent();
        $data = $event->getRequest()->getParams();

        $contactData = [];
        $contactData['customerName'] = $data['name'];
        $contactData['email'] = $data['email'];
        $contactData['telephone'] = $data['telephone'];
        $contactData['comment'] = $data['comment'];
        $contactData['store'] = $this->storeManager->getStore()->getName();

        $this->slack->sendMessage("new_contact", $contactData);

    }
}