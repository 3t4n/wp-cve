<?php
/**
    * Plugin Name: Bookvault
    * Description: Bookvault plugin for Woocommerce
    * Version: 3.4.0
    * License:     GPL v3
    * Requires at least: 5.6
    * Requires PHP: 5.6.20
    * Text Domain: bookvault
    * WC requires at least: 3.0
    * WC tested up to: 6.3
    *
    * This program is free software: you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation, either version 3 of the License, or
    * (at your option) any later version.
    *
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
    *
    * You should have received a copy of the GNU General Public License
    * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('bvlt_plugin_name', 'Bookvault');
define('bvlt_icon_image', 'data:image/svg+xml;base64,PHN2ZyBpZD0iR3JvdXBfMTM1OCIgZGF0YS1uYW1lPSJHcm91cCAxMzU4IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0NzEuNTMzIiBoZWlnaHQ9IjM4Mi44OTQiIHZpZXdCb3g9IjAgMCA0NzEuNTMzIDM4Mi44OTQiPg0KICA8cGF0aCBpZD0iUGF0aF84MTQ0IiBkYXRhLW5hbWU9IlBhdGggODE0NCIgZD0iTTE2MS40NzMsNTNTODQuMjczLDEwMC42NDcsMCwxMTcuOTE4YzAsMCw3OC41MTYsMzUuNzQ5LDE1NS4xNjcsMjUuNTUxbDU4LjA2NC00NC4xOTNMMTg4LjMzOSw3Ni40MTJaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwIDIzNy41OTYpIiBmaWxsPSIjYWVkMWVkIi8+DQogIDxwYXRoIGlkPSJQYXRoXzgxNDUiIGRhdGEtbmFtZT0iUGF0aCA4MTQ1IiBkPSJNNi4xNywxMzcuMDczUzE5MS4zMjksMjY5LjkyNSwxMjcuNTA3LDM4MS4wNjRjMCwwLDIyNi4xMTYtMy43MjgsMzE2LjE0Ni0zODEuMDY0QzQ0My44NzMsMCwzNDQuNDEyLDE0NS45NTYsNi4xNywxMzcuMDczWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMjcuNjYpIiBmaWxsPSIjOTRjMmU3Ii8+DQogIDxwYXRoIGlkPSJQYXRoXzgxNDYiIGRhdGEtbmFtZT0iUGF0aCA4MTQ2IiBkPSJNMTMzLjgxMywyOTAuNmgwQzM4MS40NzcsMTY0Ljg3Miw0NDMuODczLDAsNDQzLjg3MywwUzM0NC40MTIsMTQ1Ljk1Niw2LjE3LDEzNy4wNzNDNi4xNywxMzcuMDczLDEwNi4wNjksMjA4Ljc5LDEzMy44MTMsMjkwLjZaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgyNy42NikiIGZpbGw9IiM3YmIzZTIiLz4NCjwvc3ZnPg0K');


include "core/admin/admin.php";

function bvlt_add_menu()
{
    add_menu_page(  bvlt_plugin_name, bvlt_plugin_name, 'administrator', bvlt_plugin_name,  'bvlt_init_admin' , bvlt_icon_image, 26 );
}

add_action('admin_menu','bvlt_add_menu', 9);    


function bvlt_get_shipping_rates($body)
{
    $body = wp_json_encode( $body );

    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json'
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];
    $endpoint = "https://webhooks.bookvault.app/woocommerce/shipping?storeUrl=" . get_site_url()."/&currency=".get_woocommerce_currency();
    $shipping = wp_remote_post( $endpoint, $options ); 
    $cur_data = wp_remote_retrieve_body($shipping);
    if(bvlt_is_json($cur_data))
    {
        return json_decode($cur_data, true);
    }
    return $cur_data;
}

///########## Shipping ##################


add_action('woocommerce_shipping_init', 'bvlt_woocommerce_init_shipping_rate');
add_filter( 'woocommerce_shipping_methods', 'bvlt_add_methods' );

function bvlt_woocommerce_init_shipping_rate()
{
	class bvlt_shipping_methods extends WC_Shipping_Method {
		/**
		 * Constructor for your shipping class
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->id                 = 'BookVAULT Shipping'; // Id for your shipping method. Should be uunique.
			$this->method_title       = __( 'BookVAULT Shipping' );  // Title shown in admin
			$this->method_description = __( 'Shipping For Your BookVAULT Account' ); // Description shown in admin

			$this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled

			$this->init();
		}

		/**
		 * Init your settings
		 *
		 * @access public
		 * @return void
		 */
		function init() {
			// Load the settings API
			$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
			$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

			// Save settings in admin if you have any defined
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		/**
		 * calculate_shipping function.
		 *
		 * @access public
		 * @param mixed $package
		 * @return void
		 */
		public function calculate_shipping( $package = array() ) {
			$transaction_lines = array();
			$skus = array();
			foreach( WC()->cart->get_cart() as $cart_item ) :
				$product_id = $cart_item['product_id'];
				$product_variation_id = $cart_item['variation_id'];
				if (intval($product_variation_id) > 0) { 
					$product = new WC_Product_Variation(intval($product_variation_id));
				} else {
					$product = $cart_item['data'];
				}
				$sku =  $product->get_sku();
				
				if (strlen($sku) == 13) {
					$transaction_lines[] = [ "ISBN" => $sku, "Quantity" => $cart_item['quantity']];													
				}
			endforeach;
			
			
			$get_ship_service = [
				"OrderLines" => $transaction_lines, 
				"CountryCode" => $package["destination"]["country"], 
				"ServiceLevel" => "NotSpecified", 
				"AreaCode" => $package["destination"]["postcode"]
			]; 
			
			$total_services = bvlt_get_shipping_rates($get_ship_service);
			
			if(isset($total_services['Services'])) {
				$services_arr = $total_services['Services'];
				$fed_id = 0;
				
				foreach($services_arr as $serv)
				{
					$rate = array(
						'id' 	   => $serv['ServID'],
						'label'    => $serv['ServName'] . " - " . $serv['ServDetail'],
						'cost'     => $serv['DelTotal'],
						'calc_tax' => 'per_item'
					);
					$this->add_rate( $rate );
				}
			}
		}
	}
}

