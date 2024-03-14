<?php

namespace WcMipConnector\Entity;

defined('ABSPATH') || exit;

class ImportProcessTag
{
    /** @var int */
    public $tagMapId;

    /** @var int */
    public $fileId;

    /** @var bool */
    public $response;
}