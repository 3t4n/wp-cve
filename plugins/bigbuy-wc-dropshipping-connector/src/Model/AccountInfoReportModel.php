<?php

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class AccountInfoReportModel
{
    /** @var string */
    public $Version;

    /** @var boolean */
    public $Installed;

    /** @var boolean */
    public $MultiShop;

    /** @var string */
    public $PhpVersion;

    /** @var integer */
    public $MaxExecutionTime;

    /** @var string */
    public $DefaultCurrency;

    /** @var boolean */
    public $Curl;

    /** @var boolean */
    public $FileFolderPermissions;

    /** @var string */
    public $ShopName;

    /** @var \DateTime */
    public $LastCronExecutionDate;

    /** @var string */
    public $WordPressVersion;

    /** @var string */
    public $WooCommerceVersion;

    /** @var bool */
    public $ApiKeyEnabled;

    /** @var bool */
    public $RewritingSettingActive;

    /** @var array */
    public $DisabledRequiredFunctions;
}
