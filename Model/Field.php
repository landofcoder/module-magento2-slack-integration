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

class Field
{
    public $title;
    public $value;
    public $short;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setShort($short)
    {
        $this->short = $short;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSort()
    {
        return $this->short;
    }
}
