<?php
/**
 * The WC-way form for the shipping method options.
 *
 * @package Fish and Ships
 * @version 1.5
 */

defined( 'ABSPATH' ) || exit;

global $Fish_n_Ships;

$free = '';
if ( !$Fish_n_Ships->im_pro() ) {
	$free = '<br><br>' 
			. wp_kses( __('Only the <strong>Pro version</strong> allows distinct grouping criteria on every selection condition.', 'fish-and-ships'),
						 array('strong'=>array())
					);
}

$inner_help  = '<span class="woocommerce-fns-space-buttons">' . str_replace(array('(',')'), array('<a href="https://www.wp-centrics.com/help/fish-and-ships/" class="woocommerce-fns-help-popup button" data-fns-tip="main" target="_blank">','</a>'), __( 'Here is the (Main Help)', 'fish-and-ships') );
$inner_help .= ' or you can ' . '<a href="#" class="button button-wc-fns-colors woocommerce-fns-case">Load a full example</a>' . ' or ' . '<a href="https://www.youtube.com/watch?v=sjQKbt2Nn7k" target="_blank" title="' . esc_html__('Watch 7 minutes video introduction on YouTube', 'fish-and-ships') . '" class="button woocommerce-fns-yt fns-show-videos"><span class="dashicons-before dashicons-video-alt3 fns-yt-on-button"></span>' . esc_html__('Watch video tutorials', 'fish-and-ships') . '</a></span>';
$inner_help .= '<div class="fns-hidden-videos"><p><a href="https://www.youtube.com/watch?v=wRsoUYiHQRY&ab_channel=WpCentricsFishAndShips" target="_blank" alt="See video on YouTube" class="fns-video-link"><img src="' . WC_FNS_URL . 'assets/img/video-1.png" width="232" height="130" /><span>General overview</span></a>';
$inner_help	.= '<a href="https://www.youtube.com/watch?v=sjQKbt2Nn7k&ab_channel=WpCentricsFishAndShips" target="_blank" alt="See video on YouTube" class="fns-video-link"><img src="' . WC_FNS_URL . 'assets/img/video-2.png" width="232" height="130" /><span>Short tutorial</span></a>';
$inner_help .= '<a href="https://www.youtube.com/watch?v=y2EJFluXx9Q&ab_channel=WpCentricsFishAndShips" target="_blank" alt="See video on YouTube" class="fns-video-link"><img src="' . WC_FNS_URL . 'assets/img/video-3.png" width="232" height="130" /><span>Shipping boxes</span></a></p></div>';
//			  . '</div>';