function bvlt_add_methods( $methods ) {
	$methods['your_shipping_method'] = 'bvlt_shipping_methods';
	return $methods;
}


 function bvlt_is_json($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
 }
 
 // Add a custom metabox only for shop_order post type (order edit pages)
add_action( 'add_meta_boxes', 'bvlt_add_meta_boxes' );
function bvlt_add_meta_boxes()
{
    global $post;
    $order_id = $post->ID;
    if(get_post_type($order_id) == "shop_order"){
        $order = wc_get_order($order_id);
        if ( $order->meta_exists( 'BVRef' ) ) {
            if (get_option("bvlt_auth") == 1) {
				add_meta_box( 'custom_order_meta_box', __( 'bookvault' ),
					'bvlt_order_meta', 'shop_order', 'side', 'high');    
			}
        }
    }
    if(get_post_type($order_id) == "product"){
        if (get_option("bvlt_auth") == 1) {
			add_meta_box( 'custom_order_meta_box', __( 'bookvault' ),
					'bvlt_product_meta', 'product', 'side', 'high');    
		}
    }
}

function bvlt_product_meta(){
    global $post;
    $product = wc_get_product($post->ID);
    ?>
        <center>
		<img src = "<?php echo esc_attr(plugins_url( 'assets/img/bv-logo-long.png', __FILE__ )); ?>" style="width: 80%;">
        <h3 style="margin:0px;">Fulfillment</h3>
            <?php
                $concatenated_attributes = "";
        		$col_value = "";
        		
        		$hasBv = True;
        		$notBv = False;
        
                $locations = '[{"LocationID": 1, "Name": "Bookvault UK"},{"LocationID": 3, "Name": "Bookvault US"}]';
				$bvltPartners = json_decode($locations);
        		if ($product && $product->is_type('variable')) {
        			// Get the available variations
        			$variations = $product->get_available_variations();
        			// Loop through the variations
        			foreach ($variations as $var) {
        				$variation_id = $var['variation_id'];
        				$variation = wc_get_product($variation_id);

                        if (is_a($variation, 'WC_Product_Variation')) {
                            // Get the variation attributes
                            $attributes = $variation->get_variation_attributes();
                        
                            // Initialize a variable to store the concatenated attributes
                            $concatenated_attributes .= '<div style="text-align: left"><b>#' . $variation_id . '</b>';
                        
                            // Loop through the variation attributes and append them to the variable
                            $variations = "";
                            foreach ($attributes as $attribute_name => $attribute_value) {
                                $variations .= ' | ' . $attribute_value . ', ';
                            }
                            $concatenated_attributes .= substr($variations, 0, -2).'<hr>';
                            
							$partLink = get_post_meta($variation_id, 'bvlt_locations', true);
							if ($partLink) {
								// Parse the metadata value as JSON
								$parsed_data = json_decode($partLink);

								if (json_last_error() === JSON_ERROR_NONE) {
									// Successfully parsed as JSON
									// Now you can work with the $parsed_data object or array
									foreach ($bvltPartners as $partner) {
										$checked = "";
										if (in_array($partner->LocationID, $parsed_data->locations)) {
											$checked = "checked";
										}
									
										$concatenated_attributes .= '<input '.$checked.' disabled type="checkbox" id="chkPart'.$partner->LocationID.'" name="vehicle1" value="Bike">';
										$concatenated_attributes .= '<label for="chkPart'.$partner->LocationID.'">'.$partner->Name.'</label><br>';
									}
									
								} else {
									// JSON parsing error
									echo 'Failed to parse JSON data.';
								}
							} else {
								foreach ($bvltPartners as $partner) {
									$concatenated_attributes .= '<input disabled type="checkbox" id="chkPart'.$partner->LocationID.'" name="vehicle1" value="Bike">';
									$concatenated_attributes .= '<label for="chkPart'.$partner->LocationID.'">'.$partner->Name.'</label><br>';
								}
							}
							
                            $concatenated_attributes .= '</div><div style="text-align:right;"><a href="https://apps.bookvault.app/woocommerce/BulkProducts?client_id='. get_option("bvlt_token") . '&storeID=' . get_option("bvlt_storeid") .'&ids[]='.$post->ID.'">[Edit]</a></div><br>';
                            
                        }
        			}
        		} else {
					$partLink = get_post_meta($post->ID, 'bvlt_locations', true);
					$concatenated_attributes .= '<div style="text-align: left">';
        			if ($partLink) {
						$parsed_data = json_decode($partLink);
						if (json_last_error() === JSON_ERROR_NONE) {
							foreach ($bvltPartners as $partner) {
								$checked = "";
								if (in_array($partner->LocationID, $parsed_data->locations)) {
									$checked = "checked";
								}
							
								$concatenated_attributes .= '<input '.$checked.' disabled type="checkbox" id="chkPart'.$partner->LocationID.'" name="vehicle1" value="Bike">';
								$concatenated_attributes .= '<label for="chkPart'.$partner->LocationID.'">'.$partner->Name.'</label><br>';
							}
						}
        			} else {
						foreach ($bvltPartners as $partner) {
							$concatenated_attributes .= '<input disabled type="checkbox" id="chkPart'.$partner->LocationID.'" name="vehicle1" value="Bike">';
							$concatenated_attributes .= '<label for="chkPart'.$partner->LocationID.'">'.$partner->Name.'</label><br>';
						}
					}
					$concatenated_attributes .= '</div><div style="text-align:right;"><a href="https://apps.bookvault.app/woocommerce/BulkProducts?client_id='. get_option("bvlt_token") . '&storeID=' . get_option("bvlt_storeid") .'&ids[]='.$post->ID.'">[Edit]</a></div><br>';
        		}
        		
				$allowedHtml = array(
					'a' => array(
						'href' => array(),
						'title' => array()
					),
					'div' => array(
						'style'=> array(),
					),
					'br' => array(),
					'b' => array(),
					'hr' => array(),
					'input' => array(
						'style'=> array(),
						'disabled'=> array(),
						'type'=> array(),
						'id'=> array(),
						'name'=> array(),
						'checked'=> array(),
						'value'=> array()
					),
					'label' => array(
						'for'=> array(),
					)
				);
				
				
        		echo wp_kses($concatenated_attributes, $allowedHtml);
        		
        		?>
            </center>
    <?php
} 

