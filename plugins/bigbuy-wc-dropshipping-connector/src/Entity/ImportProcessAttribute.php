<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class ImportProcessAttribute
{
    /** @var int */
    public $attributeMapId;

    /** @var string */
    public $fileId;

    /** @var bool */
    public $response;
}