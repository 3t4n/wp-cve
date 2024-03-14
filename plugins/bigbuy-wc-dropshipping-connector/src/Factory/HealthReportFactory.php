<?php

declare(strict_types=1);

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Model\CatalogHealthReport;
use WcMipConnector\Model\FileHealthReport;
use WcMipConnector\Model\HealthReport;
use WcMipConnector\Model\OrderHealthReport;
use WcMipConnector\Model\SystemHealthReport;

class HealthReportFactory
{
    public static function create(
        FileHealthReport       $fileHealthReport,
        CatalogHealthReport    $catalogHealthReportModel,
        SystemHealthReport     $systemHealthReport,
        OrderHealthReport $orderHealthReportModel
    ): HealthReport {
        $healthReportModel = new HealthReport();

        $healthReportModel->FileHealthReport = $fileHealthReport;
        $healthReportModel->CatalogHealthReport = $catalogHealthReportModel;
        $healthReportModel->SystemHealthReport = $systemHealthReport;
        $healthReportModel->OrderHealthReport = $orderHealthReportModel;

        return $healthReportModel;
    }
}