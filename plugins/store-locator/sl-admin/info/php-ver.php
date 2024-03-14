<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//10/22/13, 3:19:50a - last saved
$is_included_pv=(basename(__FILE__) != basename($_SERVER['SCRIPT_FILENAME']) )? true : false;

if ($is_included_pv) {
	print "Current PHP Version is ".phpversion(); 
}
?>