<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$sql = "ALTER TABLE ". $wpdb->prefix . "a3_rslider_images ADD `img_alt` blob AFTER `img_description` ;";
$wpdb->query($sql);