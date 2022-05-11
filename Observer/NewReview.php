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
use Lof\SlackIntegration\Model\Rating;

class NewReview implements ObserverInterface
{

    protected $slack;
    protected $productFactory;
    protected $storeManager;
    protected $ratingManager;

    public function __construct(
        Slack $slack,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Rating $ratingManager
    ) {
        $this->slack = $slack;
        $this->productFactory = $productFactory;
        $this->storeManager = $storeManager;
        $this->ratingManager = $ratingManager;
    }

    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $observer->getEvent()->getRequest()->getParams();
        $reviewData = [];
        $id = $data['id'];
        $customerName = $data['nickname'];
        $productName = $this->productFactory->create()->load($id)->getName();
        $productSKU = $this->productFactory->create()->load($id)->getSKU();
        $title = $data['title'];
        $detail = $data['detail'];
        $store = $this->storeManager->getStore()->getName();

        $ratingCollection = isset($data['ratings']) ? $data['ratings'] : null;
        $rating = "";
        if ($ratingCollection) {
            foreach ($ratingCollection as $key => $value) {
                $name = $this->ratingManager->getRatingCodeById($key);
                $value -= ($key-1)*5;
                $starString = "";
                while ($value-- != 0) {
                    $starString .= ":star:";
                }
                $rating .= $name . ": " . $starString . "\n";
            }
        }

        $reviewData['customerName'] = $customerName;
        $reviewData['productName'] = $productName;
        $reviewData['productSKU'] = $productSKU;
        $reviewData['title'] = $title;
        $reviewData['rating'] = $rating;
        $reviewData['detail'] = $detail;
        $reviewData['store'] = $store;
        $this->slack->sendMessage('new_review', $reviewData);
    }
}
