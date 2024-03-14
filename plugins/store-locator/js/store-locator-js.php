<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Last saved: 4/7/16 2:40:31a
ob_start("sl_js_out");
header("Content-type: text/javascript");
print "/*sl-dyn-js-start*/<?php";
print sl_dyn_js();
print "?>/*sl-dyn-js-end*/";
ob_end_flush();
//var_dump($_GET);
?>