function bvlt_order_meta(){
    global $post;
    $order_id = $post->ID;
    $order = wc_get_order($order_id);
    ?>
        <center><img src = "<?php echo esc_attr(plugins_url( 'assets/img/bv-logo-long.png', __FILE__ )); ?>" style="width: 80%;">
        <p><a href="https://portal.bookvault.app/order?ID=<?php echo esc_attr($order->get_meta('BVRef')); ?>" target="_blank" class="button"><?php esc_html_e('View Order On Bookvault'); ?></a></p></center>
    <?php
}

// Add custom bulk action to WooCommerce products page
function bvlt_bulk_products_action($actions) {
    if (get_option("bvlt_auth") == 1) {
		$actions['bvlt_add_titles'] = __('Add To Bookvault', 'text-domain');
	}
    return $actions;
}
add_filter('bulk_actions-edit-product', 'bvlt_bulk_products_action');

// Handle the custom bulk action
function bvlt_handle_bulk_product_action() {
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'bvlt_add_titles') {
        $selected_products = isset($_REQUEST['post']) ? $_REQUEST['post'] : array();
        $ids = "";
        foreach ($selected_products as $product_id) {
            // Perform actions on individual products
            // You can access each product's data using $product_id
            $ids = $ids."&ids[]=".sanitize_text_field($product_id);
        }
                
        wp_redirect("https://apps.bookvault.app/woocommerce/BulkProducts?client_id=". get_option("bvlt_token") . "&storeID=" . get_option("bvlt_storeid") . $ids);
        exit();
    }
}
add_action('load-edit.php', 'bvlt_handle_bulk_product_action');


