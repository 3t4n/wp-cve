<?php
/*
 * Bismillahirrahmanirrahim
 * @jQuery Popup
 * @since 1.3.1
*/ 

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('L�tfen Olmuyor B�yle'); }
if(is_admin()) {
	include_once 'admin.init.php';
}
else {
	include_once 'init.php';
}

?>