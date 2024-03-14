<?php

function sbs_6310_template_01_10()
{
  global $wpdb;
  $style_table = $wpdb->prefix . 'sbs_6310_style';
  $item_table = $wpdb->prefix . 'sbs_6310_item';  
  include sbs_6310_plugin_url . 'header.php';
  if (empty($_GET['styleid'])) {
    include sbs_6310_plugin_url . 'settings/preview-01-10.php';
  } else if (!empty($_GET['styleid'])) {
    $styleId = (int) ($_GET['styleid']);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    $templateId = substr($styledata['style_name'], -2);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    include sbs_6310_plugin_url . 'common-template.php';
  }
}

function sbs_6310_template_11_20()
{
  global $wpdb;
  $style_table = $wpdb->prefix . 'sbs_6310_style';
  $item_table = $wpdb->prefix . 'sbs_6310_item';  
  include sbs_6310_plugin_url . 'header.php';
  if (empty($_GET['styleid'])) {
    include sbs_6310_plugin_url . 'settings/preview-11-20.php';
  } else if (!empty($_GET['styleid'])) {
    $styleId = (int) ($_GET['styleid']);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    $templateId = substr($styledata['style_name'], -2);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    include sbs_6310_plugin_url . 'common-template.php';
  }
}
function sbs_6310_template_21_30()
{
  global $wpdb;
  $style_table = $wpdb->prefix . 'sbs_6310_style';  
  $item_table = $wpdb->prefix . 'sbs_6310_item';  
  include sbs_6310_plugin_url . 'header.php';
  if (empty($_GET['styleid'])) {
    include sbs_6310_plugin_url . 'settings/preview-21-30.php';
  } else if (!empty($_GET['styleid'])) {
    $styleId = (int) ($_GET['styleid']);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    $templateId = substr($styledata['style_name'], -2);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    include sbs_6310_plugin_url . 'common-template.php';
  }
}

function sbs_6310_template_31_40()
{
  global $wpdb;
  $style_table = $wpdb->prefix . 'sbs_6310_style';
  $item_table = $wpdb->prefix . 'sbs_6310_item';  
  include sbs_6310_plugin_url . 'header.php';
  if (empty($_GET['styleid'])) {
    include sbs_6310_plugin_url . 'settings/preview-31-40.php';
  } else if (!empty($_GET['styleid'])) {
    $styleId = (int) ($_GET['styleid']);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    $templateId = substr($styledata['style_name'], -2);    
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    include sbs_6310_plugin_url . 'common-template.php';
  }
}

function sbs_6310_template_41_50()
{
  global $wpdb;
  $style_table = $wpdb->prefix . 'sbs_6310_style';
  $item_table = $wpdb->prefix . 'sbs_6310_item';  
  include sbs_6310_plugin_url . 'header.php';
  if (empty($_GET['styleid'])) {
    include sbs_6310_plugin_url . 'settings/preview-41-50.php';
  } else if (!empty($_GET['styleid'])) {
    $styleId = (int) ($_GET['styleid']);
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    $templateId = substr($styledata['style_name'], -2);    
    $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
    include sbs_6310_plugin_url . 'common-template.php';
  }
}

function sbs_6310_team_6310_manage_items()
{
  global $wpdb;  
  include sbs_6310_plugin_url . 'header.php';  
  include sbs_6310_plugin_url . 'settings/manage-items.php';
}

function sbs_6310_team_6310_category()
{
  global $wpdb;  
  include sbs_6310_plugin_url . 'header.php';  
  include sbs_6310_plugin_url . 'settings/category.php';
}


function sbs_6310_service_6310_icon()
{
  global $wpdb; 
  include sbs_6310_plugin_url . 'header.php';
  include sbs_6310_plugin_url . 'settings/social-icon.php';
}

function sbs_6310_service_6310_lincense()
{
  global $wpdb;  
  include sbs_6310_plugin_url . 'header.php';
  include sbs_6310_plugin_url . 'license.php';
}

function sbs_6310_service_6310_how_to_use()
{
  global $wpdb; 
  include sbs_6310_plugin_url . 'header.php';
  include sbs_6310_plugin_url . 'settings/how-to-use.php';
}

function sbs_6310_wpmart_plugins()
{
  global $wpdb;
  include sbs_6310_plugin_url . 'header.php';  
  include sbs_6310_plugin_url . 'settings/wpmart-plugins.php';
}
function sbs_6310_service_6310_setting()
{
  global $wpdb;
  wp_enqueue_style('sbs-6310-style', plugins_url('assets/css/style.css', __FILE__));
  wp_enqueue_style('sbs-color-style', plugins_url('assets/css/jquery.minicolors.css', __FILE__));
  include sbs_6310_plugin_url . 'header.php';  
  include sbs_6310_plugin_url . 'settings/plugin-settings.php';
}
function sbs_6310_service_6310_import_export()
{
  global $wpdb;
  include sbs_6310_plugin_url . 'header.php';  
  include sbs_6310_plugin_url . 'settings/import-export-plugins.php';
}

function sbs_6310_privacy_policy() {
  global $wpdb;
  include sbs_6310_plugin_url . 'header.php';  
  include sbs_6310_plugin_url . 'settings/privacy_policy.php';
}