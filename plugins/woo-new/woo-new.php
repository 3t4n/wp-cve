<?php
/*
Plugin Name: WooCommerce New Customer Report - WP Fix It
Author: WP Fix It
Author URI: https://www.wpfixit.com
License: GPLv2
Version: 2.0
Description: Display new customer orders versus returning customers in your WordPress admin area.  These are the customers that have bought for the first time in your online shop.
*/
if(!defined('ABSPATH')){
    exit; //Exit if accessed directly
}
if(!class_exists('NI_WC_New_Customer_Reports')){
	class NI_WC_New_Customer_Reports{
		function __construct(){
			
			add_action( 'admin_menu', array($this, 'admin_menu'));
		}
		
		function admin_menu(){
			//add_submenu_page( 'woocommerce', 'New Customer', 'New Customer', 'manage_options', 'new_customer', array($this, 'new_customer_page')); 
			add_action( 'wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
		}//End Admin Menu
		
		function wp_dashboard_setup(){
			wp_add_dashboard_widget('ni_wc_new_customer_count',__('WooCommerce New Customers'), array($this, 'ni_new_customer_output'));
		}
		
		
		function ni_new_customer_output(){
			echo $this->get_ni_new_customer_output();			
		}
		
		
		function get_ni_new_customer_output(){
			
			//echo $ni_new_customer_output  = get_transient('ni_new_customer_output');
			
			$ni_new_customer_output = false;
			if($ni_new_customer_output == false || empty($ni_new_customer_output)){				
			  return	$ni_new_customer_output = $this->get_new_customer_output();
				//set_transient('ni_new_customer_output', $ni_new_customer_output, HOUR_IN_SECONDS/2 );
				
			}else{
				return $ni_new_customer_output;
			}
			
			
			
		}
		
		function get_new_customer_output(){
			$lists['today']['count'] 		=  $daily_count = $this->new_customer('today');
			$lists['current_week']['count']	=  $daily_count = $this->new_customer('current_week');
			$lists['current_month']['count']=  $daily_count = $this->new_customer('current_month');
			$lists['current_year']['count']	=  $daily_count = $this->new_customer('current_year');
			
			$lists['today']['label'] 		=  __('Today ');
			$lists['current_week']['label']	=  __('Current Week ');
			$lists['current_month']['label']=  __('Current Month ');
			$lists['current_year']['label']	=  __('Current Year ');
			
			//$this->print_list($lists);
						
			$output = "<table class=\"widefat fixed\" cellspacing=\"0\">";
			
			$output .= '<div style="height: auto; text-align: left; margin-top:5px; margin-bottom:5px;">Customers that have purchased on your site for the first time.</div>';
			
			$output .= "<thead>";
			$output .= "<tr>";
				
				
				$output .= "<th>";
					$output .= __('<b>New Customers</b>');
				$output .= "</th>";
					
				$output .= "<th style=\"text-align:right\">";
					$output .= __('<b>Total</b>');
				$output .= "</th>";
				
				
				$output .= "</tr>";
			$output .= "</thead>";
			$output .= "<tbody>";
			
			foreach ($lists as $key => $list){
				$output .= "<tr>";
				
				
				$output .= "<td style=\"color: #e99d3a;font-size: 14px;\">";
					$output .= $list['label'];
				$output .= "</td>";
					
				$output .= "<td style=\"text-align:right; color: #288fb4;font-weight: bold; font-size:16px;\">";
					$output .= $list['count'];
				$output .= "</td>";
				
				
				$output .= "</tr>";
			}
			$output .= "</tbody>";
			$output .= "</table>";
			
			$output .= '<div style="height: auto; text-align: center; margin-top:5px;"><a title="WP Fix It" href="https://www.wpfixit.com" TARGET="_blank">
                                                    <img alt="WP Fix It" src="https://www.wpfixit.com/wp-content/uploads/2022/03/2022-logo.webp">
                                            </a><br><b>24/7 WordPress Support for Only $39</b></div>';
			
			return $output;
		}
		
		function new_customer($type = 'daily'){
			global $wpdb;
			
			$sql = " SELECT ";
			$sql .= " billing_email.meta_value AS billing_email";			
			$sql .= " FROM $wpdb->posts AS shop_order";
			$sql .= " LEFT JOIN $wpdb->postmeta AS billing_email ON billing_email.post_id = shop_order.ID";
			$sql .= " WHERE 1*1";
			$sql .= " AND shop_order.post_type = 'shop_order'";
			$sql .= " AND billing_email.meta_key = '_billing_email'";
			
			switch($type){
				case "today":
					$start_date = date_i18n("Y-m-d");
					
					$sql .= " AND  date_format( shop_order.post_date, '%Y-%m-%d') = '{$start_date}'";
					
					$billing_emails = $this->get_old_customer_billing_email($type,$start_date);
					
					if($billing_emails)
						$sql .= " AND   billing_email.meta_value NOT IN ($billing_emails)";
					break;
				case "current_week":
					$week_days 			= array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
					$today 				= date_i18n("Y-m-d");
					$today_string 		= strtotime($today);
					$curren_week_day 	= date("w",$today_string);					
					$start_of_week 		= get_option('start_of_week');
					
					$start_of_week_day = $week_days[$start_of_week];
					
					if($curren_week_day == $start_of_week){
						$start_date =$today;
					}else{
						$start_date 	= date("Y-m-d",strtotime('last '.$start_of_week_day,$today_string));
					}
					$sql .= " AND date_format( shop_order.post_date, '%Y-%m-%d') BETWEEN  '{$start_date}' AND '{$today}'";
					$billing_emails = $this->get_old_customer_billing_email($type,$start_date);
					
					if($billing_emails)
						$sql .= " AND   billing_email.meta_value NOT IN ($billing_emails)";
					break;
				case "current_month":
					$today 				= date_i18n("Y-m-d");
					$today_string 		= strtotime($today);
					$start_date			= date_i18n("Y-m-01",$today_string);
					
					
					$sql .= " AND date_format( shop_order.post_date, '%Y-%m-%d') BETWEEN  '{$start_date}' AND '{$today}'";
					$billing_emails = $this->get_old_customer_billing_email($type,$start_date);
					
					if($billing_emails)
						$sql .= " AND   billing_email.meta_value NOT IN ($billing_emails)";
					break;
				case "current_year":
					$today 				= date_i18n("Y-m-d");
					$today_string 		= strtotime($today);
					$start_date			= date_i18n("Y-01-01",$today_string);
					
					
					$sql .= " AND date_format( shop_order.post_date, '%Y-%m-%d') BETWEEN  '{$start_date}' AND '{$today}'";
					
					 $billing_emails = $this->get_old_customer_billing_email($type,$start_date);
					
					if($billing_emails)
						$sql .= " AND   billing_email.meta_value NOT IN ($billing_emails)";
					break;
				default:
					break;
			}
			
			//echo $type;
			//echo "<br>";
			$sql .= " GROUP BY billing_email";
			
			
			$item = $wpdb->get_results($sql);
			return count($item);
			
		}
		
		function get_old_customer_billing_email($type = 'daily', $start_date = ''){
			global $wpdb;
			
			$sql = " SELECT ";			
			$sql .= " billing_email.meta_value AS billing_email";			
			$sql .= " FROM $wpdb->posts AS shop_order";
			$sql .= " LEFT JOIN $wpdb->postmeta AS billing_email ON billing_email.post_id = shop_order.ID";
			$sql .= " WHERE 1*1";
			$sql .= " AND shop_order.post_type = 'shop_order'";
			$sql .= " AND billing_email.meta_key = '_billing_email'";
			
			switch($type){
				case "today":
				case "current_week":
				case "current_month":
				case "current_year":
					$sql .= " AND  date_format( shop_order.post_date, '%Y-%m-%d') < '{$start_date}'";
					break;
				default:
					break;
			}
			
			$sql .= " GROUP BY billing_email";
			
			$item = $wpdb->get_results($sql);
			
			if($item){
				$emails  = $this->get_items_id_list($item,'billing_email');
			}else{
				$emails  = '';
			}
			
			return $emails;
			
		}
		
		function get_items_id_list($order_items = array(),$field_key = 'order_id', $return_default = '-1' , $return_formate = 'string'){
			$list 	= array();
			$string = $return_default;
			if(count($order_items) > 0){
				foreach ($order_items as $key => $order_item) {
					if(isset($order_item->$field_key))
						$list[] = $order_item->$field_key;
				}
				
				$list = array_unique($list);
				
				if($return_formate == "string"){
					$string = "'".implode("', '",$list)."'";
				}else{
					$string = $list;
				}
			}
			return $string;
		}
		
		function print_list($array = array()){
			print("<pre>");
			print_r($array);
			print("</pre>");
		}
		
	}//End Class
}//End Class Exists
new NI_WC_New_Customer_Reports();
defined( 'ABSPATH' ) or exit; // Exit if accessed directly
// Check if WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	function wc_new_customer_report_wc_needed_notice() {
		$message = sprintf(
		/* translators: Placeholders: %1$s and %2$s are <strong> tags. %3$s and %4$s are <a> tags */
			esc_html__( '%1$sWooCommerce New Customer Report %2$s requires WooCommerce to function. Please %3$sinstall WooCommerce%4$s.', 'woocommerce-new-customer-report' ),
			'<strong>',
			'</strong>',
			'<a href="' . admin_url( 'plugins.php' ) . '">',
			'&nbsp;&raquo;</a>'
		);
		echo sprintf( '<div class="error"><p>%s</p></div>', $message );
	}
	add_action( 'admin_notices', 'wc_new_customer_report_wc_needed_notice' );
	return;
}
// WC version check
if ( version_compare( get_option( 'woocommerce_db_version' ), '2.3.0', '<' ) ) {
	function wc_new_customer_report_outdated_version_notice() {
		$message = sprintf(
		/* translators: Placeholders: %1$s and %2$s are <strong> tags. %3$s and %4$s are <a> tags */
			esc_html__( '%1$sWooCommerce New Customer Report is inactive.%2$s This plugin requires WooCommerce 2.3 or newer. Please %3$supdate WooCommerce to version 2.3 or newer%4$s.', 'woocommerce-new-customer-report' ),
			'<strong>',
			'</strong>',
			'<a href="' . admin_url( 'plugins.php' ) . '">',
			'&nbsp;&raquo;</a>'
		);
		echo sprintf( '<div class="error"><p>%s</p></div>', $message );
	}
	add_action( 'admin_notices', 'wc_new_customer_report_outdated_version_notice' );
	return;
}
/**
 * Plugin Description
 *
 * WooCommerce New Customer Report adds a report to the WooCommerce > Reports > Customer section.
 * This report tracks whether a customer is new vs returning based on whether the billing address
 *    has been used or not for an order before the start of the selected date range.
 */
