<?php

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class FileReport
{
    /** @var integer */
    public $TotalFiles;

    /** @var integer */
    public $PendingFiles;

    /** @var boolean */
    public $ProcessingFiles;

    /** @var \DateTime */
    public $LastStartTime;

    /** @var \DateTime */
    public $LastAddTime;
}