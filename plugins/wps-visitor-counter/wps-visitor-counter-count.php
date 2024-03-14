<?php
function wps_enqueue_scripts() {
		global $wp;
		if ( !is_admin() ) {
		  wp_enqueue_script( 'wps', plugin_dir_url( __FILE__ ) . 'styles/js/custom.js', array( 'jquery' ),'1', false );
		$params = array(
		  'ajaxurl' => admin_url('admin-ajax.php'),
		  'ajax_nonce' => wp_create_nonce('wps-nonce'),
		);
		wp_localize_script( 'wps', 'wpspagevisit', $params);  
		}
		
		wp_enqueue_style( 'wps-visitor-style', plugin_dir_url( __FILE__ ).'styles/css/default.css', array(),'2' );

	}

	
add_action( 'wp_enqueue_scripts', 'wps_enqueue_scripts',100 );
add_action( 'admin_enqueue_scripts', 'wps_enqueue_scripts',100 );



add_action('wp_ajax_wps_count_page_visit', 'wps_count_page_visit');
add_action('wp_ajax_nopriv_wps_count_page_visit', 'wps_count_page_visit');
function wps_count_page_visit() {
	check_ajax_referer( 'wps-nonce', 'nonce' );
	global $wpdb;
	$ip = wps_getRealIpAddr(); // Getting the user's computer IP
	$date = date("Y-m-d"); // Getting the current date
	$waktu = time();
	$sql = $wpdb->query( $wpdb->prepare("INSERT INTO `". WPS_VC_TABLE_NAME . "`(`ip`, `date`, `views`, `online`) VALUES(%s, %s, %d, %s) ON DUPLICATE KEY UPDATE `views` = `views` +1, `online` = %s;",$ip, $date, 1, $waktu,$waktu ));
wp_die();
}
?>