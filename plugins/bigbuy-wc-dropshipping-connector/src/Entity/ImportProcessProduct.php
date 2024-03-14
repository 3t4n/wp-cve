<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class ImportProcessProduct
{
    /** @var int */
    public $productMapId;

    /** @var string */
    public $fileId;

    /** @var bool */
    public $response;
}