if ( ! class_exists( 'WC_New_Customer_Report' ) ) :
/**
 * Sets up the plugin and loads the reporting class
 *
 * @since 1.0.0
 */
class WC_New_Customer_Report {
	const VERSION = '1.0.0';
	/** @var WC_New_Customer_Report single instance of this plugin */
	protected static $instance;
	public function __construct() {
		// load translations
		add_action( 'init', array( $this, 'load_translation' ) );
		// any frontend actions
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			// any admin actions
			add_filter( 'woocommerce_admin_reports', array( $this, 'add_reports' ) );
			// add plugin links
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_plugin_links' ) );
			// run every time
			$this->install();
		}
	}
	/** Helper methods ***************************************/
	/**
	 * Main WC_New_Customer_Report Instance, ensures only one instance is/can be loaded
	 *
	 * @since 1.0.0
	 * @see wc_new_customer_report()
	 * @return WC_New_Customer_Report
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Adds plugin page links
	 *
	 * @since 1.0.0
	 * @param array $links all plugin links
	 * @return array $links all plugin links + our custom links (i.e., "Settings")
	 */
	public function add_plugin_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-reports&tab=customers&report=new_customers' ) . '">' . __( 'View Report', 'woocommerce-new-customer-report' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}
	/**
	 * Load Translations
	 *
	 * @since 1.0.0
	 */
	public function load_translation() {
		// localization
		load_plugin_textdomain( 'woocommerce-new-customer-report', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages' );
	}
	/** Plugin methods ***************************************/
	/**
	 * Adds a 'New vs Returning' report to the 'Customers' tab with associated reports
	 * to the WC admin reports area
	 *
	 * @since 1.0.0
	 * @param array $core_reports
	 * @return array the updated reports
	 */
	public function add_reports( $core_reports ) {
		$customer_reports = array(
			'new_customers' => array(
				'title'       => __( 'New vs. Returning', 'woocommerce-new-customer-report' ),
				'description' => '',
				'hide_title'  => true,
				'function'    => array( $this, 'load_report' ),
			),
		);
		// add new customer report
		if ( isset( $core_reports['customers']['reports'] ) ) {
			$core_reports['customers']['reports'] = array_merge( $core_reports['customers']['reports'], $customer_reports );
		}
		return $core_reports;
	}
	/**
	 * Callback to load and output the given report
	 *
	 * @since 1.0.0
	 * @param string $name report name, as defined in the add_reports() array above
	 */
	public function load_report( $name ) {
		$report = require_once( 'includes/class-wc-new-customer-report.php' );
		$report->output_report();
	}
	/** Lifecycle methods ***************************************/
	/**
	 * Run every time.  Used since the activation hook is not executed when updating a plugin
	 *
	 * @since 1.0.0
	 */
	private function install() {
		// get current version to check for upgrade
		$installed_version = get_option( 'wc_new_customer_report_version' );
		// force upgrade to 1.0.0
		if ( ! $installed_version ) {
			$this->upgrade( '1.0.0' );
		}
		// upgrade if installed version lower than plugin version
		if ( -1 === version_compare( $installed_version, self::VERSION ) ) {
			$this->upgrade( self::VERSION );
		}
	}
	/**
	 * Perform any version-related changes.
	 *
	 * @since 1.0.0
	 * @param int $installed_version the currently installed version of the plugin
	 */
	private function upgrade( $version ) {
		// update the installed version option
		update_option( 'wc_new_customer_report_version', $version );
	}
} // end \WC_New_Customer_Report class
/**
 * Returns the One True Instance of WC_New_Customer_Report
 *
 * @since 1.0.0
 * @return WC_New_Customer_Report
 */