$settings = array(

	// The freemium panel
	'freemium' => array(
		'type'          => 'freemium_panel',
		'default'       => ''
	),

	'title' => array(
							// WooCommerce will escape HTML later 
							
		'title'         => __( 'Method title', 'woocommerce' ),
		'type'          => 'text',
		'description'   => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ) . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'default'       => 'Fish and Ships',
		'desc_tip'      => true,
	),
	
	'tax_status' => array(
		'title'         => __( 'Tax status', 'woocommerce' ),
		'type'          => 'select',
		//'class'         => 'wc-enhanced-select',
		'default'       => 'taxable',
		'options' => array(
			'taxable'   => __( 'Taxable', 'woocommerce' ),
			'none'      => _x( 'None', 'Tax status', 'woocommerce' ),
		),
		'description'   => __( 'Should taxes be applied over the calculated shipping cost?', 'fish-and-ships' ) . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'desc_tip'      => true,
	),

	'global_group_by' => array(
		'title' 		=> _x( 'Global group-by', 'shorted, label for global group-by activation', 'fish-and-ships' ),
		'type' 			=> 'checkbox',
		'label'         => __( 'All selection methods will use the same product grouping criterion.', 'fish-and-ships') . ($Fish_n_Ships->im_pro() ? '' : ' <span class="fns-pro-icon darker">PRO</span>'),
		'description' 	=> __( 'Uncheck it and you can set the group-by option for every selector (a bit messy but much more powerful).', 'fish-and-ships' ) . $free . ' ' . __('Click to open detailed help about Group by.', 'fish-and-ships'),
		'class'         => $Fish_n_Ships->im_pro() ? '' : 'onlypro',
		'default' 		=> 'yes',
		'desc_tip'		=> true,
	),

	'global_group_by_method' => array(
		'title'         => _x( '[for all selectors]', 'shorted, label for global group-by method select', 'fish-and-ships' ),
		'description'   => __( 'It will determine how the cart products should be grouped (or not) before analyzing if they match the selection conditions.', 'fish-and-ships' ) . ' ' . __('Click to open detailed help about Group by.', 'fish-and-ships'),
		'type'          => 'select',
		//'class'         => 'wc-enhanced-select group-by-global-select',
		'class'         => 'group-by-global-select',
		'default'       => 'none',
		'options'       => $Fish_n_Ships->get_group_by_options(),
		'desc_tip'		=> true,
	),
	
	'rules_charge' => array(
		'title'         => __( 'Calculation type', 'woocommerce' ),
		'type'          => 'select',
		//'class'         => 'wc-enhanced-select',
		'default'       => 'all',
		'options' => array(
			'all'       => __( 'Charge all matching rules', 'fish-and-ships' ),
			'max'       => __( 'Charge only the most expensive matching rule', 'fish-and-ships' ),
			'min'       => __( 'Charge only the most cheap matching rule', 'fish-and-ships' ),
		),
		'description'   => __( 'You can choose between charging all rules or only the most expensive/cheap one.', 'fish-and-ships' ) . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'desc_tip'		=> true,
	),

	'special_rate' => array(
		'title'         => '<strong>' . __( 'Shipping rules:', 'fish-and-ships') . '</strong> ' .  __('Set up the shipping rules below.', 'fish-and-ships' ),
		'type'          => 'title',
		'description'   => $inner_help,
		'default'       => '',
	),
	
	// since 1.1.6 multiple currency input
	'multiple_currency' => array(
		'title' 		=> __('Set prices for every currency', 'fish-and-ships' ),
		'type' 			=> 'checkbox',
		'label'         => $Fish_n_Ships->can_manually_costs_every_currency() ?
							__('Set currency MIN/MAX values and rates manually for every currency', 'fish-and-ships') : 
							__('Available only for multi-currency shops', 'fish-and-ships'),
		'description' 	=> $Fish_n_Ships->can_manually_costs_every_currency() ?
							__('Set MIN, MAX and shipping rates manually for every currency, instead of being translated using the exchange rate defined on your multi-currency configuration.', 'fish-and-ships') : 
							sprintf( __('This option is only compatible with: %s plugins', 'fish-and-ships'), 'WPML WooCommerce Multilingual, Official WC Multi-currency & Aelia'),
		'class'         => $Fish_n_Ships->can_manually_costs_every_currency() ? '' : 'fns-mc-unavailable',
		'default' 		=> 'no',
		'desc_tip'		=> true,
	),

	// The shipping rules table
	'shipping_rules' => array(
		'type'		    => 'shipping_rules_table',
		'default'       => ''
	),
	
	// since 1.1.2 free shipping
	'free_shipping' => array(
		'title' 		=> __( 'Allow free shipping', 'fish-and-ships' ),
		'type' 			=> 'checkbox',
		'label'         => __( 'Zero shipping rate calculation will offer free shipping.', 'fish-and-ships'),
		'description' 	=> __( 'Unchecked, a zero cost shipping rate calculation will disable this shipping method. However, any special action of "abort shipping method" triggered will disable it in any case.', 'fish-and-ships' ),
		'class'         => 'allow_free',
		'default' 		=> 'yes',
		'desc_tip'		=> true,
	),
	'disallow_other' => array(
		'title' 		=> '&nbsp;',
		'type' 			=> 'checkbox',
		'label'         => __( 'Disallow other shipping methods if this is free.', 'fish-and-ships') . ($Fish_n_Ships->im_pro() ? '' : ' <span class="fns-pro-icon darker">PRO</span>'),
		'description' 	=> __( 'If this method is priced zero (free shipping), no other methods will be offered.', 'fish-and-ships' ),
		'class'         => $Fish_n_Ships->im_pro() ? 'hide_others' : 'hide_others onlypro',
		'default' 		=> '',
		'desc_tip'		=> true,
	),

	'volumetric_weight_factor' => array(
		'title'         => 'DIM (' . __( 'Volumetric Weight Factor', 'fish-and-ships' ) . ' ' . get_option('woocommerce_dimension_unit') . '<sup style="font-size:0.75em; vertical-align:0.25em">3</sup>/' . get_option('woocommerce_weight_unit') . ')',
		'type'          => 'decimal',
		'description'   => __( 'The factor value to calculate the volumetric weight. Click to open detailed help about this.', 'fish-and-ships' ),
		'desc_tip'		=> true,
		'placeholder'   => __( 'i.e. 5000', 'fish-and-ships' ),
		'default'       => '',
	),
);

