<?php 

namespace app;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * SimpleSEO class. Basically a wrapper class to initialize everything.
 *
 * @since 2.0.0
 */
class SimpleSEO {

	/**
 	 * Initialize
 	 *
 	 * @since 2.0.0
 	 */
    function __construct() {
    	add_action('init', [$this, 'init']);
    }

	/**
 	 * Initialize front end and administration functionality.
 	 *
 	 * @since 2.0.0
 	 */
    function init() {
		new init\init();
		new Admin\Admin();
	}

}

?>