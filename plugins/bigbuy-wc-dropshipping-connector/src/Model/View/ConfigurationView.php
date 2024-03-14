<?php

namespace WcMipConnector\Model\View;

use WcMipConnector\Model\WoocommerceReportModel;

defined('ABSPATH') || exit;

class ConfigurationView
{
    /** @var WoocommerceReportModel */
    public $accountReport;

    /**  @var array */
    public $memoryInfo;

    /**  @var array */
    public $taxes;

    /**  @var string */
    public $defaultIsoCode;

    /**  @var array */
    public $storageServerInfo;

    /**  @var bool */
    public $cron;

    /** @var RequirementView[] */
    public $requirements;

    /** @var array */
    public $warningMessages;

    /** @var array */
    public $systemStorage;

    /** @var array */
    public $disabledRequiredFunctions;
}
