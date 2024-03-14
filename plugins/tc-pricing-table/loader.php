<?php
/*
Require  all lib files , functions

*/
 require_once(plugin_dir_path( __FILE__ ).'/lib/tc-pricing-table-cpt.php' );
 require_once(plugin_dir_path( __FILE__ ).'/public/tc-pricing-table-view.php' );
 // Add the metabox class (CMB2)
 if ( file_exists( dirname( __FILE__ ) . '/lib/metaboxes/init.php' ) ) {
     require_once dirname( __FILE__ ) . '/lib/metaboxes/init.php';
 } elseif ( file_exists( dirname( __FILE__ ) . '/lib/metaboxes/init.php' ) ) {
     require_once dirname( __FILE__ ) . '/lib/metaboxes/init.php';
 }

 // Create the metabox class (CMB2)
 require_once('lib/functions/tc-pricing-table-metaboxes.php');
 // Enqueue admin styles
 require_once('lib/functions/tc-pricing-table-scripts.php');
 require_once('lib/functions/tc-pricing-table-customcolumn.php');
 ?>
