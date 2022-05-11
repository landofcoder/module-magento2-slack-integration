<?php
/**
 * Created by PhpStorm.
 * User: bdt
 * Date: 5/17/18
 * Time: 11:18 AM
 */

namespace Lof\SlackIntegration\Model;


class Message
{
    public $channel;
    public $username;
    public $attachments = [];
}