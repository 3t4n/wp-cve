<?php
/**
 * Plugin Name:       		Oliver POS - A WooCommerce Point of Sale (POS)
 * Description:       		Oliver POS is a WooCommerce Point of Sale (POS) integrated into your shop. Always insync with your e-commerce shop, Oliver POS lets you sell in-store.
 * Version:           		2.4.2.1
 * Author:            		Oliver POS
 * Author URI:        		https://oliverpos.com/
 * License:           		GPL-2.0+
 * License URI:       		http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least:	3.8
 * WC tested up to:			8.6.1
 * Text Domain: 			oliver-pos
 * Domain Path: 			/languages/
 */
if (!defined('ABSPATH')) {
    exit; // Exit if someone accessed directly.
}

// Define WC_PLUGIN_FILE.
if (!defined('OLIVER_POS_PLUGIN_FILE')) {
    define('OLIVER_POS_PLUGIN_FILE', __FILE__);
}

// Include the main WooCommerce class.
if (!class_exists('Pos_Bridge')) {
    include_once dirname(__FILE__) . '/includes/class-pos-bridge.php';
}

/**
 * Main instance of WooCommerce.
 *
 * Returns the main instance of WC to prevent the need to use globals.
 */

function run_pos_bridge() {
    new Pos_Bridge();
}

// Call the above function to begin execution of the plugin.
run_pos_bridge();

function oliver_pos_load_plugin_textdomain() {
    load_plugin_textdomain('oliver-pos', false, basename(dirname( __FILE__ ) ) . '/languages/');
}

add_action('plugins_loaded', 'oliver_pos_load_plugin_textdomain');

//Display Fields
add_action('woocommerce_product_after_variable_attributes', 'oliver_pos_register_variation_product_cost_field', 10, 3);
//Save variation fields
add_action('woocommerce_save_product_variation', 'oliver_pos_save_variation_product_cost_field', 10, 2);

function oliver_pos_register_variation_product_cost_field($loop, $variation_data, $variation) {
    // Text Field
    woocommerce_wp_text_input(
        array(
            'id' => 'var_product_cost[' . $loop . ']',
            'label' => __('Variation Product Cost (' . get_woocommerce_currency_symbol() . ')', 'woocommerce'),
            'placeholder' => 'Enter Variation Product Cost',
            'desc_tip' => 'true',
            'description' => __('Enter Variation Product Cost value here.', 'woocommerce'),
            'value' => esc_attr(get_post_meta($variation->ID, 'var_product_cost', true)),
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'var_product_barcode[' . $loop . ']',
            'label' => __('Variation Product Barcode (' . get_woocommerce_currency_symbol() . ')', 'woocommerce'),
            'placeholder' => 'Enter Variation Product Barcode',
            'desc_tip' => 'true',
            'description' => __('Enter Variation Product Barcode value here.', 'woocommerce'),
            'value' => esc_attr(get_post_meta($variation->ID, 'var_product_barcode', true)),
        )
    );
}

function oliver_pos_save_variation_product_cost_field($variation_id , $id) {
    // Text Field
    if( isset($_POST['var_product_cost'][$id]) ) {
        update_post_meta( $variation_id, 'var_product_cost', sanitize_text_field($_POST['var_product_cost'][$id]) );
    }
    if( isset($_POST['var_product_barcode'][$id])) {
        update_post_meta( $variation_id, 'var_product_barcode', sanitize_text_field($_POST['var_product_barcode'][$id]) );
    }
}

