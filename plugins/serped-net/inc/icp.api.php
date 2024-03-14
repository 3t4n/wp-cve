<?php

if (!isset($_GET['pkey']))
    die('not authorized');

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once( $parse_uri[0] . 'wp-load.php' );

$plugin_key = srpd_get_plugin_key();

if ($plugin_key != sanitize_text_field($_GET['pkey']))
    die('not valid key');

//if there is no action return confirm for ping
if (!isset($_GET['action']))
    die('1');

$action = sanitize_text_field($_GET['action']);
switch ($action) {
    case 'get_plugins':
        echo json_encode(srpd_get_plugins());
        break;

    case 'activate_plugin':
        echo json_encode(srpd_activate_plugin(sanitize_text_field($_GET['plugin'])));
        break;

    case 'deactivate_plugin':
        echo json_encode(srpd_deactivate_plugin(sanitize_text_field($_GET['plugin'])));
        break;

    case 'update_plugin':
        echo json_encode(srpd_update_plugin(sanitize_text_field($_GET['plugin']), sanitize_text_field($_GET['zip'])));
        break;

    case 'install_plugin':
        echo json_encode(srpd_install_plugin(urldecode(sanitize_text_field($_GET['download_link']))));
        break;

    case 'uninstall_plugin':
        echo json_encode(srpd_uninstall_plugin(sanitize_text_field($_GET['plugin'])));
        break;

    case 'get_themes':
        echo json_encode(srpd_get_themes());
        break;

    case 'activate_theme':
        echo json_encode(srpd_activate_theme(sanitize_text_field($_GET['theme'])));
        break;

    case 'delete_theme':
        echo json_encode(srpd_delete_theme(sanitize_text_field($_GET['theme'])));
        break;

    case 'install_theme':
        echo json_encode(srpd_install_theme(urldecode(sanitize_text_field($_GET['download_link']))));
        break;

    case 'update_theme':
        echo json_encode(srpd_update_theme(sanitize_text_field($_GET['theme'])));
        break;

    case 'get_wp_version' :
        global $wp_version;

        $check = wp_remote_get("http://api.wordpress.org/core/version-check/1.0/?version=" . $wp_version);
        $check = wp_remote_retrieve_body($check);

        $upgrade = ($check == 'latest') ? 0 : 1;

        echo json_encode(array('version' => $wp_version, 'update' => $upgrade));
        break;

    case 'update_wp' :
        echo json_encode(srpd_update_core());
        break;

    case 'update_htaccess' :
        //accepts post $_POST['icp_error_msg'], $_POST['icp_bots'], $_POST['icp_ips']
        echo srpd_update_htaccess();
        break;

    case 'update_tracking' :
        //accepts post $_POST['icp_tracking_code']
        echo srpd_update_tracking_code();
        break;

    case 'get_tracking_code' :
        echo srpd_get_tracking_code();
        break;

    case 'get_blocked_bots' :
        $htaccess = srpd_get_htaccess_content();
        echo json_encode(icp_get_blocked_bots($htaccess));
        break;

    case 'get_blocked_ips' :
        $htaccess = srpd_get_htaccess_content();
        echo json_encode(icp_get_blocked_ips($htaccess));
        break;

    case 'get_error_msg' :
        $htaccess = srpd_get_htaccess_content();
        echo srpd_get_error_msg($htaccess);
        break;

    default:
        echo 'unathorized access...';
        break;
}
?>