// Add a custom column to the WooCommerce products page
function bvlt_status_column($columns) {
    $new_columns = array();

    // Copy the existing columns
    foreach ($columns as $key => $column) {
        $new_columns[$key] = $column;
    }
	
	if (get_option("bvlt_auth") == 1) {
		//Add New Coloumn
		$new_columns['LinkedToBV'] = __('BV Link', 'text-domain');		
	}

    return $new_columns;
}
add_filter('manage_product_posts_columns', 'bvlt_status_column');

// Populate the custom column with the meta field value
function bvlt_column_fill($column, $post_id) {
    if ($column === 'LinkedToBV') {
        
		$product = wc_get_product($post_id);
		$col_value = "";
		
		$notBv = false;
		$hasBv = false;

		if ($product && $product->is_type('variable')) {
			// Get the available variations
			$variations = $product->get_available_variations();

			// Loop through the variations
			foreach ($variations as $variation) {
				$variation_id = $variation['variation_id'];
				$meta_value = get_post_meta($variation_id, 'bvlt_liked', true);
				
				
				if (!$meta_value || $meta_value != "true") {
					$notBv = True;
				} elseif ($meta_value && $meta_value == "true") {
					$hasBv = True;
				}
			}
		} else {
			$meta_value = get_post_meta($post_id, 'bvlt_liked', true);
			if (!$meta_value||$meta_value == "false") {
				$notBv = True;
			} else {
				$hasBv = True;
			}
		}
		
		if ($hasBv && $notBv) {
			$meta_value = "Partial";
		} elseif (!$notBv && $hasBv)  {
			$meta_value = "Linked";
		} else {
			$meta_value = "Unlinked";
		}
		
        echo esc_html($meta_value);
    }
}
add_action('manage_product_posts_custom_column', 'bvlt_column_fill', 10, 2);


