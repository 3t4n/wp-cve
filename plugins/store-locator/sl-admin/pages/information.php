<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//9/6/13 12:36:02p - last saved

if (empty($_GET['pg'])) {
	include(SL_PAGES_PATH."/news-upgrades.php");
} else {
	$the_page = SL_PAGES_PATH."/". sanitize_text_field($_GET['pg']).".php";
	if (file_exists($the_page)) {
		include($the_page);
	}
}
?>