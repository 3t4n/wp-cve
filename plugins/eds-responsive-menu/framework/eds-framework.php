<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

require_once plugin_dir_path( __FILE__ ) .'/eds-framework-path.php';
if( ! function_exists( 'eds_framework_init' ) && ! class_exists( 'EDSFramework' ) ) {
  function eds_framework_init() {

    // active modules
  defined( 'eds_ACTIVE_FRAMEWORK' )  or  define( 'eds_ACTIVE_FRAMEWORK',  true );

	// helpers
	eds_locate_template( 'functions/deprecated.php'     );
	eds_locate_template( 'functions/fallback.php'       );
	eds_locate_template( 'functions/helpers.php'        );
	eds_locate_template( 'functions/actions.php'        );
	eds_locate_template( 'functions/enqueue.php'        );
	eds_locate_template( 'functions/sanitize.php'       );
	eds_locate_template( 'functions/validate.php'       );
	
	// classes
	eds_locate_template( 'classes/abstract.class.php'   );
	eds_locate_template( 'classes/options.class.php'    );
	eds_locate_template( 'classes/framework.class.php'  );
	if ( file_exists(EDS_MENU_DIR . 'inc/admin-menu.config.php') ) :
		require_once EDS_MENU_DIR . 'inc/admin-menu.config.php';
	endif;
  }
  add_action( 'init', 'eds_framework_init', 10 );
}
