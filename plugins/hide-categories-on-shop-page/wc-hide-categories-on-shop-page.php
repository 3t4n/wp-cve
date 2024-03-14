<?php
/**
 * @link              https://www.matrixwebdesigners.com/
 * @since             1.0.0
 * @package           wc_hide_categories
 *
 * @wordpress-plugin
 * Plugin Name:       Hide WooCommerce Categories On Shop Page
 * Plugin URI:        https://www.matrixwebdesigners.com/plugins/woocommerce-hide-categories-shop-page/
 * Description:       Simple solution to hide specific categories in you woocommerce shop main page i.e. domain.com/shop This plugin was based on WC Hide Categories On Shop Page located at https://wordpress.org/plugins/wc-hide-categories-on-shop-page/ However it did not work and wasn't maintained.
 * Version:           1.1.3
 * Author:            Matix Web Designers
 * Author URI:        https://www.matrixwebdesigners.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       hwcosp
 * Domain Path:       /languages
 *
 * WC requires at least: 2.2
 * WC tested up to: 3.9.2
 */

// If this file is called directly, abort.

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit; 

/**
 * Check if WooCommerce is active, if its not then we dont need this plugin
 **/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    
/**
* Create the section beneath the products tab
* Using WooCommerce Hooks and Filters
*
* Documentation: https://docs.woocommerce.com/document/adding-a-section-to-a-settings-tab/
**/
	
//  Adds "Hide Categories On Shop Page" beneath the products tab
	add_filter( 'woocommerce_get_sections_products', 'mwd_hide_woocommerce_categories_hide_category_setting_section' );
	
	function mwd_hide_woocommerce_categories_hide_category_setting_section( $sections ) {
	
		$sections['mwd_hide_woocommerce_categories_product_settings_section'] = __( 'Hide Categories On Shop Page', 'hwcosp' );
		
		return $sections;
	
	}
	
