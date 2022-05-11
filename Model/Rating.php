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

namespace Lof\SlackIntegration\Model;

class Rating{
    protected $connection;

    public function __construct()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();;
        $this->connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
    }

    public function getRatingCodeById($id){
        $result =  $this->connection->fetchAll("SELECT rating_code FROM rating WHERE rating_id =  $id");
        return $result[0]['rating_code'];
    }

}

?>