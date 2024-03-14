<?php

// no direct access!
defined('ABSPATH') or die("No direct access");

/*
Plugin Name: GSpeech
Plugin URI: https://gspeech.io
Description: GSpeech is a universal text to speech solution. See <a href="https://gspeech.io/demos">GSpeech Demo</a>. Please <a href="https://gspeech.io/contact-us">Contact Us</a> if you have any questions.
Author: GSpeech Inc.
Author URI: https://gspeech.io
Version: 3.1.20
*/

global $wpdb;

$session_started = session_status() === PHP_SESSION_NONE ? false : true;

if (!$session_started) {
    session_start();
}

// 3.0.0 fix
$gsp_page = isset($_GET['page']) ? $_GET['page'] : '';

if($gsp_page == 'wpgs-options') {

    $gsp_token_inner = bin2hex(random_bytes(32));
    $g_holder = isset($_GET['holder']) ? $_GET['holder'] : '';

    if($g_holder != 'gs_ajax') {
        $_SESSION["gsp_token_val"] = $gsp_token_inner;
    }
}

$gsp_player_title = 'Click to listen highlighted text!';
$plugin_folder_name = 'gspeech';
$plugin_version = '3.1.20';

$wpgs_options = get_option('wpgs_settings');

if(!$wpgs_options)
    $wpgs_options = array();

$wpgs_load_sh = 0;
$sh_ = 0;

$new_db_version = 76;
define('PLG_VERSION', $plugin_version);
define('NEW_DB_VER', $new_db_version);

$default_bcp1 = '#ffffff';
$default_cp1 =  '#111111';
$default_bca1 = '#545454';
$default_ca1 =  '#ffffff';
$default_spop1 = 90;
$default_spop1_ = 0.9;
$default_spoa1 = 100;
$default_spoa1_ = 1;
$default_animation_time_1 = 400;
$default_speaker_type_1 = 16;
$default_speaker_size_1 = 1;
$default_tooltip_1 = 'black';

$default_bcp2 = '#ffffff';
$default_cp2 =  '#3284c7';
$default_bca2 = '#3284c7';
$default_ca2 =  '#ffffff';
$default_spop2 = 80;
$default_spop2_ = 0.8;
$default_spoa2 = 100;
$default_spoa2_ = 1;
$default_animation_time_2 = 300;
$default_speaker_type_2 = 32;
$default_speaker_size_2 = 1;
$default_tooltip_2 = 'dark-midnight-blue';

$default_bcp3 = '#ffffff';
$default_cp3 =  '#fc0000';
$default_bca3 = '#ff3333';
$default_ca3 =  '#ffffff';
$default_spop3 = 90;
$default_spop3_ = 0.9;
$default_spoa3 = 100;
$default_spoa3_ = 1;
$default_animation_time_3 = 400;
$default_speaker_type_3 = 33;
$default_speaker_size_3 = 1;
$default_tooltip_3 = 'sienna';

$default_bcp4 = '#ffffff';
$default_cp4 =  '#0d7300';
$default_bca4 = '#0f8901';
$default_ca4 =  '#ffffff';
$default_spop4 = 90;
$default_spop4_ = 0.9;
$default_spoa4 = 100;
$default_spoa4_ = 1;
$default_animation_time_4 = 400;
$default_speaker_type_4 = 35;
$default_speaker_size_4 = 1;
$default_tooltip_4 = 'apple-green';

$default_bcp5 = '#ffffff';
$default_cp5 =  '#ea7d00';
$default_bca5 = '#ea7d00';
$default_ca5 =  '#ffffff';
$default_spop5 = 70;
$default_spop5_ = 0.7;
$default_spoa5 = 100;
$default_spoa5_ = 1;
$default_animation_time_5 = 400;
$default_speaker_type_5 = 20;
$default_speaker_size_5 = 1;
$default_tooltip_5 = 'carrot-orange';

/******************************
* includes
******************************/

include('includes/data-processing.php');

// get gspeech data
$sql_g = "SELECT * FROM ".$wpdb->prefix."gspeech_data";
$row_g = $wpdb->get_row($sql_g);
$lazy_load = intval($row_g->lazy_load);
$gsp_widget_id = $row_g->widget_id;
$gsp_crypto = $row_g->crypto;
$gsp_reload_session = intval($row_g->reload_session);
$version_index_1 = intval($row_g->version_index);
$gsp_user_email = $row_g->email;
$gsp_user_email = $row_g->email;
$wpgs_load_sh = intval($row_g->sh_w_loaded);
$sh_ = intval($row_g->sh_);

$use_old_plugin = isset($wpgs_options['use_old_plugin']) ? $wpgs_options['use_old_plugin'] : 0;

$gsp_widget_id = $use_old_plugin == 1 ? '' : $gsp_widget_id;

$s_enc = "";
$h_enc = "";
$hh_enc = "";

if($gsp_reload_session == 1)
    $_SESSION['gsp_index_s'] = '';

if($gsp_crypto != "" && function_exists('sodium_crypto_sign_detached')) {

    if(!isset($_SESSION['gsp_index_s']) || $_SESSION['gsp_index_s'] == '') {

        $gsp_crypto_pk = hex2bin($gsp_crypto);

        $magic_str = "Simon you are great!";

        $h_enc = bin2hex(random_bytes(32));

        $s_enc = sodium_crypto_box_seal($magic_str, $gsp_crypto_pk);
        $s_enc = bin2hex($s_enc);

        $hh_enc = sodium_crypto_box_seal($h_enc, $gsp_crypto_pk);
        $hh_enc = bin2hex($hh_enc);

        $_SESSION['gsp_index_s'] = $s_enc;
        $_SESSION['gsp_index_h'] = $h_enc;
        $_SESSION['gsp_index_hh'] = $hh_enc;

    }
    else {
        $s_enc = $_SESSION['gsp_index_s'];
        $h_enc = $_SESSION['gsp_index_h'];
        $hh_enc = $_SESSION['gsp_index_hh'];
    }
}

if(isset($_GET['act']) && $_GET['act'] == 'gs_submit_data') {
    
    if(isset($_GET['holder']) && $_GET['holder'] == 'gs_ajax')
        include('includes/admin/gs_ajax.php');
    
    exit();
}
include('includes/scripts.php');
include('includes/display-functions.php');
include('includes/admin-page.php');

// 3.1.4 fix
if (!$session_started) {
    session_write_close();
}

function wpgs_on_uninstall() {

    include('includes/install/uninstall.sql.php'); // uninstall
}

function wpgs_on_install() {

    // include('includes/install/install.sql.php'); // install
}

register_uninstall_hook(__FILE__, 'wpgs_on_uninstall');
register_activation_hook(__FILE__, 'wpgs_on_install');
