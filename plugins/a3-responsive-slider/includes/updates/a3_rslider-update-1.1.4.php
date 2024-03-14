<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$sql = "ALTER TABLE ". $wpdb->prefix . "a3_rslider_images ADD `show_readmore` tinyint(1) NOT NULL default 0 AFTER `img_link` ;";
$wpdb->query($sql);