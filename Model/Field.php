<?php

namespace Lof\SlackIntegration\Model;


class Field
{
    public $title;
    public $value;
    public $short;

    public function setTitle($title){
        $this->title = $title;
    }

    public function setValue($value){
        $this->value = $value;
    }

    public function setShort($short){
        $this->short = $short;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getValue(){
        return $this->value;
    }

    public function getSort(){
        return $this->short;
    }
}