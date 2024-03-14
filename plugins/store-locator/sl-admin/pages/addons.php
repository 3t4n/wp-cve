<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//4/14/17 5:27:27p - last saved

//include("variables.sl.php");
include_once(SL_INCLUDES_PATH."/top-nav.php");
?>
<div class='wrap'>
<?php 
if (empty($_GET['pg'])) {
	if (function_exists("do_sl_addons_settings")){ 
		do_sl_addons_settings(); 
	} elseif (function_exists("do_sl_premium_pricing")) {
		do_sl_premium_pricing();  //first update since 7/21/13 6:19:13 PM
	}
} elseif (!empty($_GET['pg'])) {
	if (function_exists("do_sl_addons_{$_GET['pg']}")){ call_user_func("do_sl_addons_{$_GET['pg']}"); }
}

?>
</div>
<?php include(SL_INCLUDES_PATH."/sl-footer.php"); ?>