// Let's allow a min and max field for every currency
$currencies = $Fish_n_Ships->get_currencies();

$n = 0;
foreach ( $currencies as $currency => $symbol ) {

	$n++;
	// Main currency haven't sufix, it brings legacy with previous releases
	$lang_sufix = ''; if ( $n > 1 ) $lang_sufix = '-' . $currency;

	$settings['min_shipping_price' . $lang_sufix] = array(
		'title'             => __( 'Min shipping cost', 'fish-and-ships' ) . ' (' . $symbol . ')',
		'type'              => 'text',
		'class'				=> 'wc_fns_input_positive_decimal min_shipping_price_field ' . ($n == 1 ? 'currency-main' : 'currency-secondary'),
		'placeholder'       => '[none]',
		'description'       => __('The minimum shipping cost in any case (if any shipping rule matches).', 'fish-and-ships') . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'default'           => '',
		'desc_tip'          => true,
		'sanitize_callback' => array( $Fish_n_Ships, 'sanitize_cost' ),
	);
}

$n = 0;
foreach ( $currencies as $currency => $symbol ) {

	$n++;
	// Main currency haven't sufix, it brings legacy with previous releases
	$lang_sufix = ''; if ( $n > 1 ) $lang_sufix = '-' . $currency;

	$settings['max_shipping_price' . $lang_sufix] = array(

		'title'             => __( 'Max shipping cost', 'fish-and-ships' ) . ' (' . $symbol . ')',
		'type'              => 'text',
		'class'				=> 'wc_fns_input_positive_decimal max_shipping_price_field ' . ($n == 1 ? 'currency-main' : 'currency-secondary'),
		'placeholder'       => '[none]',
		'description'       => __('The maximum shipping cost in any case (if any shipping rule matches).', 'fish-and-ships') . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'default'           => '',
		'desc_tip'          => true,
		'sanitize_callback' => array( $Fish_n_Ships, 'sanitize_cost' ),
	);
}

$settings['write_logs'] = array(
		'title'         => __( 'Write logs', 'fish-and-ships' ),
		'type'          => 'select',
		//'class'         => '__wc-enhanced-select',
		'options' => array(
			'off'       => _x( 'Do not save logs from any users', 'log saving options', 'fish-and-ships' ),
			'admins'    => _x( 'Only for Administrators and Shop Managers', 'log saving options', 'fish-and-ships' ),
			'everyone'  => _x( 'All users (not recommended for production sites)', 'log saving options', 'fish-and-ships' ),
		),
		'default'       => 'off',
		'description'   => __( 'Really useful if you are testing your newly-configured shipping method.', 'fish-and-ships') . '<br>' . __('The logs will be stored in your database and are not sent anywhere.', 'fish-and-ships'),
		'desc_tip' 		=> __( 'It can aid in understanding why the shipping costs are not what you expect, and/or help us to debug.', 'fish-and-ships' ) . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
);

// The logs panel
$settings['the_logs'] = array(
		'type'		        => 'logs_panel',
		'default'           => ''
);

return $settings;