function wc_new_customer_report() {
    return WC_New_Customer_Report::instance();
}
// fire it up!
wc_new_customer_report();
endif;


/* Activate the plugin and do something. */
register_activation_hook( __FILE__, 'woo_new_report_welcome_message' );
function woo_new_report_welcome_message() {
set_transient( 'woo_new_report_welcome_message_notice', true, 5 );
}
add_action( 'admin_notices', 'woo_new_report_welcome_message_notice' );
function woo_new_report_welcome_message_notice(){
/* Check transient, if available display notice */
if( get_transient( 'woo_new_report_welcome_message_notice' ) ){
?>
<div class="updated notice is-dismissible">
	<style>div#message {display: none}</style>
<p>&#127881; <strong>WP Fix It - WooCommerce New Customer Sales Report</strong> has been activated and you now can view a new customer report.
<br>
<br><a href="<?php echo get_admin_url(null, 'admin.php?page=wc-reports&tab=customers&report=new_customers') ?>"><b>CLICK HERE</b></a> to view your new customers report.</p>
</div>
<?php
/* Delete transient, only display this notice once. */
delete_transient( 'woo_new_report_welcome_message_notice' );
}
}
/* Activate the plugin and do something. */
function woo_new_report_plugin_action_links( $links ) {
    echo '<style>span#p-icon{width:23px!important}span#p-icon:before{width:32px!important;font-size:23px!important;color:#3B657D!important;background:0 0!important;box-shadow:none!important}</style>';
$links = array_merge( array(
'<a href="https://www.wpfixit.com/" target=”_blank”>' . __( '<b><span id="p-icon" class="dashicons dashicons-groups"></span> <span style="color:#f99568">GET HELP</span></b>', 'textdomain' ) . '</a>'
), $links );
return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woo_new_report_plugin_action_links' );