<?php
/*
Plugin Name: Zendrop
Description: Import products from Zendrop to your WooCommerce store & automate fulfillment.
Version: 1.0.1
*/
defined('ABSPATH') or die("Cannot access pages directly.");
define('A2CBC_BRIDGE_IS_CUSTOM_OPTION_NAME', 'woocommerce_bridge_connector_is_custom');
define('A2CBC_BRIDGE_IS_INSTALLED', 'woocommerce_bridge_connector_is_installed');

if (!defined('A2CBC_STORE_BASE_DIR')) {
  define('A2CBC_STORE_BASE_DIR', ABSPATH);
}

if (!defined('A2CBC_MIN_WOO_VERSION')) {
  define('A2CBC_MIN_WOO_VERSION', '2.8.1');
}

if (!function_exists('is_a2cbc_required_plugins_active')) {
  require_once('includes/a2c-bridge-connector-functions.php');
}

if (!is_a2cbc_required_plugins_active()) {
  add_action( 'admin_notices', 'woocommerce_version_error');

  if (!function_exists('deactivate_plugins')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    deactivate_plugins(plugin_basename(__FILE__));
  }

  return;
}

include 'worker.php';
$worker = new BridgeConnector();

include_once $worker->bridgePath . $worker->configFilePath;
$storeKey = A2CBC_TOKEN;
$isCustom = get_option(A2CBC_BRIDGE_IS_CUSTOM_OPTION_NAME);
$bridgeUrl = $worker->getBridgeUrl();

if (isset($_REQUEST['connector_action'])) {
  $action = $_REQUEST['connector_action'];
  $storeKey = BridgeConnector::generateStoreKey();
  switch ($action) {
    case 'installBridge':
      $data = [];
      update_option(A2CBC_BRIDGE_IS_INSTALLED, true);
      $status = $worker->updateToken($storeKey);

      if (!$status['success']) {
        break;
      }

      $status = $worker->installBridge();
      $data = [
        'storeKey' => $storeKey,
        'bridgeUrl' => $worker->getBridgeUrl()
      ];

      if ($status['success']) {
        update_option(A2CBC_BRIDGE_IS_CUSTOM_OPTION_NAME, isset($status['custom']) ? $status['custom'] : false);
        update_option(A2CBC_BRIDGE_IS_INSTALLED, true);
      }
      break;
    case 'removeBridge':
      update_option(A2CBC_BRIDGE_IS_INSTALLED, false);
      $status = ['success' => true, 'message' => 'Bridge deleted'];
      $data   = [];
      delete_option(A2CBC_BRIDGE_IS_CUSTOM_OPTION_NAME);
      delete_option(A2CBC_BRIDGE_IS_INSTALLED);
      break;
  }
  echo json_encode(['status' => $status, 'data' => $data]);
  exit();
}

function connector_plugin_action_links($links, $file)
{
  if ($file == plugin_basename(dirname(__FILE__) . '/connectorMain.php')) {
    $links[] = '<a href="' . admin_url('admin.php?page=connector-config') . '">' . __('Settings') . '</a>';
  }

  return $links;
}

add_filter('plugin_action_links', 'connector_plugin_action_links', 10, 2);


/**
 * Register routes.
 *
 * @since 1.5.0
 */
function rest_api_register_routes() {
  if (isset($GLOBALS['woocommerce']) || isset($GLOBALS['wpec'])) {
    require_once('includes/class-a2c-bridge-connector-rest-api-controller.php');

    // v1
    $restApiController = new A2C_Bridge_Connector_V1_REST_API_Controller();
    $restApiController->register_routes();
  }
}

add_action( 'rest_api_init', 'rest_api_register_routes');

function connector_config()
{
  global $worker;
  include_once $worker->bridgePath . $worker->configFilePath;
  $storeKey = A2CBC_TOKEN;
  $isCustom = get_option(A2CBC_BRIDGE_IS_CUSTOM_OPTION_NAME);
  $bridgeUrl = $worker->getBridgeUrl();

  wp_enqueue_style('connector-css', plugins_url('css/style.css', __FILE__));
  wp_enqueue_script('connector-js', plugins_url('js/scripts.js', __FILE__), array('jquery'));

  $showButton = 'install';
  if (get_option(A2CBC_BRIDGE_IS_CUSTOM_OPTION_NAME)) {
    $showButton = 'uninstall';
  }

  $cartName = 'WooCommerce';
  $sourceCartName = 'WooCommerce';
  $sourceCartName = strtolower(str_replace(' ', '-', trim($sourceCartName)));
  $referertext = 'Connector: ' . $sourceCartName . ' to ' . $cartName . ' module';

  include 'settings.phtml';
  return true;
}

function connector_uninstall()
{
  delete_option(A2CBC_BRIDGE_IS_CUSTOM_OPTION_NAME);
  delete_option(A2CBC_BRIDGE_IS_INSTALLED);
}

function connector_deactivate()
{
  update_option(A2CBC_BRIDGE_IS_INSTALLED, false);
}

function connector_load_menu()
{
  add_submenu_page('plugins.php', __('Zendrop'), __('Zendrop'), 'manage_options', 'connector-config', 'connector_config');
}

register_uninstall_hook( __FILE__, 'connector_uninstall' );
register_deactivation_hook( __FILE__, 'connector_deactivate' );

add_action('admin_menu', 'connector_load_menu');
