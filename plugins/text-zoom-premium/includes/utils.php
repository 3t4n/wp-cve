<?php

if (!defined('WPINC')) die('No access outside of wordpress.');

class PZATZoomOptions {
  private $defaults = array(
    'zoom_settings_design' => '',
    'zoom_settings_main_color' => '#FFFFFF',
    'zoom_settings_accent_color' => '#666666',
    'zoom_settings_opacity' => '0.8',
    'zoom_settings_position' => 'center-left',
    'zoom_settings_blocklist' => 'elementor-preview',
    'zoom_settings_strict_blocklist' => '',
    'zoom_settings_content_deselectors' => 'rs-layer',
    'zoom_settings_logo_url' => PZAT_ASSETS_URL . 'admin/default-logo.png',
    'zoom_settings_logo_title' => "I am Abili 🐵 You can find more tools for accessability from me here!",
    'zoom_settings_logo_link' => 'https://abilitools.com'
  );
  private $opts;

  function __construct() {
    $opts = get_option('zoom_options');

    if ($opts == false) {
      $this->opts = $this->defaults;
    } else {
      $this->opts = $opts;
    }
  }

  function get($id) {
    $value = $this->opts[$id];

    if ($value === NULL) {
      return $this->defaults[$id];
    }

    return $value;
  }
}

function pzat_zoom_options() {
  return new PZATZoomOptions();
}

function pzat_notify_url($host, $event, $timestamp) {
  if ((!$host || $host == '') ||
      (!$event || $event == '') ||
      (!$timestamp || $timestamp == '')) {
    return false;
  } else {
    return 'https://europe-west3-kontextr.cloudfunctions.net/at-wp-track?p=' . PZAT_PLUGIN_NAME . '&d=' . $host . '&evt=' . $event . '&ts=' . $timestamp;
  }
}

function pzat_notify($msg) {
  $prefix = '[Plugin:' . PZAT_PLUGIN_NAME . ']';
  $err_notify = $prefix . ' Error in calling remote tracking service.';
  $err_notify_resp = $prefix . ' Service responded with code %d.';
  $err_url = $prefix . 'Failed to get URL.';

  $host = parse_url(site_url(), PHP_URL_HOST);
  $timestamp = time();
  $tracking_url = pzat_notify_url($host, $msg, $timestamp);

  if ($tracking_url) {
    $response = wp_remote_get($tracking_url);

    if (is_wp_error($response)) {
      error_log($err_notify);
      trigger_error($err_notify);
    } else {
      $response_code = wp_remote_retrieve_response_code($response);
      if ($response_code != 200) {
        error_log(sprintf($err_notify_resp, $response_code));
        trigger_error(sprintf($err_notify_resp, $response_code));
      }
    }
  } else {
   error_log($err_url);
   trigger_error($err_url);
  }
}

?>
