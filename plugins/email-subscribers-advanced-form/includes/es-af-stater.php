<?php

$es_af_plugin_name='email-subscribers-advanced-form';
$es_af_current_folder = dirname(dirname(__FILE__));

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if(!defined('ES_AF_TABLE')) define('ES_AF_TABLE', 'es_advanced_form');
if(!defined('ES_AF_DBVERSION')) define('ES_AF_DBVERSION', '1.0');

if(!defined('ES_AF_PLUGIN_NAME')) define('ES_AF_PLUGIN_NAME', $es_af_plugin_name);
if(!defined('ES_AF_PLUGIN_DISPLAY')) define('ES_AF_PLUGIN_DISPLAY', "Email Subscribers - Group Selector");
if(!defined('ES_AF_TDOMAIN')) define('ES_AF_TDOMAIN', $es_af_plugin_name);
if(!defined('ES_AF_DIR')) define('ES_AF_DIR', $es_af_current_folder.DS);
if(!defined('ES_AF_URL')) define('ES_AF_URL',plugins_url().'/'.strtolower('email-subscribers-advanced-form').'/');
if(!defined('ES_AF_FILE')) define('ES_AF_FILE',ES_AF_DIR.'email-subscribers-advanced-form.php');
if(!defined('ES_AF_ADMINURL')) define('ES_AF_ADMINURL', site_url() . '/wp-admin/admin.php?page=es-af-advancedform');
if(!defined('ES_AF_FAV')) define('ES_AF_FAV', admin_url( 'admin.php?page=es-general-information' ));
define('ES_AF_OFFICIAL', 'If you like <strong>Email Subscribers - Group Selector</strong>, please leave us <a target="_blank" href="https://wordpress.org/support/plugin/email-subscribers-advanced-form/reviews/?filter=5#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a> a rating. A huge thank you from Icegram in advance!');

require_once(ES_AF_DIR.'includes'.DIRECTORY_SEPARATOR.'es-af-register.php');
require_once(ES_AF_DIR.'includes'.DIRECTORY_SEPARATOR.'es-af-query.php');

// Email Subscribers Constant
if(!defined('ES_TDOMAIN')) define('ES_TDOMAIN', 'email-subscribers');
if(!defined('ES_URL')) define('ES_URL',plugins_url().'/'.strtolower('email-subscribers').'/');
