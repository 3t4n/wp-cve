<?php
namespace Lara\Widgets\GoogleAnalytics;

/**
 * @package    Google Analytics by Lara
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @link       https://www.xtraorbit.com/
 * @copyright  Copyright (c) XtraOrbit Web development SRL 2016 - 2020
 */

if (!defined("ABSPATH"))
    die("This file cannot be accessed directly");

require(lrgawidget_plugin_dir . "core/output.class.php");
require(lrgawidget_plugin_dir . "core/storage.class.php");
SystemBootStrap::init_session();
SystemBootStrap::init_database();
require(lrgawidget_plugin_dir . "core/lrgawidget.permissions.php");
$Permissions = new Permissions();
?>