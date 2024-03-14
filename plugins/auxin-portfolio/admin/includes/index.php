<?php // load admin related classes & functions

// load admin related functions
include_once( 'admin-the-functions.php' );

// load admin related classes
include_once( 'classes/class-auxpfo-admin-assets.php'  );

do_action( 'auxpfo_admin_classes_loaded' );

include_once( 'metaboxes/metabox-fields-portfolio.php' );

// load admin related functions
include_once( 'admin-hooks.php' );
