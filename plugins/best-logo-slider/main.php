<?php
/**
Plugin Name: Best Logo Slider
Plugin URI: https://wpbean.com/downloads/best-logo-slider-wordpress-plugin/
Description: Best logo slider plugin in wordpress history. Fiew days ago I was searching for a client logo slider but I didn't got any plugin that fill my requirements. So I take a decision to make a slider plugin that can fill all of the requirements that a user need.
Author: wpbean
Version: 1.0.6
Author URI: https://wpbean.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//--------- All scripts & style file---------------- //

require_once dirname( __FILE__ ) . '/wpb-scripts.php';

//--------- Aqua-Resizer by syamilmj ---------------- //

require_once dirname( __FILE__ ) . '/aq_resizer.php';

//--------- Aqua-Resizer by syamilmj ---------------- //

require_once dirname( __FILE__ ) . '/wpb_metabox.php';

//--------- setup widgets for this plugin ---------------- //

require_once dirname( __FILE__ ) . '/wpb-bls-shortcodes.php';

// Support for feature image
add_theme_support( 'logo_slider' );