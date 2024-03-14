<?php
/**
 * Plugin Name:       Product Info Request for WooCommerce
 * Plugin URI:        http://www.miologo.it/prodotto/product-info-request/
 * Description:       Send info product request with a form ( Contact Form 7 shortcode) in single product.
 * Version:           1.0
 * Author:            Pasquale Bucci
 * Author URI:        http://www.webartsdesign.it/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*  Copyright 2015  Pasquale Bucci  (email : paky.bucci@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


/**
 * Option module
 */
add_action( 'admin_menu', 'pirw_add_admin_menu' );
add_action( 'admin_init', 'pirw_settings_init' );

function pirw_add_admin_menu(  ) { 
	add_menu_page( 'Product Info Request', 'Product Info Request', 'manage_options', 'product_info_request', 'pirw_options_page' );
}

function pirw_settings_init(  ) { 

	register_setting( 'pluginPage', 'pirw_settings' );

	add_settings_section(
		'pirw_pluginPage_section', 
		'', 
		'', 
		'pluginPage'
	);

	add_settings_field( 
		'pirw_text_field_0', 
		'Contact Form 7 shortcode', 
		'pirw_text_field_0_render', 
		'pluginPage', 
		'pirw_pluginPage_section' 
	);

}

function pirw_text_field_0_render(  ) { 

	$options = get_option( 'pirw_settings' );
	$opt1='';
	if (isset($options['pirw_text_field_0'])) {
		$opt1=$options['pirw_text_field_0'];
	}
	?>
	<input type='text' name='pirw_settings[pirw_text_field_0]' value='<?php echo $opt1; ?>'>
	<?php

}

function pirw_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Product Info Request for WooCommerce</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
	/**
	* Add Contact Form 7 shortcode
	**/ 
	add_action( 'woocommerce_single_product_summary', 'pirw_contact', 35 );
	function pirw_contact() {
		$options = get_option( 'pirw_settings' );
		if (isset($options['pirw_text_field_0']) && $options['pirw_text_field_0']!='') {
			echo do_shortcode($options['pirw_text_field_0']);
		}
	}
	
	/**
	* Add Email subject: product name & product id
	**/ 
	add_action( 'wpcf7_before_send_mail', 'pirw_add_subject' );
	function pirw_add_subject($contact_form) {
		$submission = WPCF7_Submission::get_instance();
		$url = $submission->get_meta( 'url' );
		$postid = url_to_postid( $url );
		$prodotto = get_the_title( $postid );
		$soggetto = 'Info Request for Product: '.$prodotto.' (ID: '.$postid.')';
		$mail = $contact_form->prop( 'mail' );
		$mail['subject'] = $soggetto;
		$contact_form->set_properties( array( 'mail' => $mail ) );
	}
	
}
?>