<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Customizer Setup and Custom Controls
 *
 */

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class TSWC_Email_Customizer_Preview {

	public $defaults;
	public $status;
	public $slug_status;

	// Get our default values	
	public function __construct( $status = 'in_transit' ) {

		$this->status = $status;
		$slug_status = str_replace( '_', '', $status );
		$slug_status = 'delivered' == $slug_status ? 'delivered_status' : $slug_status;
		$this->slug_status = $slug_status;

		// Get our Customizer defaults
		$this->defaults = trackship_admin_customizer()->wcast_shipment_settings_defaults( $slug_status );
	}

	/**
	 * Get blog name formatted for emails.
	 *
	 * @return string
	 */
	public function get_blogname() {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}

	/**
	 * Set up preview
	 *
	 * @return void
	 */
	public function set_up_preview() {
		?>
		<head>
			<meta charset="<?php bloginfo('charset'); ?>" />
			<meta name="viewport" content="width=device-width" />
			<style type="text/css" id="ast_designer_custom_css">.woocommerce-store-notice.demo_store, .mfp-hide {display: none;}</style>
		</head>
		<body class="ast_preview_body" style="margin:0;">
			<div id="overlay"></div>
			<div id="ast_preview_wrapper" style="display: block;">
				<?php self::preview_email(); ?>
			</div>
			<?php do_action( 'woomail_footer' ); ?>
		</body>
		<?php
		exit;
	}

	/**
	 * Code for preview of in transit email
	*/
	public function preview_email() {
		// Load WooCommerce emails.

		$status = $this->status;
		$slug_status = $this->slug_status;

		$preview_id = get_option( 'email_preview', 'mockup' );
		$order = trackship_admin_customizer()->get_wc_order_for_preview( $preview_id );				
		// print_r($order);
		$email_heading = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_' . $slug_status . '_email_settings', 'wcast_' . $slug_status . '_email_heading', $this->defaults['wcast_' . $slug_status . '_email_heading']);		
		$email_heading = str_replace( '{site_title}', $this->get_blogname(), $email_heading );
		$email_heading = str_replace( '{order_number}', $order->get_order_number(), $email_heading );
		$email_heading = str_replace( '{shipment_status}', 'In Transit', $email_heading );
		
		$email_content = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_' . $slug_status . '_email_settings', 'wcast_' . $slug_status . '_email_content', $this->defaults['wcast_' . $slug_status . '_email_content']);
		$email_content = html_entity_decode( $email_content );
		
		$wcast_show_order_details = trackship_for_woocommerce()->ts_actions->get_checkbox_option_value_from_array('wcast_' . $slug_status . '_email_settings', 'wcast_' . $slug_status . '_show_order_details', $this->defaults['wcast_' . $slug_status . '_show_order_details']);
		
		$wcast_show_product_image = trackship_for_woocommerce()->ts_actions->get_checkbox_option_value_from_array('wcast_' . $slug_status . '_email_settings', 'wcast_' . $slug_status . '_show_product_image', $this->defaults['wcast_' . $slug_status . '_show_product_image']);

		$wcast_show_shipping_address = trackship_for_woocommerce()->ts_actions->get_checkbox_option_value_from_array('wcast_' . $slug_status . '_email_settings', 'wcast_' . $slug_status . '_show_shipping_address', $this->defaults['wcast_' . $slug_status . '_show_shipping_address']);		
		
		$sent_to_admin = false;
		$plain_text = false;
		$email = '';
		
		// get the preview email subject
		$email_heading = __( $email_heading, 'trackship-for-woocommerce' );
		//ob_start();
		
		$message = wc_trackship_email_manager()->email_content( $email_content, $preview_id, $order );
		
		$wcast_analytics_link = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('wcast_' . $slug_status . '_email_settings', 'wcast_' . $slug_status . '_analytics_link', '');
		
		if ( $wcast_analytics_link ) {
			$regex = '#(<a href=")([^"]*)("[^>]*?>)#i';
			$message = preg_replace_callback($regex, array( $this, '_appendCampaignToString'), $email_content);
		}
		
		$shipment_row = trackship_admin_customizer()->get_wc_shipment_row_for_preview( $status, $preview_id );
		$tracking_items = trackship_admin_customizer()->get_tracking_items_for_preview( $preview_id );
		
		$local_template	= get_stylesheet_directory() . '/woocommerce/emails/tracking-info.php';
		if ( file_exists( $local_template ) && is_writable( $local_template ) ) {
			$message .= wc_get_template_html( 'emails/tracking-info.php', array(
				'tracking_items'	=> $tracking_items,
				'shipment_row'		=> $shipment_row,
				'order_id'			=> $preview_id,
				'show_shipment_status' => true,
				'new_status'		=> 'pickup_reminder' == $status ? 'available_for_pickup' : $status,
				'ts4wc_preview'		=> true,
			), 'woocommerce-advanced-shipment-tracking/', get_stylesheet_directory() . '/woocommerce/' );
		} else {
			$message .= wc_get_template_html( 'emails/tracking-info.php', array(
				'tracking_items'	=> $tracking_items,
				'shipment_row'		=> $shipment_row,
				'order_id'			=> $preview_id,
				'show_shipment_status' => true,
				'new_status'		=> 'pickup_reminder' == $status ? 'available_for_pickup' : $status,
				'ts4wc_preview'		=> true,
			), 'woocommerce-advanced-shipment-tracking/', trackship_for_woocommerce()->get_plugin_path() . '/templates/' );
		}

		// Order detail template
		$message .= wc_get_template_html(
			'emails/tswc-email-order-details.php',
			array(
				'order'         => $order,
				'sent_to_admin' => $sent_to_admin,
				'plain_text'    => $plain_text,
				'email'         => $email,
				'wcast_show_product_image' => $wcast_show_product_image,
				'wcast_show_order_details' => $wcast_show_order_details,
				'ts4wc_preview' => true,
			),
			'woocommerce-advanced-shipment-tracking/', 
			trackship_for_woocommerce()->get_plugin_path() . '/templates/'
		);
		
		if ( 'pickup_reminder' != $status ) {
			// Shipping Address template
			$message .= wc_get_template_html(
				'emails/shipping-email-addresses.php', array(
					'order'         => $order,
					'sent_to_admin' => $sent_to_admin,
					'ts4wc_preview' => true,
					'wcast_show_shipping_address' => $wcast_show_shipping_address,
				),
				'woocommerce-advanced-shipment-tracking/', 
				trackship_for_woocommerce()->get_plugin_path() . '/templates/'
			);
		}
		
		$mailer = WC()->mailer();
		// create a new email
		$email = new WC_Email();
		
		add_filter( 'wp_kses_allowed_html', array( trackship_admin_customizer(), 'my_allowed_tags' ) );
		add_filter( 'safe_style_css', array( trackship_admin_customizer(), 'safe_style_css_callback' ), 10, 1 );
		add_filter( 'woocommerce_email_styles', array( trackship_admin_customizer(), 'shipment_email_preview_css' ), 9999, 2 );

		add_filter( 'woocommerce_email_footer_text', array( $this, 'email_footer_text' ) );
		
		// wrap the content with the email template and then add styles
		$email_html = apply_filters( 'woocommerce_mail_content', $email->style_inline( $mailer->wrap_message( $email_heading, $message ) ) );
		$email_html = apply_filters( 'trackship_mail_content', $email_html, $email_heading );
		echo wp_kses_post($email_html);
	}

	/**
	 * Code for format email subject
	*/
	public function email_footer_text( $footer_text ) {
		$unsubscribe = get_trackship_settings( 'enable_email_widget' ) ? '<div style="text-align:center;"><a href="#">' . esc_html__( 'Unsubscribe', 'trackship-for-woocommerce' ) . '</a></div>' : '';
		$class = $unsubscribe ? 'hide' : '';
		$default_footer = '<div class="default_footer ' . $class . '">' . $footer_text . '</div>';
		return $unsubscribe . $default_footer;
	}

	/**
	 * Code for append analytics link in email content
	*/
	public function _appendCampaignToString( $match ) {
		$wcast_intransit_analytics_link = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'wcast_intransit_email_settings', 'wcast_intransit_analytics_link', '' );
		
		$url = $match[2];
		if (strpos($url, '?') === false) {
			$url .= '?';
		}
		$url .= $wcast_intransit_analytics_link;
		return $match[1] . $url . $match[3];
	}
}


/**
 * Initialise our Customizer settings
*/
new TSWC_Email_Customizer_Preview();