// Add WooCommerce  Setting For excluding Category From Shop Page
	add_filter( 'woocommerce_get_settings_products', 'mwd_hwcosp_add_wc_exclude_setting', 10, 2 );
	
	function mwd_hwcosp_add_wc_exclude_setting( $settings ,  $current_section) {
	
		$settings_url = array();
	       
		// Checks to make sure this is the section we want
		if( $current_section == 'mwd_hide_woocommerce_categories_product_settings_section'){
		
			$settings_url[] = array( 'name' => __( 'Hide categories on shop page', 'hwcosp' ), 'type' => 'title', 'desc' => __( 'The following options are used to configure Hide categories on shop page', 'hwcosp' ), 'id' => 'hwcosp' );
		
			// Adds the text feild option for user input
			$settings_url[] = array(
				'name'     => __( 'Hide Categories', 'hwcosp' ),
				'desc_tip' => __( 'This will Hide Categories From Shop Page', 'hwcosp' ),
				'id'       => 'hwcosp_global',
				'type'     => 'text',
				'css'      => 'min-width:300px;',
				'desc'     => __( 'Put the categories which are to be excluded <b>eg : abc,xyz</b>', 'hwcosp' ),
			);
			
			$settings_url[] = array( 'name' => __( 'Future Releases', 'hwcosp' ), 'type' => 'title', 'desc' => __( 'I\'m working on an easier solution to select categories and page(s) to hide categories on. Please bare with me.<br><br>If you would like to donate via <a href="https://www.paypal.me/matrixwd" target="_blank">Paypal</a>', 'hwcosp' ), 'id' => 'hwcosp' );
			
			return $settings_url;
		
		}else{
			return $settings;
		}
	}
		
	/**
	 * @param $string - Input string to convert to array
	 * @param string $separator - Separator to separate by (default: ,)
	 *
	 * @return array
	 */
	
	function mwd_hwcosp_comma_separated_to_array($string, $separator = ',')
	{
	  //Explode on comma
	  $vals = explode($separator, $string);
	 
	  //Trim whitespace
	  foreach($vals as $key => $val) {
	    $vals[$key] = trim($val);
	  }
	  //Return empty array if no items found
	  //http://php.net/manual/en/function.explode.php#114273
	  return array_diff($vals, array(""));
	}
	
	// Now that we setup all the WooCommerce stuff and the user inputed the category/categories to hide
	// Lets actually hide them now
	
	add_filter( 'get_terms', 'mwd_hwcosp_get_subcategory_terms', 10, 3 );

	function mwd_hwcosp_get_subcategory_terms( $terms, $taxonomies, $args ) {
		
		//hwcosp_global is the databse row entry
		$opt_terms = get_option('hwcosp_global');
		
		// Processes our users data to the way we want it from above
		$data = mwd_hwcosp_comma_separated_to_array($opt_terms);
		
		// Sets an empty array() for later use
		$new_terms = array();
	
/*
	if a product category and on the shop page
	to hide from shop page, replace is_page('YOUR_PAGE_SLUG') with is_shop()
	if ( in_array( 'product_cat', $taxonomies ) && ! is_admin() && is_page('YOUR_PAGE_SLUG') ) {
	
	Test some variations user may have for their shop
	ToDo make a dropdown select of current pages
	
	If you need a different page just uncomment out remove // in front of $mwd_opt4 and
	Then insert your page slug << -- This needs done in a more appropriate way for multisite users i.e. page select or text input
*/
		
		$mwd_opt1 = in_array( 'product_cat', $taxonomies ) && ! is_admin() && is_shop();
		$mwd_opt2 = in_array( 'product_cat', $taxonomies ) && ! is_admin() && is_front_page();
		$mwd_opt3 = in_array( 'product_cat', $taxonomies ) && ! is_admin() && is_home();
		//$mwd_opt4 = in_array( 'product_cat', $taxonomies ) && ! is_admin() && is_page('YOUR_PAGE_SLUG'),
		/*
			$mwd_opt5 Lets you hide the category everywhere
		*/
		$mwd_opt5 = in_array( 'product_cat', $taxonomies ) && ! is_admin();
		
			if ( $mwd_opt1 || $mwd_opt2 || $mwd_opt3 /*|| $mwd_opt4*/ || $mwd_opt5 ) {
			
			// Foreach fix provided by @madmax4ever Thank you :)
			// https://wordpress.org/support/topic/error-in-log-about-null-slug/#post-16208609
			
				foreach ( $terms as $key => $term ) {
					//1.1.3
					// Use get_term to get a WP_Term object from wether WP_Term object or term_id
					$termobj = get_term($term);
					//1.1.3
					if ( ! in_array( $termobj->slug, $data ) ) {
						
						$new_terms[] = $term;
					}
				}
				
				$terms = $new_terms;
			}
		
		return $terms;
		
	}
	
	// Uncomment the function below if you also want those products hidden
/*
	add_action( 'woocommerce_product_query', 'mwd_hwcosp_remove_product_in_cat' );
	
	function mwd_hwcosp_remove_product_in_cat( $q ) {
		//hwcosp_global is the databse row entry
		$opt_terms = get_option('hwcosp_global');
		
		// Processes our users data to the way we want it from above
		$data = mwd_hwcosp_comma_separated_to_array($opt_terms);
		
		$tax_query = (array) $q->get('tax_query');
		$tax_query[] = array(
							 'taxonomy' => 'product_cat',
							 'field' => 'slug',
							 'terms' => $data, // Set Category Slug which products not show on the shop and Archieve page.
							 'operator' => 'NOT IN'
							);
		$q->set( 'tax_query', $tax_query );
	}
*/
	
} else {
	add_action( 'admin_notices', 'mwd_hide_woocommerce_categories_admin_notice' );
}

/* Admin notice if WooCommerce is not installed or active */
function mwd_hide_woocommerce_categories_admin_notice(){
    echo '<div class="notice notice-error">';
    echo     '<p>'. _e( 'WC Hide Categories On Shop Page requires an active WooCommerce Installation!', 'hwcosp' ).'</p>';
    echo '</div>';
}