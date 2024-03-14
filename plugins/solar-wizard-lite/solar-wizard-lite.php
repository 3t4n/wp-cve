<?php
/**
 * Plugin Name:       Solar Wizard Lite
 * Plugin URI:        https://solarwizardplugin.com/
 * Description:       Calculate solar estimate and savings.
 * Version:           1.2.1
 * Requires PHP:      7.3
 * Author:            Covert Communication
 * Author URI:        https://www.covertcommunication.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       solar-wizard-lite
 * Domain Path:       /languages
 */
 
 /*
Solar Calculator is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Solar Calculator is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Solar Calculator. If not, see {URI to Plugin License}.
*/
require_once('admin/admin_settings.php');
require_once('includes.php');
require_once ('include/shortcodeConstructor.php');
define( 'SOLWZD_VERSION', '1.2.1' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
class SolarWizard {
	
	use shortcodeConstructor;
	public $sw_db_version = "1.2.1";
	
	public function __construct() {		
		add_action( 'plugins_loaded', array($this, 'solwzd_update_db_check') );
		add_shortcode('solar_wizard', array($this, 'solwzd_shortcode'));
		
		add_action( 'init', array($this, 'solwzd_register_solar_quote'));
		add_filter( 'post_row_actions', array($this, 'solwzd_remove_row_actions'), 10, 1);
		add_action( 'add_meta_boxes', array($this, 'solwzd_quote_add_custom_box'));
		
		add_filter('manage_quote_posts_columns' , array($this, 'solwzd_quote_columns' ));
		add_action( 'manage_quote_posts_custom_column' , array($this,  'solwzd_fill_quote_columns'), 10, 2);
		
		register_activation_hook( __FILE__, array($this, 'solwzd_prefix_activate'));
		
		add_action( 'wp_ajax_solwzd_submit_quote', array($this, 'solwzd_submit_quote'));
		add_action( 'wp_ajax_nopriv_solwzd_submit_quote', array($this, 'solwzd_submit_quote'));
		
		add_action( 'wp_ajax_solwzd_calculate_panel', array($this, 'solwzd_calculate_panel'));
		add_action( 'wp_ajax_nopriv_solwzd_calculate_panel', array($this, 'solwzd_calculate_panel'));
		
		add_action( 'wp_ajax_solwzd_count_incentive_with_cost', array($this, 'solwzd_count_incentive_with_cost'));
		add_action( 'wp_ajax_nopriv_solwzd_count_incentive_with_cost', array($this, 'solwzd_count_incentive_with_cost'));
		add_action( 'admin_init', array($this, 'solwzd_show_review_banner'));
		
		define('SOLWZD_UPGRADE_WEBSITE', 'https://solarwizardplugin.com/');
	}
	public function solwzd_update_db_check(){
		
		if ( get_site_option( 'sw_db_version' ) != $this->sw_db_version ) {
			update_option( "sw_db_version", $this->sw_db_version );
			add_option('sw_email_send_mid_wizard', array('yes'));
			add_option('sw_email_at_wizard_completion', array('yes'));
			add_option('sw_currency_symbol', array('USD'));
			add_option('sw_language', '');
			add_option('sw_system_size_fixed_pricing_matrix', Array ( 
				"price_per_watt_low" => get_option('sw_price_per_watt_panel_low_cash', 3),
				"price_per_watt_high" => get_option('sw_price_per_watt_panel_high_cash', 6),
				"financial_low" => get_option('sw_lowest_per_of_saving', 20),
				"financial_high" => get_option('sw_higest_per_of_saving', 40),
				"lease_low"	=> get_option('sw_lowest_per_of_saving_lease', 20),
				"lease_high" => get_option('sw_higest_per_of_saving_lease', 40)
			));
		}
	}
	public function solwzd_show_review_banner(){
		//$install_date = get_option( 'your_plugin_activation_time' );
        //$past_date = strtotime( '-7 days' );
     
        //if ( $past_date >= $install_date ) {
     
            add_action( 'admin_notices', array($this, 'solwzd_display_admin_notice'));
     
        //}
	}
	public function solwzd_display_admin_notice(){
		global $pagenow;
		//if( $pagenow == 'index.php' ){ 
		   
			$plugin_info = get_plugin_data( __FILE__ , true, true );       
			$reviewurl = esc_url( 'https://wordpress.org/support/plugin/solar-wizard-lite/reviews/' );
		 
			printf(__('<div class="notice notice-info is-dismissible"><p>Waiting for your important review. We hope you liked <b> %s </b>  ! Please give us a quick rating, it works as a boost for us to keep working on the plugin !<a href="%s" class="button button-primary solwzd-review-btn" target="_blank">Rate Now!</a></p></div>', $plugin_info['TextDomain']), $plugin_info['Name'], $reviewurl);
				
				
		//}
	}
	
	public function solwzd_quote_columns($columns){
    // Remove Author and Comments from Columns and Add custom column 1, custom column 2 and Post Id
    	return array(
    		'cb' => '<input type="checkbox" />',
    		'_name' => __('Name'),
			'_email' => __('Email'),
    		'_phone' => __('Phone'),
    		'_address' => __('Address'),
    		'_attachements' => __('Attachments'),
    		'_form_used' => __('Form Used'),
			'_form_used' => __('Form Used'),
			'_comm_method' => __('Comm. Method'),
			'_comm_details' => __('Comm. Details'),
			'_comm_date' => __('Date'),
			'_quote_date' => __('Date Created')
    	);
    }
	
	public function solwzd_fill_quote_columns( $column, $post_id ) {
    		// Fill in the columns with meta box info associated with each post
    	switch ( $column ) {
    	case '_name' :
    		echo get_the_title($post_id); 
    		break;
		case '_email' :
    		echo get_post_meta( $post_id , '_email' , true ); 
    		break;
    	case '_phone' :
    		echo get_post_meta( $post_id , '_phone' , true ); 
    			break;
    	case '_address' :
    		echo get_post_meta( $post_id , '_confirmaddress' , true ); 
    			break;
    	case '_attachements' :
			$attachments = get_posts( array(
				'post_type'         => 'attachment',
				'posts_per_page'    => -1,
				'post_parent'       => $post_id,
				'exclude'           => get_post_thumbnail_id() // Exclude post thumbnail to the attachment count
			));
    		if(count($attachments) > 0){
				echo '<span class="dashicons dashicons-media-document"></span>';
			}			
    			break;
    	case '_form_used' :
    		echo get_post_meta( $post_id , '_form_used' , true ); 
    			break;
		case '_comm_method' :
			echo get_post_meta( $post_id , '_communication_method' , true ); 
				break;
				
		case '_comm_details' :
			echo get_post_meta( $post_id , '_communication_details' , true ); 
				break;
				
		case '_comm_date' :
			echo get_post_meta( $post_id , '_date' , true ); 
				break;
		
		case '_quote_date' :
			echo get_the_date( 'F j, Y', $post_id); 
				break;
    	}
    }
	
	public function solwzd_calculate_panel(){
		
		$describe_you = sanitize_text_field($_POST['describe_you']);
		
		//Get default values 
		$sw_environmental_derate_factor = get_option( 'sw_environmental_derate_factor' );
		$sw_sunzone_values = get_option( 'sw_sunzone_hours' );
		if($sw_sunzone_values == ''){
			$sw_sunzone_values = get_option( 'sw_sunzone_values' )[0];
		}
		$cost_of_utility = get_option( 'sw_electricity_kwh_cost' );
		$panel_watt = get_option( 'sw_panel_watt' );
		$rate_of_utility_increase = get_option( 'sw_utility_increase_rate' );
		$sw_panel_manufacturer = get_option('sw_panel_manufacturer');
		$lease_option = get_option('sw_show_purchase_lease')[0];
		$panel_image = get_option('sw_panel_image');
		
		$estimated_kwh = $sw_environmental_derate_factor * 365 * $sw_sunzone_values;
		
		$battery_storage = sanitize_text_field($_POST['battery_storage']);
		if($battery_storage == "solar_with_storage"){
			$estimated_kwh = $estimated_kwh * 0.9;
		}
		
		$monthly_bill = sanitize_text_field($_POST['monthly_bill']);
		$average_kw_month = $monthly_bill / $cost_of_utility;
		$annual_kwh = $average_kw_month * 12;
		$system_size = ($annual_kwh / $estimated_kwh);
		$panel = round($system_size / ($panel_watt/1000));
		
		echo wp_json_encode(
			array(
				'panel_required' => $panel.' '.$sw_panel_manufacturer.' '.$panel_watt,
				'system_size' => number_format($system_size,2),
				'potential_savings' => number_format($this->solwzd_savingsOverYears(30,$cost_of_utility, $rate_of_utility_increase, $average_kw_month),2),
				'battery'=> $battery,
				'lease_option' => $lease_option,
				'panel_image' => $panel_image
		));
		exit();
	}
	
	public function solwzd_savingsOverYears($years, $cost_of_utility, $rate_of_utility_increase, $average_kw_month){
		$utility_bill = ($cost_of_utility * $average_kw_month * 12);
		for($i = 1; $i < $years; $i++){
			$addition = ($cost_of_utility * $rate_of_utility_increase) / 100;
			$cost_of_utility = $cost_of_utility + $addition;
			$utility_bill = $utility_bill + ($cost_of_utility * $average_kw_month * 12);
		}
		return $utility_bill;
	}
	
	
	public function solwzd_register_solar_quote(){
		$labels = array(
		'name'               => _x( 'Quote', 'Quotes' ),
		'singular_name'      => _x( 'Quotes', 'Quotes' ),
		'add_new'            => _x( 'Add New', 'Quotes' ),
		'add_new_item'       => __( 'Add New Quote' ),
		'edit_item'          => __( 'Edit Quote' ),
		'new_item'           => __( 'New Quote' ),
		'all_items'          => __( 'All Quotes' ),
		'view_item'          => __( 'View Quotes' ),
		'search_items'       => __( 'Search Quotes' ),
		'not_found'          => __( 'No quote found' ),
		'not_found_in_trash' => __( 'No quote found in the Trash' ), 
		'parent_item_colon'  => __( 'Parent Quotes' ),
		'menu_name'          => 'Quotes'
	  );
	  $args = array(
		'labels'        => $labels,
		'description'   => 'Holds Solar Quote specific data',
		'public'        => true,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'can_export'          => true,
		'menu_position' => 5,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'supports'      => array( 'title' ),
		'has_archive'   => false
	  );
	  register_post_type( 'quote', $args );
	}
	
	public function solwzd_remove_row_actions($actions){
		if( get_post_type() === 'quote' ){
			unset( $actions['view'] );
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}
	
	public function solwzd_prefix_activate() { 
		add_option('sw_company_name', 'Solar Wizard');
		add_option('sw_wizard_title', 'Welcome to the Solar Wizard');
		add_option('sw_primary_color', '#fc8900');
		add_option('sw_secondary_color', '#000000');
		
		add_option('sw_panel_watt', '360');
		add_option('sw_electricity_kwh_cost', '0.31');
		add_option('sw_utility_increase_rate', '4.5');
		
		add_option('sw_price_per_watt_panel_low_finance', '4');
		add_option('sw_price_per_watt_panel_high_finance', '8');
		add_option('sw_show_purchase_lease', array('no'));
		add_option('sw_price_per_watt_panel_low_lease', '4');
		add_option('sw_price_per_watt_panel_high_lease', '8');	
		add_option('sw_environmental_derate_factor', '0.9');
		add_option('sw_system_size_fixed_pricing_matrix', Array ( 
			"price_per_watt_low" => get_option('sw_price_per_watt_panel_low_cash', 3),
			"price_per_watt_high" => get_option('sw_price_per_watt_panel_high_cash', 6),
			"financial_low" => get_option('sw_lowest_per_of_saving', 20),
			"financial_high" => get_option('sw_higest_per_of_saving', 40),
			"lease_low"	=> get_option('sw_lowest_per_of_saving_lease', 20),
			"lease_high" => get_option('sw_higest_per_of_saving_lease', 40)
		));
		
		add_option('sw_loan_rate', '1.99%');
		add_option('sw_loan_term', '25 Years');
		add_option('sw_credit_score', '640+');
		
		add_option('sw_lease_rate', '1.99%');
		add_option('sw_lease_term', '25 Years');
		add_option('sw_lease_credit_score', '640+');
		
		add_option('sw_sunzone_values', array('4.6'));	
		
		add_option('sw_email_admin_email', get_option('admin_email'));
		add_option('sw_email_enable_admin_notification', array('yes'));
		add_option('sw_email_send_mid_wizard', array('yes'));
		add_option('sw_email_at_wizard_completion', array('yes'));
		add_option('sw_email_enable_office_hours_setup', array('no'));
		add_option('sw_email_enable_user_notification', array('yes'));
		add_option('sw_disable_battery_option', array('yes'));
		add_option('sw_currency_symbol', array('USD'));
		
		add_option('sw_incentives_repeater_name', array('Federal', 'State'));
		add_option('sw_incentives_repeater_value', array(26, 1000));
		add_option('sw_incentives_repeater_value_type', array('Percentage', 'Fixed'));
		add_option('sw_incentives_repeater_applied', array(array('Residential'), array('Residential')));
		
		update_option('sw_wizard_logo', plugin_dir_url( __FILE__ ).'/images/trusting_solar_image.png');
	}
	
	public function solwzd_shortcode( $atts = [], $content = null) {
    // do something to $content
    // always return
	
	$atts = shortcode_atts( array(
		'select_wizard_form_id' => rand(),
        'form_id_full' => rand(),
		'form_id_lite' => rand(),
		'address_id_full' => rand(),
		'address_id_lite' => rand(),
		'class_full' => 'wizard_full',
		'class_lite' =>	'wizard_lite',
    ), $atts, 'bartag' );
	
	$property_type_step = true;
	$atts['property_type_step'] = false;
	$property_type_step = false;
	$atts['property_type'] = 'Residential';
	$atts['battery_step'] = false;
	$battery_step = false;
	
	$content = $this->solwzd_openWrapper();
	$content .= $this->solwzd_createStyle();
	$content .= $this->solwzd_wizardSelection($atts);
	$content .= $this->solwzd_openForm($atts);
	$content .= $this->solwzd_setHiddenFields($atts);
	
	$content .= $this->solwzd_step_one();
	$content .= $this->solwzd_step_two();
	$content .= $this->solwzd_step_three();
	
	$content .= $this->solwzd_step_four($atts, 'Full');
	$content .= $this->solwzd_step_five();
	$content .= $this->solwzd_step_six();
	$content .= $this->solwzd_step_eight();
	$content .= $this->solwzd_step_ten();
	$content .= $this->solwzd_step_eleven();
	$content .= $this->solwzd_step_twelve();
	$content .= $this->solwzd_progressFull($atts);
	$content .= $this->solwzd_closeForm();
	
	$content .= $this->solwzd_openFormLite($atts);
	$content .= $this->solwzd_setHiddenFields($atts);
	$content .= $this->solwzd_step_four($atts, 'Lite');
	$content .= $this->solwzd_step_five();
	$content .= $this->solwzd_step_six();
	$content .= $this->solwzd_step_eight();
	$content .= $this->solwzd_step_ten();
	$content .= $this->solwzd_step_eleven();
	$content .= $this->solwzd_step_twelve();
	$content .= $this->solwzd_progressLite($atts);
	$content .= $this->solwzd_closeForm();
	
	$content .= $this->solwzd_closeWrapper();
	return $content;
}
		
	public function solwzd_quote_add_custom_box() {
		$screens = [ 'quote' ];
		foreach ( $screens as $screen ) {
			add_meta_box(
				'quote_box_id',                 // Unique ID
				'Solar Quote Details',      // Box title
				array( $this, 'solwzd_quote_custom_fields_html'),  // Content callback, must be of type callable
				$screen                            // Post type
			);
		}
	}
	
	public function solwzd_count_incentive_with_cost(){
		
		$describe_you = sanitize_text_field($_POST['describe_you']);
		
		$price_per_watt_panel_low = 0;
		$price_per_watt_panel_high = 0;
		$system_purchase_plan = sanitize_text_field($_POST['system_purchase_plan']);
		//Get default values 
		$sw_environmental_derate_factor = get_option( 'sw_environmental_derate_factor' );
		$sw_sunzone_values = get_option( 'sw_sunzone_hours' );
		if($sw_sunzone_values == ''){
			$sw_sunzone_values = get_option( 'sw_sunzone_values' )[0];
		}
		$cost_of_utility = get_option( 'sw_electricity_kwh_cost' );
		$panel_watt = get_option( 'sw_panel_watt' );
		$rate_of_utility_increase = get_option( 'sw_utility_increase_rate' );
		$sw_panel_manufacturer = get_option('sw_panel_manufacturer');
		$sw_system_size_fixed_pricing_matrix = get_option( 'sw_system_size_fixed_pricing_matrix' );
		$price_per_watt_panel_low = $sw_system_size_fixed_pricing_matrix['price_per_watt_low'];
		$price_per_watt_panel_high = $sw_system_size_fixed_pricing_matrix['price_per_watt_high'];
		$sw_lowest_per_of_saving_financing = $sw_system_size_fixed_pricing_matrix['financial_low'];
		$sw_higest_per_of_saving_financing = $sw_system_size_fixed_pricing_matrix['financial_high'];
		$sw_lowest_per_of_saving_lease = $sw_system_size_fixed_pricing_matrix['lease_low'];
		$sw_higest_per_of_saving_lease = $sw_system_size_fixed_pricing_matrix['lease_high'];
		$sw_loan_rate = get_option( 'sw_loan_rate' );
		$sw_loan_term = get_option( 'sw_loan_term' );
		$sw_loan_credit_score = get_option( 'sw_credit_score' );
		$sw_lease_rate = get_option( 'sw_lease_rate' );
		$sw_lease_term = get_option( 'sw_lease_term' );
		$sw_lease_credit_score = get_option( 'sw_lease_credit_score' );
		
		$incentive_fixed = 0;
		$incentive_percentage = 0;
		
		$applied = sanitize_text_field($_POST['applied']);
		$type = sanitize_text_field($_POST['type']);
		
		$inc_name = get_option( 'sw_incentives_repeater_name' );
		$inc_value = get_option( 'sw_incentives_repeater_value');
		$inc_value_type = get_option( 'sw_incentives_repeater_value_type');
		$inc_applied = get_option( 'sw_incentives_repeater_applied');
		
		$total_incentive = 0;
		for($i=0; $i<count($inc_name) && is_array($inc_name); $i++){
			if(in_array($applied, $inc_applied[$i])){
				if($inc_value_type[$i] == 'Fixed'){
					$incentive_fixed = $incentive_fixed + $inc_value[$i];
				}
				if($inc_value_type[$i] == 'Percentage'){
					$incentive_percentage = $incentive_percentage + $inc_value[$i];
				}
				//$total_incentive = $total_incentive + $inc_value[$i];
			}
		}
		
		$estimated_kwh = $sw_environmental_derate_factor * 365 * $sw_sunzone_values;
		$battery_price = 0;
		$battery_storage = sanitize_text_field($_POST['battery_storage']);
		if(sanitize_text_field($_POST['battery_storage']) == "solar_with_storage"){
			$estimated_kwh = $estimated_kwh * 0.9;
			$battery_price = sanitize_text_field($_POST['battery_price']);
		}
		
		$monthly_bill = sanitize_text_field($_POST['monthly_bill']);
		
		$average_kw_month = $monthly_bill / $cost_of_utility;
		$annual_kwh = $average_kw_month * 12;
		$system_size = ($annual_kwh / $estimated_kwh);
		$panel = round($system_size / ($panel_watt/1000));
				
		$system_cost_low = (($panel*$panel_watt)*$price_per_watt_panel_low) + $battery_price;
		$system_cost_high = (($panel*$panel_watt)*$price_per_watt_panel_high) + $battery_price;
		
		$total_incentive_low = round((($system_cost_low*$incentive_percentage)/100) + $incentive_fixed);
		$total_incentive_high = round((($system_cost_high*$incentive_percentage)/100) + + $incentive_fixed);
		
		$low_per_of_saving = 0;
		$high_per_of_saving = 0;
		
		
		if( $system_purchase_plan == 'Finance' ){
			$low_per_of_saving = ($monthly_bill - ( ($monthly_bill * $sw_lowest_per_of_saving_financing) / 100)); 
			$high_per_of_saving = ($monthly_bill - ( ($monthly_bill * $sw_higest_per_of_saving_financing) / 100));
		} else if( $system_purchase_plan == 'Lease' ){
			$low_per_of_saving = ($monthly_bill - ( ($monthly_bill * $sw_lowest_per_of_saving_lease) / 100)); 
			$high_per_of_saving = ($monthly_bill - ( ($monthly_bill * $sw_higest_per_of_saving_lease) / 100));
		}
		
		$net_cost_low = $system_cost_low - $total_incentive_low;
		$net_cost_high = $system_cost_high - $total_incentive_high;
		
		echo wp_json_encode(
			array(
				'system_cost_low'=> $system_cost_low, 
				'system_cost_high'=> $system_cost_high, 
				'total_incentive_low' => $total_incentive_low, 
				'total_incentive_high' => $total_incentive_high, 
				'low_per_of_saving' => $low_per_of_saving, 
				'high_per_of_saving' => $high_per_of_saving,
				'sw_loan_rate' => $sw_loan_rate,
				'sw_loan_term' => $sw_loan_term,
				'sw_loan_credit_score' => $sw_loan_credit_score,
				'sw_lease_rate' => $sw_lease_rate,
				'sw_lease_term' => $sw_lease_term,
				'sw_lease_credit_score' => $sw_lease_credit_score,
				'describe_you' => $describe_you,
				'net_cost_low' => $net_cost_low,
				'net_cost_high' => $net_cost_high
			)
		);
		exit();
	}
	
	public function solwzd_sendMailtoUser($to, $subject, $body, $name_title, $name_detail, $quote_id, $send_attachments = false){
		$email_str = '<table border="0" cellpadding="0" cellspacing="0" width="100%"> 
						<tr>
							<td style="padding: 10px 0 30px 0;">
								<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid '.get_option( 'sw_primary_color' ).'; border-collapse: collapse;">
									<tr>
										<td align="center" bgcolor="#ffffff" style="border-bottom:1px solid '.get_option( 'sw_primary_color' ).'; padding: 10px 0 10px 0; color: '.get_option( 'sw_secondary_color' ).'; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
											<img align="center" alt="" src="'.get_option( 'sw_wizard_logo' ).'" width="120" style="max-width:120px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">
										</td>
									</tr>
									<tr>
										<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
											<table border="0" cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 24px;">
														<b>'.$name_title.'</b>
													</td>
												</tr>
												<tr>
													<td style="padding: 20px 0 30px 0; color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
														'.$name_detail.'
													</td>
												</tr>
												<tr>
													<td style="padding: 20px 0 30px 0; color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
														'.$body.'
														<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">This email was sent from <a style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;" href="'.get_site_url().'" target="_blank">'.get_site_url().'</a></p>
													</td>
												</tr>
												
											</table>
										</td>
									</tr>
									<tr>
										<td bgcolor="'.get_option( 'sw_primary_color' ).'" style="padding: 30px 30px 30px 30px;">
											<table border="0" cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 10px; text-align: center;">
														<p style="margin: 10px 0 0 0;color:'.get_option( 'sw_secondary_color' ).';font-size: 10px;">©'.date('Y').' '.get_bloginfo( 'name' ).' | All Rights Reserved</p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
		if($send_attachments){
			$attachments_array = array();
			$attachments = get_posts( array(
				'post_type'         => 'attachment',
				'posts_per_page'    => -1,
				'post_parent'       => $quote_id,
				'exclude'           => get_post_thumbnail_id() // Exclude post thumbnail to the attachment count
			));
			foreach($attachments as $at){
				$attachments_array[]  = get_attached_file( $at->ID );
			}
		} else {
			$attachments_array = array();
		}
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		if(get_option('sw_email_from_name') != ''){
			$headers[] = 'From: '.get_option('sw_email_from_name').' <'.get_option('sw_email_from_email').'>';
		}
		wp_mail( $to, $subject, $email_str, $headers, $attachments_array );
	}
	
	public function solwzd_send_email($quote_id, $email_type){
		$name_title = '';
		$name_detail = '';
		$body = '';
		$body .= 
		
		'<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;"><strong>Details:</strong></p>
		<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Name: <strong>'.get_the_title($quote_id).'</strong></p>';
		$_form_used = get_post_meta( $quote_id, '_form_used', true );
		if($_form_used != ''){
			$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Form used?: <strong>'.$_form_used.'</strong></p>';
		}	
		$_motivate_option = get_post_meta( $quote_id, '_motivate_option', true );
		if($_motivate_option != ''){
			$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">What else motivates you to go solar?: <strong>'.$_motivate_option.'</strong></p>';
		}
		$_more_about = get_post_meta( $quote_id, '_more_about', true );
		if($_more_about != ''){
			$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">What best describes you?: <strong>'.$_more_about.'</strong></p>';
		}
		$_getting_best = get_post_meta( $quote_id, '_getting_best', true );
		if($_getting_best != ''){
			$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">When picking a solar partner is getting the best what is most important to me?: <strong>'.$_getting_best.'</strong></p>';
		}
		$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Address: <strong>'.get_post_meta( $quote_id, '_confirmaddress', true ).'</strong></p>';
		$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Average monthly utility bill: <strong>'.get_post_meta( $quote_id, '_sw_currency_symbol', true ).get_post_meta( $quote_id, '_monthly_bill', true ).'</strong></p>';
		$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Email: <strong>'.get_post_meta( $quote_id, '_email', true ).'</strong></p>
			<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Phone: <strong>'.get_post_meta( $quote_id, '_phone', true ).'</strong></p>
			<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Solar Installation: <strong>'.get_post_meta( $quote_id, '_describe_you', true ).'</strong></p>';
		$_panel_required = get_post_meta( $quote_id, '_panel_required', true );
		if($_panel_required!=''){
			$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Number of Panels: <strong>'.$_panel_required.' WATT PANELS</strong></p>';
		}
		$_system_size = get_post_meta( $quote_id, '_system_size', true );
		if($_system_size!=''){
			$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">System size: <strong>'.$_system_size.' kW</strong></p>';
		}
		$_potential_savings = get_post_meta( $quote_id, '_potential_savings', true );
		if($_potential_savings!=''){
			$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Potential Savings: <strong>'.$_potential_savings.' Over 30 Years</strong></p>';
		}
		if($email_type == 'full'){
			$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">How do you plan on purchasing your system?: <strong>'.get_post_meta( $quote_id, '_system_purchase_plan', true ).'</strong></p>';
			$_military = get_post_meta( $quote_id, '_military', true );
			$_nurse = get_post_meta( $quote_id, '_nurse', true );
			if($_military != ''){
				$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">I’m in the military or a veteran: <strong>'.$_military.'</strong></p>';
			}
			if($_nurse != ''){
				$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">I’m a nurse or state worker: <strong>'.$_nurse.'</strong></p>';
			}
			$_learn_battery_storage = get_post_meta( $quote_id, '_learn_battery_storage', true );
			if($_learn_battery_storage != ''){
				$body .= '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">I’d like to learn more about battery storage: <strong>'.$_learn_battery_storage.'</strong></p>';
			}
			
			$body .= '
			<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Communication Method: <strong>'.get_post_meta( $quote_id, '_communication_method', true ).'</strong></p>
			<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Communication Details: <strong>'.get_post_meta( $quote_id, '_communication_details', true ).'</strong></p>
			<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Date: <strong>'.get_post_meta( $quote_id, '_date', true ).'</strong></p>';
		}
		if(is_array(get_option('sw_email_enable_admin_notification')) && get_option('sw_email_enable_admin_notification')[0] == 'yes'){
			
			if($email_type == 'after_system_size'){
				$name_detail = '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif;">'.get_the_title($quote_id).' is estimating a quote for solar system, below are the details. Please wait for full details.</p>';
				$subject = 'Solar Wizard Quotation after System Size Calculation';
			}
			if($email_type == 'full'){
				$name_detail = '<p style="color: '.get_option( 'sw_secondary_color' ).'; font-family: Arial, sans-serif;">'.get_the_title($quote_id).' has placed a quote for solar system, below are the full details.</p>';
				$subject = 'New Solar Wizard Quotation Placed';
			}
			$name_title = 'Hello Administrator,';
			
			$to = get_option('sw_email_admin_email');
			$this->solwzd_sendMailtoUser($to, $subject, $body, $name_title, $name_detail, $quote_id);
		}
		if(is_array(get_option('sw_email_enable_user_notification')) && get_option('sw_email_enable_user_notification')[0] == 'yes' && sanitize_text_field($_POST['email_type']) == 'full'){
			
			$name_title = 'Hello '.get_the_title($quote_id).',';
			$name_detail = '<p>Your initial estimate has been placed successfully.</p><p style="font-size:9px;">The prices quoted here are only an estimate.  A representative will contact you soon to provide a formal proposal.</p>';
			
			$to = get_post_meta( $quote_id, '_email', true );
			$subject = 'Solar Wizard Quotation Successfully Placed';
			$this->solwzd_sendMailtoUser($to, $subject, $body, $name_title, $name_detail, $quote_id);
		}
	}
	
	public function solwzd_submit_quote(){
		$quote_id = sanitize_text_field($_POST['quote_id']);
		$quote_title = sanitize_text_field($_POST['username']);
		if($quote_id == '' || $quote_id == 'undefined'){
			$quote = array(
			   'post_type'     => 'quote',
			   'post_title'    => $quote_title,
			   'post_content'  => '',
			   'post_status'   => 'publish',
			   'post_author'   => 1
			 );
			$quote_id = wp_insert_post( $quote );
			update_post_meta( $quote_id, '_quote_id', uniqid() );
		} 
		$sw_currency_symbol = sanitize_text_field($_POST['sw_currency_symbol']);
		if(isset($_POST['sw_currency_symbol']) && $sw_currency_symbol != '' && $sw_currency_symbol != 'undefined'){
			update_post_meta( $quote_id, '_sw_currency_symbol', $sw_currency_symbol );
		}
		$firstname = sanitize_text_field($_POST['firstname']);
		if(isset($_POST['firstname']) && $firstname != '' && $firstname != 'undefined'){
			update_post_meta( $quote_id, '_firstname', $firstname );
		}
		$lastname = sanitize_text_field($_POST['lastname']);
		if(isset($_POST['lastname']) && $lastname != '' && $lastname != 'undefined'){
			update_post_meta( $quote_id, '_lastname', $lastname );
		}
		$motivate_option = sanitize_text_field($_POST['motivate_option']);
		if(isset($_POST['motivate_option']) && $motivate_option != '' && $motivate_option != 'undefined'){
			update_post_meta( $quote_id, '_motivate_option', $motivate_option );
		}
		$more_about = sanitize_text_field($_POST['more_about']);
		if(isset($_POST['more_about']) && $more_about != '' && $more_about != 'undefined'){
			update_post_meta( $quote_id, '_more_about', $more_about );
		}
		$getting_best = sanitize_text_field($_POST['getting_best']);
		if(isset($_POST['getting_best']) && $getting_best != '' && $getting_best != 'undefined'){
			update_post_meta( $quote_id, '_getting_best', $getting_best );
		}
		$confirmaddress = sanitize_text_field($_POST['address']);
		if(isset($_POST['address']) && $confirmaddress != '' && $confirmaddress != 'undefined'){
			update_post_meta( $quote_id, '_confirmaddress', $confirmaddress );
		}
		$monthly_bill = sanitize_text_field($_POST['monthly_bill']);
		if($monthly_bill != '' && $monthly_bill != 'undefined'){
			update_post_meta( $quote_id, '_monthly_bill', $monthly_bill );
		}
		$potential_savings = sanitize_text_field($_POST['potential_savings']);
		if($potential_savings != '' && $potential_savings != 'undefined'){
			update_post_meta( $quote_id, '_potential_savings', $potential_savings );
		}
		$system_size = sanitize_text_field($_POST['system_size']);
		if($system_size != '' && $system_size != 'undefined'){
			update_post_meta( $quote_id, '_system_size', $system_size );
		}
		$panel_required = sanitize_text_field($_POST['panel_required']);
		if($panel_required != '' && $panel_required != 'undefined'){
			update_post_meta( $quote_id, '_panel_required', $panel_required );
		}
		$opt_in = sanitize_text_field($_POST['opt_in']);
		if($opt_in != '' && $opt_in != 'undefined'){
			update_post_meta( $quote_id, '_opt_in', 'Yes' );
		} else {
			update_post_meta( $quote_id, '_opt_in', 'No' );
		}
		$email = sanitize_email($_POST['email']);
		if($email != '' && $email != 'undefined'){
			update_post_meta( $quote_id, '_email', $email );
		}
		$phone = sanitize_text_field($_POST['phone']);
		if($phone != '' && $phone != 'undefined'){
			update_post_meta( $quote_id, '_phone', $phone );
		}
		$describe_you = sanitize_text_field($_POST['describe_you']);
		if(isset($_POST['describe_you']) && $describe_you != '' && $describe_you != 'undefined'){
			update_post_meta( $quote_id, '_describe_you', $describe_you );
		}
		$system_purchase_plan = sanitize_text_field($_POST['system_purchase_plan']);
		if($system_purchase_plan != '' && $system_purchase_plan != 'undefined'){
			update_post_meta( $quote_id, '_system_purchase_plan', $system_purchase_plan );
		}
		$military = sanitize_text_field($_POST['military']);
		if(isset($military) && $military != '' && $military != 'undefined'){
			update_post_meta( $quote_id, '_military', $military );
		}
		$nurse = sanitize_text_field($_POST['nurse']);
		if(isset($nurse) && $nurse != '' && $nurse != 'undefined'){
			update_post_meta( $quote_id, '_nurse', $nurse );
		}
		$learn_battery_storage = sanitize_text_field($_POST['learn_battery_storage']);
		if($learn_battery_storage != '' && $learn_battery_storage != 'undefined'){
			update_post_meta( $quote_id, '_learn_battery_storage', $learn_battery_storage );
		}
		$communication_method = sanitize_text_field($_POST['communication_method']);
		if($communication_method != '' && $communication_method != 'undefined'){
			update_post_meta( $quote_id, '_communication_method', $communication_method );
		}
		$communication_details = sanitize_text_field($_POST['communication_details']);
		if($communication_details != '' && $communication_details != 'undefined'){
			update_post_meta( $quote_id, '_communication_details', $communication_details );
		}
		$date = sanitize_text_field($_POST['date']);
		if($date != '' && $date != 'undefined'){
			update_post_meta( $quote_id, '_date', $date);
		}
		$battery_storage = sanitize_text_field($_POST['battery_storage']);
		if($battery_storage != '' && $battery_storage != 'undefined'){
			update_post_meta( $quote_id, '_battery_storage', $battery_storage );
		}
		$form_used = sanitize_text_field($_POST['form_used']);
		if($form_used != '' && $form_used != 'undefined'){
			update_post_meta( $quote_id, '_form_used', $form_used );
		}
		
		$total_failes_msg = '';
		$total_suc_msg ='';
		if(isset($_FILES['files']) && count( $_FILES['files']['name'] ) > 0):
		$parent_post_id = $quote_id;
		$valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "pdf"); // Supported file types
		$max_file_size = 3072 * 1000; // in kb
		$max_image_upload = 24; // Define how many images can be uploaded to the current post
		$wp_upload_dir = wp_upload_dir();
		$path = $wp_upload_dir['path'] . '/';
		$count = 0;
		
		$attachments = get_posts( array(
			'post_type'         => 'attachment',
			'posts_per_page'    => -1,
			'post_parent'       => $parent_post_id,
			'exclude'           => get_post_thumbnail_id() // Exclude post thumbnail to the attachment count
		));
		
		if( $_SERVER['REQUEST_METHOD'] == "POST" ){
		   
			// Check if user is trying to upload more than the allowed number of images for the current post
			if( ( count( $attachments ) + count( $_FILES['files']['name'] ) ) > $max_image_upload ) {
				$upload_message[] = "Sorry you can only upload " . $max_image_upload . " images for each quote";
			} else {
			   
				foreach ( $_FILES['files']['name'] as $f => $name ) {
					$extension = pathinfo( $name, PATHINFO_EXTENSION );
					// Generate a randon code for each file name
					//$new_filename = cvf_td_generate_random_code( 20 )  . '.' . $extension;
					$new_filename = sanitize_file_name($name);
					if ( $_FILES['files']['error'][$f] == 4 ) {
						continue;
					}
				   
					if ( $_FILES['files']['error'][$f] == 0 ) {
						// Check if image size is larger than the allowed file size
						if ( $_FILES['files']['size'][$f] > $max_file_size ) {
							$upload_message[] = "$name is too large!.";
							continue;
					   
						// Check if the file being uploaded is in the allowed file types
						} elseif( ! in_array( strtolower( $extension ), $valid_formats ) ){
							$upload_message[] = "$name is not a valid format";
							continue;
					   
						} else{
							// If no errors, upload the file...
							if( move_uploaded_file( $_FILES["files"]["tmp_name"][$f], $path.$new_filename ) ) {
							   
								$count++;
								$filename = $path.$new_filename;
								$filetype = wp_check_filetype( basename( $filename ), null );
								$wp_upload_dir = wp_upload_dir();
								$attachment = array(
									'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
									'post_mime_type' => $filetype['type'],
									'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
									'post_content'   => '',
									'post_status'    => 'inherit'
								);
								// Insert attachment to the database
								$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
								require_once( ABSPATH . 'wp-admin/includes/image.php' );
							   
								// Generate meta data
								$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
								wp_update_attachment_metadata( $attach_id, $attach_data );
							   
							}
						}
					}
				}
			}
		}
		// Loop through each error then output it to the screen
		if ( isset( $upload_message ) ) :
			foreach ( $upload_message as $msg ){       
				//printf( __('<p class="bg-danger">%s</p>', 'wp-trade'), $msg );
				$total_failes_msg = $total_failes_msg.$msg.'<br />';
			}
		endif;
	   
		// If no error, show success message
		if( $count != 0 ){
			//printf( __('<p class = "bg-success">%d files added successfully!</p>', 'wp-trade'), $count );  
			$total_suc_msg = $count.' files added successfully!';
		}
		
		endif;
		
		if(isset($_POST['email_type']) && sanitize_text_field($_POST['email_type']) != ''){
			if(is_array(get_option('sw_email_at_wizard_completion')) && get_option('sw_email_at_wizard_completion')[0] == 'yes' && sanitize_text_field($_POST['email_type']) == 'full'){
				$this->solwzd_send_email($quote_id, sanitize_text_field($_POST['email_type']));
			} else if(is_array(get_option('sw_email_send_mid_wizard')) && get_option('sw_email_send_mid_wizard')[0] == 'yes' && sanitize_text_field($_POST['email_type']) == 'after_system_size'){
				$this->solwzd_send_email($quote_id, sanitize_text_field($_POST['email_type']));
			}
		}
		echo wp_json_encode(array('result' => 'true', 'quote_id' => $quote_id, 'file_fail_repsonse' => $total_failes_msg, 'file_suc_repsonse' => $total_suc_msg));
		die();
	}
	public function solwzd_quote_custom_fields_html( $post ) {
		$_sw_currency_symbol = get_post_meta( $post->ID, '_sw_currency_symbol', true );
		$_motivate_option = get_post_meta( $post->ID, '_motivate_option', true );
		$_firstname = get_post_meta( $post->ID, '_firstname', true );
		$_lastname = get_post_meta( $post->ID, '_lastname', true );
		$_more_about = get_post_meta( $post->ID, '_more_about', true );
		$_getting_best = get_post_meta( $post->ID, '_getting_best', true );
		$_confirmaddress = get_post_meta( $post->ID, '_confirmaddress', true );
		$_monthly_bill = get_post_meta( $post->ID, '_monthly_bill', true );
		$_email = get_post_meta( $post->ID, '_email', true );
		$_phone = get_post_meta( $post->ID, '_phone', true );
		$_describe_you = get_post_meta( $post->ID, '_describe_you', true );
		$_system_purchase_plan = get_post_meta( $post->ID, '_system_purchase_plan', true );
		$_military = get_post_meta( $post->ID, '_military', true );
		$_nurse = get_post_meta( $post->ID, '_nurse', true );
		$_learn_battery_storage = get_post_meta( $post->ID, '_learn_battery_storage', true );
		$_communication_method = get_post_meta( $post->ID, '_communication_method', true );
		$_communication_details = get_post_meta( $post->ID, '_communication_details', true );
		$_date = get_post_meta( $post->ID, '_date', true );
		$_form_used = get_post_meta( $post->ID, '_form_used', true );
		$_opt_in = get_post_meta( $post->ID, '_opt_in', true );
		$_system_size = get_post_meta( $post->ID, '_system_size', true );
		$_potential_savings = get_post_meta( $post->ID, '_potential_savings', true );
		$_panel_required = get_post_meta( $post->ID, '_panel_required', true );
		
		?>
		<p>Form Used : <strong><?php echo esc_html($_form_used); ?></strong></p>
		<?php if($_firstname != '') {?><p>First Name : <strong><?php echo esc_html($_firstname); ?></strong></p> <?php } ?>
		<?php if($_lastname != '') {?><p>Last Name : <strong><?php echo esc_html($_lastname); ?></strong></p> <?php } ?>
		<?php if($_motivate_option != '') {?><p>What else motivates you to go solar? : <strong><?php echo esc_html($_motivate_option); ?></strong></p> <?php } ?>
		<?php if($_more_about != '') {?><p>What best describes you? : <strong><?php echo esc_html($_more_about); ?></strong></p><?php } ?>
		<?php if($_getting_best != '') {?><p>When picking a solar partner is getting the best what is most important to me?: <strong><?php echo esc_html($_getting_best); ?></strong></p><?php } ?>
		<p>Address : <strong><?php echo esc_html($_confirmaddress); ?></strong></p>
		<p>Average monthly utility Bill : <strong><?php echo esc_html($_sw_currency_symbol).esc_html($_monthly_bill); ?></strong></p>
		<p>System size : <strong><?php echo esc_html($_system_size); ?></strong></p>
		<p>Potential savings : <strong><?php echo esc_html($_sw_currency_symbol).esc_html($_potential_savings); ?></strong></p>
		<p>Number of Panels : <strong><?php echo esc_html($_panel_required); ?></strong></p>
		<p>Email : <strong><?php echo esc_html($_email); ?></strong></p>
		<p>Phone : <strong><?php echo esc_html($_phone); ?></strong></p>
		<p>opt-in : <strong><?php echo esc_html($_opt_in); ?></strong></p>
		<p>Solar Installation : <strong><?php echo esc_html($_describe_you); ?></strong></p>
		<p>How do you plan on purchasing your system? : <strong><?php echo esc_html($_system_purchase_plan); ?></strong></p>
		<?php if($_military != '') {?><p>I’m in military or a veteran : <strong><?php echo esc_html($_military); ?></strong></p><?php } ?>
		<?php if($_nurse != '') {?><p>I’m a nurse or state worker : <strong><?php echo esc_html($_nurse); ?></strong></p><?php } ?>
		<p>I’d like to learn more about battery storage : <strong><?php echo esc_html($_learn_battery_storage); ?></strong></p>
		<p>Communication Method : <strong><?php echo esc_html($_communication_method); ?></strong></p>
		<p>Communication Details: <strong><?php echo esc_html($_communication_details); ?></strong></p>
		<p>Date : <strong><?php echo esc_html($_date); ?></strong></p>
		<style>
		ul.quote-documents {
			display: flex;
			align-items: stretch;
		}
		ul.quote-documents li {
			margin: 5px 15px 5px 0px;
			align-items: center;
			display: flex;
			border: 1px solid #dfdfdf;
			padding:5px;
		}
		</style>
		<?php
		$attachments = get_posts( array(
			'post_type'         => 'attachment',
			'posts_per_page'    => -1,
			'post_parent'       => $post->ID,
			'exclude'           => get_post_thumbnail_id() // Exclude post thumbnail to the attachment count
		));
		if ( $attachments ) {
			echo ('<p>Uploaded File(s):</p>');
			echo ('<ul class="quote-documents">');
			foreach ( $attachments as $attachment ) {
				$class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
				$thumbimg = wp_get_attachment_link( $attachment->ID, array(100, 100), false, true );
				echo ('<li class="' . $class . ' data-design-thumbnail">' . $thumbimg . '</li>');
			}
			echo ('</ul>');
		}
	}
}
$SolarWizard = new SolarWizard();
?>