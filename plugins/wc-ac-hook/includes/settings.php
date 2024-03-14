<?php
namespace mtreherne\WC_AC_Hook;

use WC_Integration;

/**
 * Class for WC AC Hook settings fields in administration panel. This class will add the necessary
 * form fields to the 'Integration' tab of the WooCommerce Settings menu.
 *
 */
if (!defined('ABSPATH')) exit();

if (!class_exists(__NAMESPACE__ . '\WC_AC_Hook_Integration')) :

class WC_AC_Hook_Integration extends WC_Integration {

	public function __construct() {
		$this->id = 'wc-ac-hook';
		$this->method_title = __( 'WC-AC Hook', 'wc-ac-hook' );
		$this->method_description = __( 'You must enter your ActiveCampaign URL and your ActiveCampaign API key to allow the WooCommerce web hook to add/update contacts when an order is placed. Enter the ActiveCampaign List ID to which you want contacts added. You may also have tags dependent on the product ordered. You will find an ActiveCampaign Tag field in the Advanced Product Data section for each WooCommerce product in your shop. For more information read the FAQs by viewing the plugin details.', 'wc-ac-hook' );
		$this->init_form_fields();
		add_action( 'woocommerce_update_options_integration_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	public function init_form_fields() {
		$ac_settings = get_option('settings_activecampaign', null);
		$this->form_fields = array(
			'ac_url' => array(
				'title'				=> __( 'ActiveCampaign URL', 'wc-ac-hook' ),
				'description'	=> __( 'In the format "https://accountname.api-us1.com"', 'wc-ac-hook' ),
				'type'				=> 'text',
				'default'			=> isset($ac_settings['api_url']) ? $ac_settings['api_url'] : null
			),
			'ac_api_key' => array(
				'title'				=> __( 'ActiveCampaign API Key', 'wc-ac-hook' ),
				'type'				=> 'text',
				'default'			=> isset($ac_settings['api_key']) ? $ac_settings['api_key'] : null
			),
			'ac_list_id' => array(
				'title'				=> __( 'ActiveCampaign List ID', 'wc-ac-hook' ),
				'type'				=> 'text',
				'css'					=> 'width:4em',
				'description'	=> __( 'Enter the of ActiveCampaign list ID (number) to which you would like shop contacts added.', 'wc-ac-hook' ),
				'desc_tip'		=> true
			),
			'ac_default_tag' => array(
				'title'				=> __( 'Default Tag(s)', 'wc-ac-hook' ),
				'type'				=> 'text',
				'description'	=> __( 'The default tags will always be added for any order (comma seperated for multiple tags).', 'wc-ac-hook' ),
				'desc_tip'		=> true
			),
			'wc_ac_addonprocessing' => array(
				'title' 			=> __( 'Add/Update Contact', 'wc-ac-hook' ),
				'type' 				=> 'checkbox',
				'label' 			=> __( 'When order status is processing (rather than completed)', 'wc-ac-hook' ),
				'description' => __( 'Default is to wait until order is completed. Option ignored when Track Order Status selected.', 'wc-ac-hook' ),
				'desc_tip'		=> true
			),
			'wc_ac_ordertracking' => array(
				'title' 			=> __( 'Track Order Status', 'wc-ac-hook' ),
				'type' 				=> 'checkbox',
				'label' 			=> __( 'Add WooCommerce order status to tags', 'wc-ac-hook' ),
				'description' => __( 'Suffix (pending), (failed), (processing), (on-hold), (cancelled) or (completed) appended to last tags', 'wc-ac-hook' ),
				'desc_tip'          => true
			),
			'wc_ac_notification' => array(
				'title' 			=> __( 'Debug Log', 'wc-ac-hook' ),
				'type' 				=> 'checkbox',
				'label' 			=> __( 'Enable logging', 'wc-ac-hook' ),
				'default' 		=> 'yes',
				'description' => __( 'Report errors to a WooCommerce System Status log file', 'wc-ac-hook' ),
			),
			'wc_ac_marketing' => array(
				'title' 			=> __( 'Signup to Marketing', 'wc-ac-hook' ),
				'type' 				=> 'select',
				'default'			=> 'no',
				'options' 		=> array(
					'no'			=> __( 'No', 'wc-ac-hook' ),
					'opt_in'	=> __( 'Opt In', 'wc-ac-hook' ),
					'opt_out'	=> __( 'Opt Out', 'wc-ac-hook' )),
				'description' => __( 'Adds checkbox to checkout to opt in or out of marketing', 'wc-ac-hook' )
			),
			'wc_ac_marketing_label' => array(
				'title'				=> __( 'Checkbox Label', 'wc-ac-hook' ),
				'type'				=> 'text',
				'description'	=> __( 'Enter the text you wish to see by the checkbox at checkout', 'wc-ac-hook' ),
				'default'			=> __( 'Send me a newsletter (you can unsubscribe at any time)', 'wc-ac-hook' ),
				'desc_tip'		=> true
			),
			'wc_ac_marketing_form_id' => array(
				'title'				=> __( 'Marketing Form ID', 'wc-ac-hook' ),
				'type'				=> 'text',
				'css'					=> 'width:4em',
				'description'	=> __( 'Enter the ActiveCampaign form for associated actions e.g. subscribe to list, add tags and double opt in.', 'wc-ac-hook' ),
				'desc_tip'		=> true
			)
		);
	}

}

endif;
?>