<?php

declare(strict_types=1);

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class HealthReport
{
    /** @var CatalogHealthReport */
    public $CatalogHealthReport;

    /** @var SystemHealthReport */
    public $SystemHealthReport;

    /** @var FileHealthReport */
    public $FileHealthReport;

    /** @var OrderHealthReport */
    public $OrderHealthReport;
}