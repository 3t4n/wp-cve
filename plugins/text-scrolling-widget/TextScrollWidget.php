<?php
/*
* Plugin Name: Text Scroll Widget
* Plugin URI: https://jiteshgondaliya.wordpress.com/
 * Description: Text Scroll Vertically or Horizontally Widget.
 * Version: 2.4
 * Author: Jitesh Gondaliya
 * Author URI: https://jiteshgondaliya.wordpress.com/
 * License: A "Slug" license name e.g. GPL2
 * Network: false
 * License: A short license name. Example: GPL2
 */

$version = '2.4';

if (!class_exists("textScrollingAdmin")) {
    require_once dirname( __FILE__ ) . '/includes/TextScrollingAdmin.php';
    $gb = new TextScrollingAdmin (__FILE__, $version);
}
?>
