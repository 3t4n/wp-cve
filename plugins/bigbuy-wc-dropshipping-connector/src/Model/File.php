<?php

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class File
{
    public const STATUS_SUBMITTED = 'SUBMITTED';
    public const STATUS_DONE = 'DONE';

    /** @var string */
    public $state;

    /** @var string */
    public $message;
}