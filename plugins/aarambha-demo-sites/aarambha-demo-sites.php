<?php

/**
 * Aarambha Demo Sites.
 *
 * @author            AarambhaThemes
 * @copyright         2020 AarambhaThemes
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Aarambha Demo Sites
 * Plugin URI:        https://aarambhathemes.com/
 * Description:       Aarambha Demo Sites - it is the perfect plugin to import already inbuilt theme's demos into your business websites within a click.
 * Version:           1.1.7
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Author:            Aarambha Themes
 * Author URI:        https://aarambhathemes.com
 * Text Domain:       aarambha-demo-sites
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
if (!defined('WPINC')) {
    exit;   // Exit if accessed directly.
}

/* Set constant path to the main file for activation call */
define('AARAMBHA_BOOTSTRAP', __FILE__);

/* Require our constants declaration file */
require_once plugin_dir_path(__FILE__) . 'inc/helpers/constant.php';

/* Load our other helper files. */
require_once AARAMBHA_DS_HELPERS . 'functions.php';

/**
 * Fires immediately after this plugin is activated.
 */
function aarambhaDSActivation()
{
    require_once AARAMBHA_DS_MAIN . 'class-aarambha-ds-activation.php';
    Aarambha_DS_Activation::activate();
}
register_activation_hook(__FILE__, 'aarambhaDSActivation');

/* Load our plugin core file */
require_once AARAMBHA_DS_MAIN . 'class-aarambha-ds.php';

/**
 * The main function responsible for returning the one true
 * Aarambha_DS Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except
 * without needing to declare the global.
 *
 * @since 1.0.0
 *
 * @return Aarambha_DS Aarambha_DS Instance
 */
function Aarambha_DS()
{
    return Aarambha_DS::getInstance();
}

/*
 * Loads the main instance of Aarambha_DS to prevent
 * the need to use globals.
 *
 * @since 1.0.0
 * @return object Aarambha_DS
 */
Aarambha_DS();