add_action('woocommerce_order_actions', 'bvlt_order_resend', 10, 1 );
function bvlt_order_resend( $actions ) {

	if ( is_array( $actions ) ) {
		$actions['blt_resend_order'] = __( 'Resend Order To Bookvault', 'bookvault' );
	}

	return $actions;

}

/**
 * Filter name is woocommerce_order_action_{$action_slug}
 */
add_action( 'woocommerce_order_action_blt_resend_order', 'bvlt_order_resend_action' );
function bvlt_order_resend_action( $order ) {
	bvlt_resendOrder($order->get_id());
}

// Adding to admin order list bulk dropdown a custom action 'custom_downloads'
add_filter( 'bulk_actions-edit-shop_order', 'bvlt_resend_orders_action', 8, 1 );
function bvlt_resend_orders_action( $actions ) {
    if (get_option("bvlt_auth") == 1) {
		$actions['bvlt_resend_orders'] = __( 'Resend Orders To Bookvault', 'bookvault' );
	}
    return $actions;
}

// Make the action from selected orders
add_filter( 'handle_bulk_actions-edit-shop_order', 'bvlt_bulk_resend_orders', 10, 3 );

function bvlt_bulk_resend_orders( $redirect_to, $action, $post_ids ) {
    if ( $action !== 'bvlt_resend_orders' )
        return $redirect_to; // Exit

    foreach ( $post_ids as $post_id ) {
        bvlt_resendOrder($post_id);
    }

    return $redirect_to = add_query_arg( array('processed_count' => count( $post_ids ),), $redirect_to );
}

// The results notice from bulk action on orders
add_action( 'admin_notices', 'bvlt_admin_notice' );
function bvlt_admin_notice() {
    if ( empty( $_REQUEST['processed_count'] ) ) return; // Exit

    $count = intval( $_REQUEST['processed_count'] );

    printf( '<div id="message" class="updated fade"><p>' .
		esc_html( sprintf(
			_n( 'Resent %s orders to bookvault.',
				'Resent %s orders to bookvault.',
				$count,
				'bvlt_resend_orders'
			),
			$count
		) ) . '</p></div>', esc_html($count) );
}



function bvlt_resendOrder($orderId) {
    $order = wc_get_order($orderId);
	$body = $order->get_data();	
	$body = wp_json_encode($body);
	
    $options = [
        'body'        => $body,
        'headers'     => [
            'Content-Type' => 'application/json'
        ],
        'timeout'     => 60,
        'redirection' => 5,
        'blocking'    => true,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'data_format' => 'body',
    ];
    $endpoint = "https://webhooks.bookvault.app/woocommerce/orders/create?client_id=". get_option("bvlt_token") . "&storeID=" . get_option("bvlt_storeid");
    $shipping = wp_remote_post( $endpoint, $options ); 
    $cur_data = wp_remote_retrieve_body($shipping);
} 

add_action('upgrader_process_complete', 'bvlt_check_plugin_update', 10, 2);

function bvlt_check_plugin_update($upgrader_object, $options) {
    // Check if the plugin being updated is the specific plugin
    $plugin_slug = 'Bookvault'; // Replace with your plugin's slug or folder name
    $updated_plugin = plugin_basename($options['destination']);

    if ($options['action'] === 'update' && $options['type'] === 'plugin' && $updated_plugin === $plugin_slug) {
        if (get_option( "bvlt_auth", "0" ) == 0) {
			$uri = 'https://auth.bookvault.app/api/WooAuth?storeUrl='.get_site_url();
			$response = wp_remote_get( $uri,
			array(
				'timeout'     => 120,
				'httpversion' => '1.1',
			));
			$response = json_decode(wp_remote_retrieve_body($response), true);
			if (array_key_exists("Token", $response)) {
				update_option("bvlt_token", $response["Token"]);
				update_option("bvlt_storeid", $response["StoreID"]);
				update_option("bvlt_auth", $response["Authenticated"]);
			}
		}
    }
}




?>