<?php
/**
 * Created by PhpStorm.
 * User: bdt
 * Date: 5/17/18
 * Time: 9:13 AM
 */

namespace Lof\SlackIntegration\Model\Notification;

class BaseNotification
{
    public $fallback;
    public $color;
    public $pretext;
    public $title;
    public $fields = [];
    public $footer;

    /**
     * @param mixed $fallback
     */
    public function setFallback($fallback)
    {
        $this->fallback = $fallback;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @param mixed $pretext
     */
    public function setPretext($pretext)
    {
        $this->pretext = $pretext;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function addField($field)
    {
        $this->fields[] = $field;
    }

    /**
     * @param mixed $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    /**
     * @return mixed
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return mixed
     */
    public function getPretext()
    {
        return $this->pretext;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return mixed
     */
    public function getFooter()
    {
        return $this->footer;
    }
}