// send email
function oliver_pos_send_order_email($order_id, $email_check) {
    if($email_check==true) {
        $pos_bridge_order = new Pos_Bridge_Order();
        add_filter('woocommerce_email_recipient_customer_completed_order', [$pos_bridge_order, 'oliver_pos_unhook_order_emails_customer_from_register'], 99, 2);
        add_filter('woocommerce_email_recipient_customer_processing_order', [$pos_bridge_order, 'oliver_pos_unhook_order_emails_customer_from_register'], 99, 2);
        add_filter('woocommerce_before_email_order', [$pos_bridge_order, 'oliver_pos_unhook_order_emails_customer_from_register'], 99, 2);
    }
    $status = 1;
    $message = 'Mail sent.';

    $id = is_int($order_id) ? $order_id : (int) $order_id;

    $order = new WC_Order($id);
    $order_status = $order->get_status();

    switch ($order_status) {
        case 'completed':
            $email = new WC_Email_Customer_Completed_Order();
            break;

        case 'processing':
            $email = new WC_Email_Customer_Processing_Order();
            break;

        case 'on-hold':
            $email = new WC_Email_Customer_On_Hold_Order();
            break;

        case 'cancelled':
            $email = new WC_Email_Cancelled_Order();
            break;

        case 'refunded':
            $email = new WC_Email_Customer_Refunded_Order();
            break;

        case 'failed':
            $email = new WC_Email_Failed_Order();
            break;

        default:
            $email = new WC_Email_Customer_Processing_Order();
            break;
    }

    $email->trigger($id);
    return ['status' => $status, 'message' => $message];
}

// manage log's file
function oliver_log($msg) {
    if(is_dir(plugin_dir_path( __FILE__  ))) {
        if (is_writable(plugin_dir_path( __FILE__  ))) {
            $date = date('Ymd');

            $directory = plugin_dir_path(__FILE__) . 'log';

            if (!file_exists($directory)) {
                if ( ! mkdir ( $directory , 0777 , true ) && ! is_dir ( $directory ) ) {
                    throw new \RuntimeException( sprintf ( 'Directory "%s" was not created' , $directory ) );
                }
            }
            $file = fopen($directory . '/log_'. $date .'.txt', 'a+');
            fwrite($file, date('H:i:s').' - ' . $msg . "\n");
            fclose($file);
        }
    }
}

// get log file content
function oliver_pos_get_log_file($request_data) {
    $parameters = $request_data->get_params();

    if (isset($parameters['file']) && !empty($parameters['file'])) {
        $directory = plugin_dir_path( __FILE__ ) . 'log';
        $filename = $parameters['file'] . '.txt';
        $file = $directory . '/' . $filename;

        if (file_exists($file)) {
            $r_file = fopen($file, 'r') or exit('Unable to open file!');

            return ['message' => fread($r_file, filesize($file)), 'link' => plugins_url('log/' . $filename, __FILE__), 'status' => 1];
        } else {
            return ['message' => 'Log file not exists.', 'status' => -1];
        }
    }

    return ['message' => 'invalid Request.', 'status' => -1];
}

