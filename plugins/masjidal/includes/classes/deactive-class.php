<?php
/** @wordpress-plugin
 * Author:            Masjidal 
 * Author URI:        http://www.masjidal.com/
 */

namespace masjidal_namespace;

class MPSTI_Plugin_Deactivator {
	/* De-activate Class */
	public static function deactivate() {
		/* Delete Table And Post type*/
	global $wpdb;	
	
		delete_option( 'widget_mpsti_wpb_widget' );
		  	
	}
}