<?php
/*
* Plugin Name: Export All Post Meta
* Plugin URI: http://brainvire.com
* Description: Export WordPress post with all serialized post meta in readable in CSV format.
* Version: 1.1
* Author: brainvireinfo
* Author URI: http://brainvire.com
* License: GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once('export-all-post-meta-class.php');

$ExportPost = new brainspace\ExportPost();