if (!function_exists('oliver_pos_admin_notices')) {
    function oliver_pos_admin_notices() {
        if (!current_user_can('update_plugins')) {
            return;
        }

        $install_date = get_option('oliver_pos_install_date');
        $is_show = get_option('oliver_pos_show_rating_div');
        $display_date = gmdate('Y-m-d h:i:s');
        $datetime1 = new DateTime($install_date);
        $datetime2 = new DateTime($display_date);
        $diff_intrval = round(($datetime2->format('U') - $datetime1->format('U')) / (60 * 60 * 24));
        if ($is_show && $diff_intrval >= 3) {
            echo '<div class="oliver_pos_notice updated" style="box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);">
			<p>Awesome, you\'ve been using <strong>Oliver POS</strong> for more than 3 days. <br> May we ask you to give it a <strong>5-star rating</strong> on Wordpress? </br>
			This will help us spread its popularity and allow us to keep making this plugin better.
			<br><br>Your help is much appreciated, thank you very much.<br> - Mathias Nielsen (Oliver POS)
			<ul>
			<li><a href="https://wordpress.org/support/view/plugin-reviews/oliver-pos" class="thankyou" target="_blank" title="Ok, you deserved it" style="font-weight:bold;">'.__('Ok, you deserved it', 'widget-options').'</a></li>
				<li><a href="javascript:void(0);" class="oliverpos_bHideRating" title="I already did" style="font-weight:bold;">'.__('I already did', 'widget-options').'</a></li>
				<li><a href="javascript:void(0);" class="oliverpos_bHideRating" title="No, not good enough" style="font-weight:bold;">'.__('No, not good enough, i do not like to rate it!', 'widget-options').'</a></li>
			</ul>
		</div>
		<script>
		jQuery( document ).ready(function( $ ) {

		jQuery(\'.oliverpos_bHideRating\').click(function(){
			var data={\'action\':\'oliverpos_hideRating\'}
					jQuery.ajax({

			url: "' . admin_url('admin-ajax.php') . '",
			type: "post",
			data: data,
			dataType: "json",
			async: !0,
			success: function(e) {
				if (e=="success") {
					jQuery(\'.oliver_pos_notice\').slideUp(\'slow\');

				}
			}
				});
			})

		});
		</script>
		';
        }
    }
    add_action('admin_notices', 'oliver_pos_admin_notices');
}

if (!function_exists('oliver_pos_ajax_hideRating')) {
    function oliver_pos_ajax_hideRating() {
        update_option('oliver_pos_show_rating_div', false);
        echo json_encode(['success']);
        exit;
    }
    add_action('wp_ajax_oliverpos_hideRating', 'oliver_pos_ajax_hideRating');
}

// Add new column in wooCommerce order admin panel

/*
 * Create new column in wooCommerce admin order pane
 * @since 2.1.3.2
 * @param array $columns Array of all wooCommerce order columns
 * @return array Returns Array of all wooCommerce order columns with new custom column
 */
if (!function_exists('oliver_pos_custom_shop_order_column')) {
    add_filter('manage_edit-shop_order_columns', 'oliver_pos_custom_shop_order_column', 20);
    //HPOS
    add_filter('manage_woocommerce_page_wc-orders_columns', 'oliver_pos_custom_shop_order_column', 20);
    function oliver_pos_custom_shop_order_column($columns) {
        $reordered_columns = [];

        // Inserting columns to a specific location
        foreach ($columns as $key => $column) {
            $reordered_columns[$key] = $column;
            if ('order_status' == $key) {
                // Inserting after "Status" column
                $reordered_columns['oliver_pos_receipt'] = __('<strong>OliverPOS Receipt #</strong>', 'oliver-pos');
            }
        }
        return $reordered_columns;
    }
}

/*
 * Populate the value corresponding to new created column
 * @since 2.1.3.2
 * @param array $column Array of all wooCommerce order columns
 * @param int $post_id Order Id
 * @return string Returns either value of column or default column value
 */
if (!function_exists('oliver_pos_custom_shop_order_column_value')) {
    // Adding custom fields meta data for each new column (example)
    add_action('manage_shop_order_posts_custom_column', 'oliver_pos_custom_shop_order_column_value', 20, 2);
    //HPOS
    add_action('manage_woocommerce_page_wc-orders_custom_column', 'oliver_pos_custom_shop_order_column_value', 20, 2);
    function oliver_pos_custom_shop_order_column_value($column, $post_id) {
        switch ($column) {
            case 'oliver_pos_receipt':
                if(OP_HPOS_ENABLED){
					$post_id = $post_id->get_id();
				}
                // Get custom post meta data
                $value = esc_attr(get_post_meta($post_id, '_oliver_pos_receipt_id', true));
                if (!empty($value)) {
                    echo "<strong> {$value} </strong>";
                }
            break;
        }
    }
}

// Add new column in wooCommerce order admin panel

// Add new filter for custom column in wooCommerce order admin panel

/*
 * Create the filter input field
 * @since 2.1.3.2
 * @return void
 */
if (!function_exists('oliver_pos_admin_posts_filter_restrict_manage_posts')) {
    add_action('restrict_manage_posts', 'oliver_pos_admin_posts_filter_restrict_manage_posts');

    function oliver_pos_admin_posts_filter_restrict_manage_posts() {
        $type = 'post';
        if (isset($_GET['post_type'])) {
            $type = sanitize_text_field($_GET['post_type']);
        }

        //only add filter to post type you want
        if ('shop_order' == $type) {
            $value = isset($_GET['oliver_pos_receipt_filter']) ? sanitize_text_field($_GET['oliver_pos_receipt_filter']) : '';
            echo '<input type="search" name="oliver_pos_receipt_filter" value="'.$value.'" placeholder="OliverPOS Receipt No.">';
            //Since 2.3.8.6
            // Add new fitler to Woocommerce admin shop order
            $oliver_pos_filter_types = ['Oliver', 'Online'];
            $current_val = isset($_GET['oliver_filter_shop_order']) ? $_GET['oliver_filter_shop_order'] : '';

            echo '<select name="oliver_filter_shop_order"><option value="">All</option>';

            foreach ($oliver_pos_filter_types as $oliver_pos_filter_type) {
                printf('<option value="%s"%s>%s</option>', $oliver_pos_filter_type,
                    $oliver_pos_filter_type === $current_val ? '" selected="selected"' : '', $oliver_pos_filter_type);
            }
            echo '</select>';
        }
    }
}

/*
 * Filter the order list by given value from filter input field
 * @since 2.1.3.2
 * @param object $query
 *
 * @return void
 */
if (!function_exists('oliver_pos_posts_filter_query')) {
    add_filter('parse_query', 'oliver_pos_posts_filter_query');

    function oliver_pos_posts_filter_query($query) {
        global $pagenow;
        $type = 'post';
        if (isset($_GET['post_type'])) {
            $type = sanitize_text_field($_GET['post_type']);
        }
        if ('shop_order' == $type && is_admin() && 'edit.php' == $pagenow) {
            if (isset($_GET['oliver_pos_receipt_filter']) && '' != sanitize_text_field($_GET['oliver_pos_receipt_filter'])) {
                $query->query_vars['meta_key'] = '_oliver_pos_receipt_id';
                $query->query_vars['meta_value'] = sanitize_text_field($_GET['oliver_pos_receipt_filter']);
            }
            //Since 2.3.8.6
            // Add new fitler to Woocommerce admin shop order
            if (isset($_GET['oliver_filter_shop_order']) && 'Oliver' == sanitize_text_field($_GET['oliver_filter_shop_order'])) {
                $query->query_vars['meta_key'] = '_oliver_pos_receipt_id';
                $query->query_vars['compare'] = 'EXISTS';
            }
            if (isset($_GET['oliver_filter_shop_order']) && 'Online' == sanitize_text_field($_GET['oliver_filter_shop_order'])) {
                $query->query_vars['meta_key'] = '_created_via';
                $query->query_vars['meta_value'] = 'checkout';
                $query->query_vars['compare'] = 'EXISTS';
            }
        }
    }
}
// Add new filter for custom column in wooCommerce order admin panel

/*
 * Load oliver loader file
 * @since 2.2.5.0
 *
 * @return html file
 */
if (!function_exists('oliver_pos_loader')) {
    function oliver_pos_loader() {
        include 'includes/views/backend/loader.php';
    }
}

/**
 *
 * Create or update tables if needed.
 * Add Wherehouse since 2.4.0.1.
 *
 */
add_action( 'plugins_loaded', 'oliver_pos_warehouse_table' );
function oliver_pos_warehouse_table() {
    global $wpdb;
    $table_name      = $wpdb->prefix . 'pos_warehouse';
    $charset_collate = $wpdb->get_charset_collate();
    if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
        $sql = "CREATE TABLE $table_name (
			  id bigint(20) NOT NULL AUTO_INCREMENT,
			  oliver_warehouseid bigint(20) NOT NULL,
			  isdefault bigint(20) NOT NULL,	
			  syncerror bigint(20) NOT NULL,	
			  isdeleted bigint(20) NOT NULL,
			  name longtext NOT NULL,
			  type longtext NOT NULL,
			  relwarehouselocations longtext NOT NULL,
			  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  PRIMARY KEY  (id)

			) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $data = dbDelta( $sql );
    }
}
/*
*  @since 2.4.0.2
*  To return the response.
*
*/
function oliver_pos_api_response( $msg, $status ) {
	return( array('message' => $msg, 'status' => $status) );
}
//HPOS
add_action('before_woocommerce_init', function(){
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
});
