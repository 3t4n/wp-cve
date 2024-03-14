<?php

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class WoocommerceReportModel
{
    /** @var integer */
    public $MaxExecutionTime;

    /** @var integer */
    public $MemoryLimit;

    /** @var bool */
    public $DefaultCurrency;

    /** @var \DateTime */
    public $LastCronExecutionDate;
}