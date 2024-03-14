<?php

namespace WcMipConnector\Model;

use WcMipConnector\Entity\WCObjectInterface;

defined('ABSPATH') || exit;

class WCBatchRequest
{
    /** @var WCObjectInterface[] */
    public $create;

    /** @var WCObjectInterface[] */
    public $update;

    /** @var WCObjectInterface[] */
    public $delete;
}
