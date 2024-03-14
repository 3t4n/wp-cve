<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//12/10/13 3:45:02a - last saved

if (empty($_POST)) {sl_move_upload_directories();}
if (empty($_GET['pg'])) {
	include(SL_PAGES_PATH."/manage-locations.php");
} else {
	$the_page = SL_PAGES_PATH."/". sanitize_text_field($_GET['pg']) .".php";
	if (file_exists($the_page)) {
		include($the_page);
	}
}
?>