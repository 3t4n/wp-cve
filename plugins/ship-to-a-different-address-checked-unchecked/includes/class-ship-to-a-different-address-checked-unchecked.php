<?php
/**
 * Class for Custom Text Field support.
 *
 * @package WordPress
 */

// If check class exists.
if ( ! class_exists( 'WP_NGD_Ship_Different_Address_Checked_Class' ) ) {

	/**
	 * Declare class.
	 */
	class WP_NGD_Ship_Different_Address_Checked_Class {

		/**
		 * Calling construct.
		 */
		public function __construct() {
			
			add_action( 'admin_init', array( $this, 'wp_ngd_woocommerce_ship_to_a_different_address_admin_init_fields') );
			add_action( 'admin_menu', array( $this, 'wp_ngd_woocommerce_ship_to_a_different_address_register_sub_menu') );

			if( get_option( 'wp_ngd_ship_option_checked' ) && get_option( 'wp_ngd_ship_option_checked' ) == 1 ){
			 	add_filter('woocommerce_ship_to_different_address_checked', array( $this, 'wp_ngd_woocommerce_ship_to_a_different_address_checked_function' ) );
			 }else{
			 	add_filter('woocommerce_ship_to_different_address_checked', array( $this, 'wp_ngd_woocommerce_ship_to_a_different_address_unchecked_function' ) );
			}

		}

		public function wp_ngd_woocommerce_ship_to_a_different_address_admin_init_fields(){
			add_settings_section("wp-ngd-section", __( 'Settings', 'ship-to-a-different-address-checked-unchecked' ), null, "ship-to-a-different-address-checked-unchecked");
			add_settings_field('wp_ngd_ship_option_checked', "", array( $this, 'wp_ngd_woocommerce_ship_checked'), 'ship-to-a-different-address-checked-unchecked', "wp-ngd-section");
			register_setting("wp-ngd-section", "wp_ngd_ship_option_checked");
		}

		public function wp_ngd_woocommerce_ship_checked(){
			if( get_option( 'wp_ngd_ship_option_checked' ) && get_option( 'wp_ngd_ship_option_checked' ) == 1 ){
				$checked = 'checked';
			}else{
				$checked = '';
			}
			?>
		    <input type="checkbox" name="wp_ngd_ship_option_checked" id="wp_ngd_ship_option_checked" value="1" <?php echo $checked; ?> /><?php _e( 'Ship to a different address?', 'ship-to-a-different-address-checked-unchecked' ); ?>
		    <?php
			
		}

		public function wp_ngd_woocommerce_ship_to_a_different_address_register_sub_menu(){
			$hook = add_submenu_page( 'woocommerce', __( 'Ship to a different address?', 'ship-to-a-different-address-checked-unchecked' ), __( 'Ship to a different address?', 'ship-to-a-different-address-checked-unchecked' ), 'manage_options', 'ngd-ship-to-a-different', array( $this, 'wp_ngd_woocommerce_ship_to_a_different_address_submenu_page_callback') );
		}

		public function wp_ngd_woocommerce_ship_to_a_different_address_submenu_page_callback() {
			if ( ! current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
	        echo '<div class="wrap">';
	        echo '<h2>'. esc_html( get_admin_page_title() ) .'</h2>';
		        ?>
		        <form method="post" action="options.php">
		        <?php
		            settings_fields("wp-ngd-section");
		            do_settings_sections("ship-to-a-different-address-checked-unchecked");	          
		            submit_button(); 
		            ?>
		   		 </form>
		        <?php
	        echo '</div>';
	    }

		public function wp_ngd_woocommerce_ship_to_a_different_address_checked_function(){
			add_filter('woocommerce_ship_to_different_address_checked', '__return_true', 999 );
		}

		public function wp_ngd_woocommerce_ship_to_a_different_address_unchecked_function(){
			add_filter('woocommerce_ship_to_different_address_checked', '__return_false', 999 );
		}
		

	}
}