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

namespace Lof\SlackIntegration\Model;

class Rating
{
    protected $connection;

    public function __construct()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();;
        $this->connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
    }

    /**
     * Get rating code by id
     *
     * @param int|string $id
     * @return string
     */
    public function getRatingCodeById($id)
    {
        $result =  $this->connection->fetchAll("SELECT rating_code FROM rating WHERE rating_id =  $id");
        return $result && isset($result[0]) && isset($result[0]['rating_code']) ? $result[0]['rating_code'] : "";
    }

}
?>
