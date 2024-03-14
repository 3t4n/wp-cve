<?php

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class StatusReportModel
{
    /** @var string */
    public $RequestDate;

    /** @var AccountInfoReportModel */
    public $AccountInfo;

    /** @var ProductReport */
    public $ProductsReport;

    /** @var VariationReport */
    public $ProductVariationsReport;

    /** @var CategoryReport */
    public $CategoriesReport;

    /** @var FileReport */
    public $FilesReport;

    /** @var array */
    public $Taxes;

    /** @var array */
    public $Languages;
}