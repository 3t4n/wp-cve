<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 include 'form.php';
 function save_quickgoogleanalytics() {
  adminForm_quickgoogleanalytics();
 }







function QGA_quickgoogleanalytics() {
	add_options_page('Quick Google Analytics', 'Quick Google Analytics', 'manage_options', 'QGA_quickgoogleanalytics', 'save_quickgoogleanalytics');
}
add_action( 'admin_menu', 'QGA_quickgoogleanalytics' );
?>