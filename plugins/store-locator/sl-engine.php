<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !empty($_GET['f']) ) {
	$translate = preg_replace("@_@", "/", $_GET['f'] . ".php");
	if (file_exists($translate)) {
		include($translate);
	}
}

?>