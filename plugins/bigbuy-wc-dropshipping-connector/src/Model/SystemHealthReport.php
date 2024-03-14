<?php

declare(strict_types=1);

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class SystemHealthReport
{
    /** @var string|null */
    public $LastCronExecutionDate;

    /** @var float|null */
    public $TotalHardDriveGb;

    /** @var float|null */
    public $FreeHardDriveGb;

    /** @var int|null */
    public $TotalMemoryMb;

    /** @var int|null */
    public $FreeMemoryMb;

    /** @var string|null */
    public $DateUpdateCarriers;

    /** @var string|null */
    public $DateUpdateStocks;

    /** @var string|null */
    public $DateLastModuleVersionCheck;
}