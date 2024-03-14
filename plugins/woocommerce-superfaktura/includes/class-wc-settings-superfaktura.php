<?php
/**
 * SuperFaktúra WooCommerce.
 *
 * @package   SuperFaktúra WooCommerce
 * @author    2day.sk <superfaktura@2day.sk>
 * @copyright 2022 2day.sk s.r.o., Webikon s.r.o.
 * @license   GPL-2.0+
 * @link      https://www.superfaktura.sk/integracia/
 */

/**
 * WC_Settings_SuperFaktura.
 *
 * @package SuperFaktúra WooCommerce
 * @author  2day.sk <superfaktura@2day.sk>
 */
class WC_Settings_SuperFaktura extends WC_Settings_Page {

	/**
	 * Allowed tags in HTML output.
	 *
	 * @var array
	 */
	protected $allowed_tags;



	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		$this->id    = 'superfaktura';
		$this->label = __( 'SuperFaktúra', 'woocommerce-superfaktura' );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );

		$this->allowed_tags = wp_kses_allowed_html( 'post' );
		$this->allowed_tags['style'] = array( 'type' );
	}



	/**
	 * Get or create default secret key.
	 */
	private function get_or_create_default_secret_key() {
		$secret_key = get_option( 'woocommerce_sf_sync_secret_key', false );
		if ( false === $secret_key ) {
			$secret_key = $this->create_default_secret_key();
			update_option( 'woocommerce_sf_sync_secret_key', $secret_key );
		}

		return $secret_key;
	}



	/**
	 * Create default secret key.
	 */
	private function create_default_secret_key() {
		return WC_Secret_Key_Helper::generate_secret_key();
	}



	/**
	 * Get list of payment methods from SuperFaktura.
	 */
	private function get_sf_payment_methods() {

		try {
			$wcsf     = WC_SuperFaktura::get_instance();
			$api      = $wcsf->sf_api();
			$response = $api->get( '/dashboard/full_data' );

			$payment_methods = array(
				'0' => __( 'Don\'t use', 'woocommerce-superfaktura' ),
			);

			foreach ( $response->company->custom_options->invoice->payment as $payment ) {
				if ( 1 != $payment->active || 'custom' === $payment->key ) {
					continue;
				}

				$payment_methods[ $payment->key ] = $payment->name;
			}

			$payment_methods['other'] = __( 'Other', 'woocommerce-superfaktura' );
		} catch ( Exception $e ) {
			$payment_methods = array(
				'0'             => __( 'Don\'t use', 'woocommerce-superfaktura' ),
				'cash'          => __( 'Cash', 'woocommerce-superfaktura' ),
				'cod'           => __( 'Cash on delivery', 'woocommerce-superfaktura' ),
				'transfer'      => __( 'Transfer', 'woocommerce-superfaktura' ),
				'card'          => __( 'Card', 'woocommerce-superfaktura' ),
				'credit'        => __( 'Credit card', 'woocommerce-superfaktura' ),
				'debit'         => __( 'Debit card', 'woocommerce-superfaktura' ),
				'barion'        => __( 'Barion', 'woocommerce-superfaktura' ),
				'besteron'      => __( 'Besteron', 'woocommerce-superfaktura' ),
				'gopay'         => __( 'GoPay', 'woocommerce-superfaktura' ),
				'paypal'        => __( 'Paypal', 'woocommerce-superfaktura' ),
				'trustpay'      => __( 'Trustpay', 'woocommerce-superfaktura' ),
				'viamo'         => __( 'Viamo', 'woocommerce-superfaktura' ),
				'inkaso'        => __( 'Encashment', 'woocommerce-superfaktura' ),
				'postal_order'  => __( 'Postal money order', 'woocommerce-superfaktura' ),
				'accreditation' => __( 'Mutual credit', 'woocommerce-superfaktura' ),
				'other'         => __( 'Other', 'woocommerce-superfaktura' ),
			);
		}

		return $payment_methods;
	}



	/**
	 * Get list of shipping methods from SuperFaktura.
	 */
	private function get_sf_shipping_methods() {
		try {
			$wcsf     = WC_SuperFaktura::get_instance();
			$api      = $wcsf->sf_api();
			$response = $api->get( '/dashboard/full_data' );

			$shipping_methods = array(
				'0' => __( 'Don\'t use', 'woocommerce-superfaktura' ),
			);

			foreach ( $response->company->custom_options->invoice->delivery as $delivery ) {
				if ( 1 != $delivery->active || 'custom' === $delivery->key ) {
					continue;
				}

				$shipping_methods[ $delivery->key ] = $delivery->name;
			}
		} catch ( Exception $e ) {
			$shipping_methods = array(
				'0'            => __( 'Don\'t use', 'woocommerce-superfaktura' ),
				'mail'         => __( 'By mail', 'woocommerce-superfaktura' ),
				'courier'      => __( 'By courier', 'woocommerce-superfaktura' ),
				'personal'     => __( 'Personal pickup', 'woocommerce-superfaktura' ),
				'haulage'      => __( 'Freight', 'woocommerce-superfaktura' ),
				'pickup_point' => __( 'Pickup point', 'woocommerce-superfaktura' ),
			);
		}

		return $shipping_methods;
	}



	/**
	 * Create sections.
	 */
	public function get_sections() {

		$sections = array(
			''                 => __( 'Authorization', 'woocommerce-superfaktura' ),
			'invoice'          => __( 'Invoice', 'woocommerce-superfaktura' ),
			'invoice_creation' => __( 'Invoice Creation', 'woocommerce-superfaktura' ),
			'integration'      => __( 'Integration', 'woocommerce-superfaktura' ),
			'payment'          => __( 'Payment', 'woocommerce-superfaktura' ),
			'shipping'         => __( 'Shipping', 'woocommerce-superfaktura' ),
			'accounting'       => __( 'Accounting', 'woocommerce-superfaktura' ),
			'api_log'          => __( 'API log', 'woocommerce-superfaktura' ),
			'help'             => __( 'Help', 'woocommerce-superfaktura' ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}



	/**
	 * Create settings.
	 *
	 * @param string $current_section Optional. Defaults to empty string.
	 */
	public function get_settings( $current_section = '' ) {
		$wc_gateways = WC()->payment_gateways();
		$gateways    = $wc_gateways->payment_gateways;

		$settings = array();
		switch ( $current_section ) {

			case 'invoice':
				$settings = array(
					array(
						'title' => __( 'Invoice Options', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title2',
					),
					array(
						'title' => __( 'Proforma Invoice Sequence ID', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_proforma_invoice_sequence_id',
						'desc'  => '',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Invoice Sequence ID', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_invoice_sequence_id',
						'desc'  => '',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Credit Note Sequence ID', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_cancel_sequence_id',
						'desc'  => '',
						'type'  => 'text',
					),
					array(
						'title'   => __( 'Custom invoice numbering', 'woocommerce-superfaktura' ),
						'desc'    => __( 'Use custom invoice numbering (this is a deprecated option, please use sequence IDs above instead)', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_invoice_custom_num',
						'default' => 'no',
						'type'    => 'checkbox',
					),
					array(
						'title'   => __( 'Proforma Invoice Nr.', 'woocommerce-superfaktura' ),
						// Translators: %s Available tags.
						'desc'    => sprintf( __( 'Available Tags: %s', 'woocommerce-superfaktura' ), '[YEAR], [YEAR_SHORT], [MONTH], [DAY], [COUNT], [ORDER_NUMBER]' ),
						'id'      => 'woocommerce_sf_invoice_proforma_id',
						'default' => 'ZAL[YEAR][MONTH][COUNT]',
						'type'    => 'text',
						'class'   => 'custom-invoice-numbering-item',
					),
					array(
						'title'   => __( 'Invoice Nr.', 'woocommerce-superfaktura' ),
						// Translators: %s Available tags.
						'desc'    => sprintf( __( 'Available Tags: %s', 'woocommerce-superfaktura' ), '[YEAR], [YEAR_SHORT], [MONTH], [DAY], [COUNT], [ORDER_NUMBER]' ),
						'id'      => 'woocommerce_sf_invoice_regular_id',
						'default' => '[YEAR][MONTH][COUNT]',
						'type'    => 'text',
						'class'   => 'custom-invoice-numbering-item',
					),
					array(
						'title'   => __( 'Credit Note Nr.', 'woocommerce-superfaktura' ),
						// Translators: %s Available tags.
						'desc'    => sprintf( __( 'Available Tags: %s', 'woocommerce-superfaktura' ), '[YEAR], [YEAR_SHORT], [MONTH], [DAY], [COUNT], [ORDER_NUMBER]' ),
						'id'      => 'woocommerce_sf_invoice_cancel_id',
						'default' => '[YEAR][MONTH][COUNT]',
						'type'    => 'text',
						'class'   => 'custom-invoice-numbering-item',
					),
					array(
						'title'             => __( 'Current Proforma Invoice Number for [COUNT]', 'woocommerce-superfaktura' ),
						'id'                => 'woocommerce_sf_invoice_proforma_count',
						'default'           => '1',
						'type'              => 'number',
						'class'             => 'wi-small',
						'custom_attributes' => array(
							'min'  => 1,
							'step' => 1,
						),
						'class'             => 'custom-invoice-numbering-item',
					),
					array(
						'title'             => __( 'Current Invoice Number for [COUNT]', 'woocommerce-superfaktura' ),
						'id'                => 'woocommerce_sf_invoice_regular_count',
						'default'           => '1',
						'type'              => 'number',
						'class'             => 'wi-small',
						'custom_attributes' => array(
							'min'  => 1,
							'step' => 1,
						),
						'class'             => 'custom-invoice-numbering-item',
					),
					array(
						'title'             => __( 'Current Credit Note Number for [COUNT]', 'woocommerce-superfaktura' ),
						'id'                => 'woocommerce_sf_invoice_cancel_count',
						'default'           => '1',
						'type'              => 'number',
						'class'             => 'wi-small',
						'custom_attributes' => array(
							'min'  => 1,
							'step' => 1,
						),
						'class'             => 'custom-invoice-numbering-item',
					),
					array(
						'title'             => __( 'Number of digits for [COUNT]', 'woocommerce-superfaktura' ),
						'id'                => 'woocommerce_sf_invoice_count_decimals',
						'default'           => '4',
						'type'              => 'number',
						'class'             => 'wi-small',
						'custom_attributes' => array(
							'min'  => 1,
							'step' => 1,
						),
						'class'             => 'custom-invoice-numbering-item',
					),
					array(
						'title'   => __( 'Delivery name', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_invoice_delivery_name',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Use format <em>CompanyName - FirstName LastName</em>', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Invoice language', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_invoice_language',
						'default' => 'endpoint',
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => array(
							'endpoint' => __( 'Default endpoint language', 'woocommerce-superfaktura' ),
							'locale'   => __( 'Site locale (fallback to endpoint)', 'woocommerce-superfaktura' ),
							'wpml'     => __( 'WPML', 'woocommerce-superfaktura' ),
							'slo'      => __( 'Slovak', 'woocommerce-superfaktura' ),
							'cze'      => __( 'Czech', 'woocommerce-superfaktura' ),
							'eng'      => __( 'Εnglish', 'woocommerce-superfaktura' ),
							'deu'      => __( 'German', 'woocommerce-superfaktura' ),
							'nld'      => __( 'Dutch', 'woocommerce-superfaktura' ),
							'hrv'      => __( 'Croatian', 'woocommerce-superfaktura' ),
							'hun'      => __( 'Hungarian', 'woocommerce-superfaktura' ),
							'pol'      => __( 'Polish', 'woocommerce-superfaktura' ),
							'rom'      => __( 'Romanian', 'woocommerce-superfaktura' ),
							'rus'      => __( 'Russian', 'woocommerce-superfaktura' ),
							'slv'      => __( 'Slovenian', 'woocommerce-superfaktura' ),
							'spa'      => __( 'Spanish', 'woocommerce-superfaktura' ),
							'ita'      => __( 'Italian', 'woocommerce-superfaktura' ),
							'ukr'      => __( 'Ukrainian', 'woocommerce-superfaktura' ),
						),
					),
					array(
						'title'   => __( 'VAT rounding', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_rounding',
						'type'    => 'select',
						'default' => ( wc_prices_include_tax() ) ? 'item_ext' : 'document',
						'options' => array(
							'document' => __( 'Whole document', 'woocommerce-superfaktura' ),
							'item'     => __( 'Per item', 'woocommerce-superfaktura' ),
							'item_ext' => __( 'Retail (recommended for eshops)', 'woocommerce-superfaktura' ),
						),
					),
					array(
						'title'   => __( 'Rounding for cash on delivery orders', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_cod_add_rounding_item',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Add rounding to the document (to 5 cents for EUR and to the whole number for CZK)', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Free products', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_skip_free_products',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Do not add free products to the invoice', 'woocommerce-superfaktura' ),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_wi_invoice_title2',
					),
					array(
						'title' => __( 'Invoice Comments', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title8',
					),
					array(
						'title'   => __( 'Allow custom comments', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_comments',
						'default' => 'yes',
						'type'    => 'checkbox',
						'desc'    => __( 'Override default comments options in SuperFaktúra. Adds custom comment, order comment and tax liability if needed.', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Proforma invoice payment', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_comment_add_proforma_payment',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Add information about proforma invoice payment to comment.', 'woocommerce-superfaktura' ),
						'class'   => 'custom-comment-item',
					),
					array(
						'title'   => __( 'Tax Liability', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_tax_liability',
						'class'   => 'input-text wide-input custom-comment-item',
						'default' => 'Dodanie tovaru je oslobodené od dane. Dodanie služby podlieha preneseniu daňovej povinnosti.',
						'type'    => 'textarea',
					),
					array(
						'title' => __( 'Comment', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_comment',
						'class' => 'input-text wide-input custom-comment-item',
						'css'   => 'width:100%; height: 75px;',
						'type'  => 'textarea',
					),
					array(
						'title'   => __( 'Order note', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_comment_add_order_note',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Add order note from customer to comment.', 'woocommerce-superfaktura' ),
						'class'   => 'custom-comment-item',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_wi_invoice_title8',
					),
					array(
						'title' => __( 'Additional Invoice Fields', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_wi_invoice_title3',
					),
					array(
						'title'   => __( 'Variable symbol', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_variable_symbol',
						'type'    => 'radio',
						'default' => 'invoice_nr',
						'options' => array(
							'invoice_nr' => __( 'Use invoice number', 'woocommerce-superfaktura' ),
							'invoice_nr_match' => __( 'Use invoice number (if there is a proforma invoice, use the variable symbol from the proforma invoice)', 'woocommerce-superfaktura' ),
							'order_nr'   => __( 'Use order number', 'woocommerce-superfaktura' ),
						),
					),
					array(
						'title'   => __( 'PAY by square', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_bysquare',
						'type'    => 'checkbox',
						'desc'    => __( 'Display a QR code', 'woocommerce-superfaktura' ),
						'default' => 'yes',
					),
					array(
						'title' => __( 'Issued by', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_issued_by',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Issued by Phone', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_issued_phone',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Issued by Web', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_issued_web',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Issued by Email', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_issued_email',
						'type'  => 'text',
					),
					array(
						'title'   => __( 'Created Date', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_created_date_as_order',
						'type'    => 'checkbox',
						'desc'    => __( 'Use order date instead of current date', 'woocommerce-superfaktura' ),
						'default' => 'no',
					),
					array(
						'title'   => __( 'Delivery Date', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_delivery_date_value',
						'type'    => 'select',
						'desc'    => __( 'Select date to be used as delivery date', 'woocommerce-superfaktura' ),
						'default' => 'invoice_created',
						'options' => array(
							'invoice_created' => __( 'Invoice creation date', 'woocommerce-superfaktura' ),
							'order_paid'      => __( 'Order payment date', 'woocommerce-superfaktura' ),
							'order_created'   => __( 'Order creation date', 'woocommerce-superfaktura' ),
							'none'            => __( 'Do not display', 'woocommerce-superfaktura' ),
						),
					),
					array(
						'title'   => __( 'Product Description', 'woocommerce-superfaktura' ),
						'desc'    =>
							// Translators: %s Available tags.
							sprintf( __( 'Available Tags: %s', 'woocommerce-superfaktura' ), '[SKU], [SHORT_DESCR], [VARIATION], [ATTRIBUTES], [NON_VARIATIONS_ATTRIBUTES], [ATTRIBUTE:name], [WEIGHT]' )
							. '<br><em><small>' . __( '[ATTRIBUTE:name] tag outputs single product attribute. Use the name of the attribute, for example [ATTRIBUTE:Shirt size]', 'woocommerce-superfaktura' ) . '</small></em>',
						'id'      => 'woocommerce_sf_product_description',
						'css'     => 'width:50%; height: 75px;',
						'default' => '[ATTRIBUTES]' . ( 'yes' === get_option( 'woocommerce_sf_product_description_visibility', 'yes' ) ? "\n[SHORT_DESCR]" : '' ),
						'type'    => 'textarea',
					),
					array(
						'title'   => __( 'Discount', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_product_description_show_discount',
						'type'    => 'checkbox',
						'desc'    => __( 'Show product discount in description', 'woocommerce-superfaktura' ),
						'default' => 'yes',
					),
					array(
						'title'   => __( 'Coupon Invoice Items', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_coupon_invoice_items',
						'type'    => 'radio',
						'desc'    => '',
						'default' => 'total',
						'options' => array(
							'total'    => __( 'Total', 'woocommerce-superfaktura' ),
							'per_item' => __( 'Per Item', 'woocommerce-superfaktura' ),
						),
					),
					array(
						'title'   => __( 'Coupon Description', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_product_description_show_coupon_code',
						'type'    => 'checkbox',
						'desc'    => __( 'Show coupon code in description', 'woocommerce-superfaktura' ),
						'default' => 'yes',
					),
					array(
						'title'   => __( 'Discount Name', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_discount_name',
						'default' => 'Zľava',
						'desc'    => '',
						'type'    => 'text',
					),
					array(
						'title'   => __( 'Shipping Item Name', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_shipping_item_name',
						'default' => 'Poštovné',
						'desc'    => '',
						'type'    => 'text',
					),
					array(
						'title'   => __( 'Free Shipping Name', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_free_shipping_name',
						'default' => '',
						'desc'    => '<br>' . __( 'By default, in case of free shipping, the invoice does not contain shipping item; to force the item to appear, fill in its name in this field', 'woocommerce-superfaktura' ),
						'type'    => 'text',
					),
					array(
						'title'   => __( 'Refunded items', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_product_subtract_refunded_qty',
						'type'    => 'checkbox',
						'desc'    => __( 'Subtract refunded items quantity on invoice', 'woocommerce-superfaktura' ),
						'default' => 'no',
					),
					array(
						'title'   => __( 'Tag', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_invoice_tag',
						'default' => '',
						'desc'    => '',
						'type'    => 'text',
					),
					array(
						'title'   => __( 'OSS', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_oss',
						'type'    => 'checkbox',
						'desc'    => __( 'Create invoices under the One Stop Shop scheme', 'woocommerce-superfaktura' ),
						'default' => 'no',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_wi_invoice_title3',
					),
				);

				$settings = apply_filters( 'superfaktura_invoice_settings', $settings );
				break;

			case 'invoice_creation':
				$wc_get_order_statuses = $this->get_order_statuses();

				$shop_order_status = array( '0' => __( 'Don\'t generate', 'woocommerce-superfaktura' ) );
				$shop_order_status = array_merge( $shop_order_status, $wc_get_order_statuses );

				$settings[] = array(
					'title' => __( 'Invoice Creation', 'woocommerce-superfaktura' ),
					'type'  => 'title',
					'desc'  => __( 'Select when you would like to create an invoice for each payment gateway.', 'woocommerce-superfaktura' ),
					'id'    => 'woocommerce_wi_invoice_creation1',
				);

				foreach ( $gateways as $gateway ) {
					$settings[] = array(
						'title'   => $gateway->title,
						'id'      => 'woocommerce_sf_invoice_regular_' . $gateway->id,
						'default' => 0,
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => $shop_order_status,
					);

					$settings[] = array(
						'title'   => '',
						'id'      => 'woocommerce_sf_invoice_regular_' . $gateway->id . '_set_as_paid',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Create as paid', 'woocommerce-superfaktura' ),
					);
				}

				$settings[] = array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_wi_invoice_creation1',
				);

				$settings[] = array(
					'type' => 'title',
					'id'   => 'woocommerce_wi_invoice_creation2',
				);

				$settings[] = array(
					'title'   => __( 'Zero value invoices', 'woocommerce-superfaktura' ),
					// The fake payment ID makes it look like one of the payment gateways above.
					'id'      => 'woocommerce_sf_invoice_regular_' . WC_SuperFaktura::$zero_value_order_fake_payment_method_id,
					'default' => '',
					'type'    => 'select',
					'class'   => 'wc-enhanced-select',
					'options' => $shop_order_status,
					'desc'    => __( 'Allow zero value invoices to be generated when order changes to the selected status.', 'woocommerce-superfaktura' ),
				);

				$settings[] = array(
					'title'   => __( 'Invoice for orders without processing', 'woocommerce-superfaktura' ),
					'id'      => 'woocommerce_sf_invoice_regular_processing_skipped_fix',
					'default' => 'no',
					'type'    => 'checkbox',
					'desc'    => sprintf(
						// Translators: %1$s Order status, %2$s Order status.
						__( 'Allow invoice creation in the "%1$s" state for orders that do not need processing. Only applies if the invoice was supposed to be created in "%2$s" state.', 'woocommerce-superfaktura' ),
						$wc_get_order_statuses['completed'],
						$wc_get_order_statuses['processing']
					),
				);

				$settings[] = array(
					'title'   => __( 'Manual Invoice Creation', 'woocommerce-superfaktura' ),
					'id'      => 'woocommerce_sf_invoice_regular_manual',
					'default' => 'no',
					'type'    => 'checkbox',
					'desc'    => __( 'Allow manual invoice creation', 'woocommerce-superfaktura' ),
				);

				$settings[] = array(
					'title'   => __( 'Client Data', 'woocommerce-superfaktura' ),
					'id'      => 'woocommerce_sf_invoice_update_addressbook',
					'default' => 'no',
					'type'    => 'checkbox',
					'desc'    => __( 'Update client data in SuperFaktura', 'woocommerce-superfaktura' ),
				);

				// Set default values.
				$default_options = array( 'completed' );
				// Backward compatibility with previous options woocommerce_sf_invoice_regular_processing_set_as_paid and woocommerce_sf_invoice_regular_dont_set_as_paid.
				if ( 'yes' === get_option( 'woocommerce_sf_invoice_regular_processing_set_as_paid', 'no' ) ) {
					$default_options[] = 'processing';
				}
				if ( 'yes' === get_option( 'woocommerce_sf_invoice_regular_dont_set_as_paid', 'no' ) ) {
					// Remove 'completed'.
					array_shift( $default_options );
				}

				$settings[] = array(
					'title'   => __( 'Set invoice as paid in these order statuses', 'woocommerce-superfaktura' ),
					'id'      => 'woocommerce_sf_invoice_set_as_paid_statuses',
					'default' => $default_options,
					'type'    => 'multiselect',
					'class'   => 'wc-enhanced-select',
					'options' => $wc_get_order_statuses,
				);

				$settings[] = array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_wi_invoice_creation2',
				);

				$settings[] = array(
					'title' => __( 'Proforma Invoice Creation', 'woocommerce-superfaktura' ),
					'type'  => 'title',
					'desc'  => __( 'Select when you would like to create a proforma invoice for each payment gateway.', 'woocommerce-superfaktura' ),
					'id'    => 'woocommerce_wi_invoice_creation3',
				);

				foreach ( $gateways as $gateway ) {
					$settings[] = array(
						'title'   => $gateway->title,
						'id'      => 'woocommerce_sf_invoice_proforma_' . $gateway->id,
						'default' => 0,
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => $shop_order_status,
					);
				}

				$settings[] = array(
					'title'   => __( 'Manual Proforma Invoice Creation', 'woocommerce-superfaktura' ),
					'id'      => 'woocommerce_sf_invoice_proforma_manual',
					'default' => 'no',
					'type'    => 'checkbox',
					'desc'    => __( 'Allow manual proforma invoice creation', 'woocommerce-superfaktura' ),
				);

				$settings[] = array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_wi_invoice_creation3',
				);

				$settings[] = array(
					'title' => __( 'Experimental functions', 'woocommerce-superfaktura' ),
					'type'  => 'title',
					'desc'  => __( 'These features are either experimental or incomplete, enable them at your own risk!', 'woocommerce-superfaktura' ),
					'id'    => 'woocommerce_wi_invoice_creation4',
				);

				$settings[] = array(
					'title'   => __( 'Prevent document duplicity', 'woocommerce-superfaktura' ),
					'id'      => 'woocommerce_sf_prevent_concurrency',
					'default' => 'no',
					'type'    => 'checkbox',
					'desc'    => __( 'Prevent document duplicity caused by concurrent callbacks and/or return URLs from some payment plugins (such as GoPay)', 'woocommerce-superfaktura' ),
				);

				$settings[] = array(
					'title'   => __( 'Retry failed API calls to create a document', 'woocommerce-superfaktura' ),
					'id'      => 'woocommerce_sf_retry_failed_api_calls',
					'default' => 'no',
					'type'    => 'checkbox',
					'desc'    => __( 'If the API call to create a document fails due to a SuperFaktura server error or timeout, try again in 5 minutes, 30 minutes and 1 hour. If all attempts fail, the plugin will display an admin notification and add the information to the order notes.', 'woocommerce-superfaktura' ),
				);

				$settings[] = array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_wi_invoice_creation4',
				);

				$settings = apply_filters( 'superfaktura_invoice_creation_settings', $settings );
				break;

			case 'integration':
				$settings = array(
					array(
						'title' => __( 'Checkout', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title12',
					),
					array(
						'title'   => __( 'Billing fields', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_add_company_billing_fields',
						'default' => 'yes',
						'type'    => 'checkbox',
						'desc'    => __( 'Add company billing fields to checkout', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Company name', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_add_company_billing_fields_name',
						'type'    => 'select',
						'options' => array(
							'optional' => __( 'Optional', 'woocommerce-superfaktura' ),
							'required' => __( 'Required', 'woocommerce-superfaktura' ),
						),
						'default' => 'optional',
						'desc'    => '',
						'class'   => 'company-billing-fields-item',
					),
					array(
						'title'   => __( 'Add field ID #', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_add_company_billing_fields_id',
						'type'    => 'select',
						'options' => array(
							'optional' => __( 'Optional', 'woocommerce-superfaktura' ),
							'required' => __( 'Required', 'woocommerce-superfaktura' ),
							'no'       => __( 'No', 'woocommerce-superfaktura' ),
						),
						'default' => 'optional',
						'desc'    => '',
						'class'   => 'company-billing-fields-item',
					),
					array(
						'title'   => __( 'Add field VAT #', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_add_company_billing_fields_vat',
						'type'    => 'select',
						'options' => array(
							'optional' => __( 'Optional', 'woocommerce-superfaktura' ),
							'required' => __( 'Required', 'woocommerce-superfaktura' ),
							'no'       => __( 'No', 'woocommerce-superfaktura' ),
						),
						'default' => 'optional',
						'desc'    => '',
						'class'   => 'company-billing-fields-item',
					),
					array(
						'title'   => __( 'Validate VAT #', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_validate_eu_vat_number',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Validate VAT # in EU countries', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Add field TAX ID #', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_add_company_billing_fields_tax',
						'type'    => 'select',
						'options' => array(
							'optional' => __( 'Optional', 'woocommerce-superfaktura' ),
							'required' => __( 'Required', 'woocommerce-superfaktura' ),
							'no'       => __( 'No', 'woocommerce-superfaktura' ),
						),
						'default' => 'optional',
						'desc'    => '',
						'class'   => 'company-billing-fields-item',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_wi_invoice_title12',
					),
					array(
						'title' => __( 'Order received', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title11',
					),
					array(
						'title'   => __( 'Invoice link', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_order_received_invoice_link',
						'default' => 'yes',
						'type'    => 'checkbox',
						'desc'    => __( 'Add invoice link to order received screen', 'woocommerce-superfaktura' ),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_wi_invoice_title11',
					),
					array(
						'title' => __( 'Emails', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title10',
					),
					array(
						'title'   => __( 'Billing details', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_email_billing_details',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Add customer billing details (ID #, VAT #, TAX ID #) to WooCommerce emails', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Online payment link', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_email_payment_link',
						'default' => 'yes',
						'type'    => 'checkbox',
						'desc'    => __( 'Add online payment link to WooCommerce emails', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Invoice link', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_email_invoice_link',
						'default' => 'yes',
						'type'    => 'checkbox',
						'desc'    => __( 'Add invoice link to WooCommerce emails', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Invoice PDF attachment', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_invoice_pdf_attachment',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Attach invoice PDF to WooCommerce emails', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Completed orders', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_completed_email_skip_invoice',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Don\'t add invoice to WooCommerce emails for completed orders', 'woocommerce-superfaktura' ),
					),
					array(
						'title'   => __( 'Cash on delivery orders', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_cod_email_skip_invoice',
						'default' => 'no',
						'type'    => 'checkbox',
						'desc'    => __( 'Don\'t add invoice to WooCommerce emails for cash on delivery orders', 'woocommerce-superfaktura' ),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_wi_invoice_title10',
					),
					array(
						'title' => __( 'Admin', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title13',
					),
					array(
						'title'   => __( 'Order list', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_invoice_download_button_actions',
						'type'    => 'checkbox',
						'desc'    => __( 'Show invoice action button in order list', 'woocommerce-superfaktura' ),
						'default' => 'no',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_sf_invoice_title13',
					),
					array(
						'title' => __( 'Automatic pairing', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '
							<p><strong>' . __( 'Default', 'woocommerce-superfaktura' ) . '</strong>:
							' . sprintf(
								// Translators: %1$s SuperFaktura edit profile URL, %2$s Callback URL.
								__( 'To automatically update order status when payment is paired to an invoice in SuperFaktura, fill in callback URL and Secret Key in <a target="_blank" href="%1$s">SuperFaktura settings</a>.<br>Callback URL: <strong>%2$s</strong>', 'woocommerce-superfaktura' ),
								( 'cz' === get_option( 'woocommerce_sf_lang', 'sk' ) ) ? 'https://moje.superfaktura.cz/users/edit_profile/settings#tab-bmails' : 'https://moja.superfaktura.sk/users/edit_profile/settings#tab-bmails',
								site_url( '/' ) . '?callback=wc_sf_order_paid'
							) . '</p>
							<p><strong>' . __( 'Multiple eshops per single company', 'woocommerce-superfaktura' ) . '</strong>:
							' . __( 'Plugin will send Callback URL to SuperFaktura automatically for each document issued.', 'woocommerce-superfaktura' ) . '</p>
						',
						'id'    => 'woocommerce_sf_invoice_title9',
					),
					array(
						'title'   => __( 'Automatic pairing type', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_sync_type',
						'type'    => 'radio',
						'default' => 'single',
						'options' => array(
							'single' => __( 'Default', 'woocommerce-superfaktura' ),
							'multi'  => __( 'Multiple eshops per single company', 'woocommerce-superfaktura' ),
						),
					),
					array(
						'title'   => __( 'Secret Key', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_sync_secret_key',
						'desc'    => '<button id="createSecretKey" type="button" class="button">' . __( 'Regenerate', 'woocommerce-superfaktura' ) . '</button>',
						'class'   => 'input-text regular-input',
						'type'    => 'text',
						'default' => $this->get_or_create_default_secret_key(),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_sf_invoice_title9',
					),
				);

				$settings = apply_filters( 'superfaktura_integration_settings', $settings );
				break;

			case 'payment':
				$settings[] = array(
					'title' => __( 'Payment Methods', 'woocommerce-superfaktura' ),
					'type'  => 'title',
					'desc'  => __( 'Map Woocommerce payment methods to ones in SuperFaktura', 'woocommerce-superfaktura' ),
					'id'    => 'woocommerce_wi_invoice_title6',
				);

				$gateway_mapping = apply_filters( 'sf_gateway_mapping', $this->get_sf_payment_methods() );

				foreach ( $gateways as $gateway ) {
					$settings[] = array(
						'title'   => $gateway->title,
						'id'      => 'woocommerce_sf_gateway_' . $gateway->id,
						'default' => 0,
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => $gateway_mapping,
					);
				}

				$settings[] = array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_wi_invoice_title6',
				);

				$settings[] = array(
					'title' => __( 'Cash Registers', 'woocommerce-superfaktura' ),
					'type'  => 'title',
					'desc'  => __( 'Map Woocommerce payment methods to cash registers in SuperFaktura', 'woocommerce-superfaktura' ),
					'id'    => 'woocommerce_wi_invoice_title7',
				);

				foreach ( $gateways as $gateway ) {
					$settings[] = array(
						'title' => $gateway->title,
						'id'    => 'woocommerce_sf_cash_register_' . $gateway->id,
						'desc'  => 'Cash register ID',
						'type'  => 'text',
					);
				}

				$settings[] = array(
					'type' => 'sectionend',
					'id'   => 'woocommerce_wi_invoice_title7',
				);

				$settings = apply_filters( 'superfaktura_payment_settings', $settings );
				break;

			case 'shipping':
				$shipping_mapping = apply_filters( 'sf_shipping_mapping', $this->get_sf_shipping_methods() );

				if ( class_exists( 'WC_Shipping_Zones' ) ) {
					$zones = WC_Shipping_Zones::get_zones();

					// "Rest of the world" zone.
					$rest                                = new WC_Shipping_Zone( 0 );
					$zones[0]                            = $rest->get_data();
					$zones[0]['formatted_zone_location'] = $rest->get_formatted_location();
					$zones[0]['shipping_methods']        = $rest->get_shipping_methods();

					foreach ( $zones as $id => $zone ) {
						$settings[] = array(
							'title' => __( 'Shipping Methods', 'woocommerce-superfaktura' ) . ': ' . $zone['formatted_zone_location'],
							'type'  => 'title',
							'id'    => 'woocommerce_wi_invoice_title_zone_' . $id,
						);

						foreach ( $zone['shipping_methods'] as $method ) {
							if ( 'no' === $method->enabled ) {
								continue;
							}
							$legacy     = get_option( 'woocommerce_sf_shipping_' . $method->id );
							$settings[] = array(
								'title'   => $method->title,
								'id'      => 'woocommerce_sf_shipping_' . $method->id . ':' . $method->instance_id,
								'default' => empty( $legacy ) ? 0 : $legacy,
								'type'    => 'select',
								'class'   => 'wc-enhanced-select',
								'options' => $shipping_mapping,
							);
						}

						$settings[] = array(
							'type' => 'sectionend',
							'id'   => 'woocommerce_wi_invoice_title_zone_' . $id,
						);
					}
				} else {
					$wc_shipping = WC()->shipping();
					$shippings   = $wc_shipping->get_shipping_methods();

					if ( $shippings ) {
						$settings[] = array(
							'title' => __( 'Shipping Methods', 'woocommerce-superfaktura' ),
							'type'  => 'title',
							'desc'  => 'Map Woocommerce shipping methods to ones in SuperFaktúra.sk',
							'id'    => 'woocommerce_wi_invoice_title7',
						);

						foreach ( $shippings as $shipping ) {
							if ( 'no' === $shipping->enabled ) {
								continue;
							}

							$settings[] = array(
								'title'   => $shipping->title,
								'id'      => 'woocommerce_sf_shipping_' . $shipping->id,
								'default' => 0,
								'type'    => 'select',
								'class'   => 'wc-enhanced-select',
								'options' => $shipping_mapping,
							);
						}

						$settings[] = array(
							'type' => 'sectionend',
							'id'   => 'woocommerce_wi_invoice_title7',
						);
					}
				}

				$settings = apply_filters( 'superfaktura_shipping_settings', $settings );
				break;

			case 'accounting':
				$item_type_options = array(
					'0'       => __( 'Don\'t use', 'woocommerce-superfaktura' ),
					'item'    => __( 'Item', 'woocommerce-superfaktura' ),
					'service' => __( 'Service', 'woocommerce-superfaktura' ),
				);

				$settings = array(
					array(
						'title' => __( 'Product', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title21',
					),
					array(
						'title'   => __( 'Item Type', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_item_type_product',
						'default' => 0,
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => $item_type_options,
					),
					array(
						'title' => __( 'Analytics Account', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_analytics_account_product',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Synthetic Account', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_synthetic_account_product',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Preconfidence', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_preconfidence_product',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_sf_invoice_title21',
					),

					array(
						'title' => __( 'Fees', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title22',
					),
					array(
						'title'   => __( 'Item Type', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_item_type_fees',
						'default' => 0,
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => $item_type_options,
					),
					array(
						'title' => __( 'Analytics Account', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_analytics_account_fees',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Synthetic Account', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_synthetic_account_fees',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Preconfidence', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_preconfidence_fees',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_sf_invoice_title22',
					),

					array(
						'title' => __( 'Shipping', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title23',
					),
					array(
						'title'   => __( 'Item Type', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_item_type_shipping',
						'default' => 0,
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => $item_type_options,
					),
					array(
						'title' => __( 'Analytics Account', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_analytics_account_shipping',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Synthetic Account', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_synthetic_account_shipping',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Preconfidence', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_preconfidence_shipping',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_sf_invoice_title23',
					),

					array(
						'title' => __( 'Discount', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'woocommerce_sf_invoice_title24',
					),
					array(
						'title'   => __( 'Item Type', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_item_type_discount',
						'default' => 0,
						'type'    => 'select',
						'class'   => 'wc-enhanced-select',
						'options' => $item_type_options,
					),
					array(
						'title' => __( 'Analytics Account', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_analytics_account_discount',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Synthetic Account', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_synthetic_account_discount',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Preconfidence', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_preconfidence_discount',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_sf_invoice_title24',
					),
				);

				$settings = apply_filters( 'superfaktura_accounting_settings', $settings );
				break;

			case 'api_log':
				global $wpdb;
				$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wc_sf_log ORDER BY time DESC LIMIT 200", ARRAY_A );

				$table = '
					<table class="wc-sf-api-log">
						<thead>
							<tr>
								<th>Order ID</th>
								<th>Document Type</th>
								<th>Request Type</th>
								<th>Response Status</th>
								<th>Response Message</th>
								<th>Time</th>
							</tr>
						</thead>
						<tbody>
				';

				if ( $results ) {
					foreach ( $results as $index => $row ) {
						$table .= '
							<tr class="' . ( ( 0 === $index % 2 ) ? 'odd' : '' ) . ' ' . ( ( $row['response_status'] ) ? ' error' : '' ) . '">
								<td>' . $row['order_id'] . '</td>
								<td>' . $row['document_type'] . '</td>
								<td>' . $row['request_type'] . '</td>
								<td>' . $row['response_status'] . '</td>
								<td>' . $row['response_message'] . '</td>
								<td>' . $row['time'] . '</td>
							</tr>
						';
					}
				}

				$table .= '
						</tbody>
					</table>
				';

				$settings = array(
					array(
						'title' => __( 'API log', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => $table,
						'id'    => 'woocommerce_sf_invoice_title98',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_sf_invoice_title98',
					),
				);

				$settings = apply_filters( 'superfaktura_api_log_settings', $settings );
				break;

			case 'help':
				$settings = array(
					array(
						'title' => __( 'Help', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => '
							<p>' . __( 'For more information about the plugin and its settings check the articles on SuperFaktura blog.', 'woocommerce-superfaktura' ) . '</p>

							<h3>SuperFaktura.sk</strong></h3>
							<ul style="padding-left: 1em; list-style-type: disc;">
								<li><a href="https://www.superfaktura.sk/blog/superfaktura-a-woocommerce-diel-1-instalacia-a-autorizacia/">Diel 1. – Inštalácia a autorizácia</a></li>
								<li><a href="https://www.superfaktura.sk/blog/superfaktura-a-woocommerce-diel-2-vytvaranie-faktur/">Diel 2. – Vytváranie faktúr</a></li>
								<li><a href="https://www.superfaktura.sk/blog/superfaktura-a-woocommerce-diel-3-cislovanie-dokladov/">Diel 3. – Číslovanie dokladov</a></li>
								<li><a href="https://www.superfaktura.sk/blog/superfaktura-a-woocommerce-diel-4-pokrocile-nastavenia/">Diel 4. – Pokročilé nastavenia</a></li>
								<li><a href="https://www.superfaktura.sk/blog/superfaktura-a-woocommerce-diel-5-platby-doprava-pokladne/">Diel 5. – Platby, doprava, pokladne</a></li>
								<li><a href="https://www.superfaktura.sk/blog/superfaktura-a-woocommerce-faq/">FAQ</a></li>
							</ul>

							<h3>SuperFaktura.cz</h3>
							<ul style="padding-left: 1em; list-style-type: disc;">
								<li><a href="https://www.superfaktura.cz/blog/superfaktura-a-woocommerce-dil-1-instalace-a-autorizace/">Díl 1. – Instalace a autorizace</a></li>
								<li><a href="https://www.superfaktura.cz/blog/superfaktura-a-woocommerce-dil-2-vytvareni-faktur/">Díl 2. – Vytváření faktur</a></li>
								<li><a href="https://www.superfaktura.cz/blog/superfaktura-a-woocommerce-dil-3-cislovani-dokladu/">Díl 3. – Číslování dokladů</a></li>
								<li><a href="https://www.superfaktura.cz/blog/superfaktura-a-woocommerce-dil-4-pokrocila-nastaveni/">Díl 4. – Pokročilá nastavení</a></li>
								<li><a href="https://www.superfaktura.cz/blog/superfaktura-a-woocommerce-dil-5-platby-doprava-pokladny-a-eet/">Díl 5. – Platby, doprava, pokladny a EET</a></li>
								<li><a href="https://www.superfaktura.cz/blog/superfaktura-a-woocommerce-faq/">FAQ</a></li>
							</ul>

							<hr>
							<p>' . __( 'Do you have a technical issue with the plugin? Contact us at <a href="mailto:superfaktura@2day.sk">superfaktura@2day.sk</a>', 'woocommerce-superfaktura' ) . '</p>
						',
						'id'    => 'woocommerce_sf_invoice_title99',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_sf_invoice_title99',
					),
				);

				$settings = apply_filters( 'superfaktura_help_settings', $settings );
				break;

			case '':
			default:
				$settings = array(
					array(
						'title' => __( 'Authorization', 'woocommerce-superfaktura' ),
						'type'  => 'title',
						'desc'  => __( 'You can find your API access credentials in your SuperFaktura account at <a href="https://moja.superfaktura.sk/api_access">Tools &gt; API</a>', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_invoice_title1',
					),
					array(
						'title'   => __( 'Version', 'woocommerce-superfaktura' ),
						'id'      => 'woocommerce_sf_lang',
						'type'    => 'radio',
						'desc'    => '',
						'default' => 'sk',
						'options' => array(
							'sk' => 'SuperFaktura.sk',
							'cz' => 'SuperFaktura.cz',
							'at' => 'SuperFaktura.at',
						),
					),
					array(
						'title'   => __( 'Sandbox', 'woocommerce-superfaktura' ),
						'desc'    => __( 'Use sandbox', 'woocommerce-superfaktura' ) . '<br><small>' . __( 'Note: To use sandbox create a test account at sandbox.superfaktura.sk or sandbox.superfaktura.cz', 'woocommerce-superfaktura' ) . '</small>',
						'id'      => 'woocommerce_sf_sandbox',
						'default' => 'no',
						'type'    => 'checkbox',
					),
					array(
						'title' => __( 'API Email', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_email',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'API Key', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_apikey',
						'desc'  => '',
						'class' => 'input-text regular-input',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Company ID', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_company_id',
						'desc'  => '
							<a class="button wc-sf-api-test" href="">' . __( 'Test API connection', 'woocommerce-superfaktura' ) . '</a>
							<span class="wc-sf-api-test-loading"><img src="' . plugins_url( '../images/rolling.gif', __FILE__ ) . '" width="25" height="25" alt=""></span>
							<span class="wc-sf-api-test-ok"><img src="' . plugins_url( '../images/ok.png', __FILE__ ) . '" width="32" height="32" alt=""></span>
							<span class="wc-sf-api-test-fail"><img src="' . plugins_url( '../images/fail.png', __FILE__ ) . '" width="32" height="32" alt=""></span>
							<span class="wc-sf-api-test-fail-message" style="color: #e15b64;"></span>
						',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Logo ID', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_logo_id',
						'desc'  => '',
						'type'  => 'text',
					),
					array(
						'title' => __( 'Bank Account ID', 'woocommerce-superfaktura' ),
						'id'    => 'woocommerce_sf_bank_account_id',
						'desc'  => '',
						'type'  => 'text',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'woocommerce_wi_invoice_title1',
					),
				);

				$settings = apply_filters( 'superfaktura_authorization_settings', $settings );
				break;
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );

	}

	/**
	 * Get order statuses array
	 */
	private function get_order_statuses() {
		if ( function_exists( 'wc_order_status_manager_get_order_status_posts' ) ) {
			$wc_order_statuses = array_reduce(
				wc_order_status_manager_get_order_status_posts(),
				function( $result, $item ) {
					$result[ $item->post_name ] = $item->post_title;
					return $result;
				},
				array()
			);

			return $wc_order_statuses;
		}

		if ( function_exists( 'wc_get_order_statuses' ) ) {
			$wc_get_order_statuses = wc_get_order_statuses();

			return $this->alter_wc_statuses( $wc_get_order_statuses );
		}

		$order_status_terms = get_terms( 'shop_order_status', 'hide_empty=0' );

		$shop_order_statuses = array();
		if ( ! is_wp_error( $order_status_terms ) ) {
			foreach ( $order_status_terms as $term ) {
				$shop_order_statuses[ $term->slug ] = $term->name;
			}
		}

		return $shop_order_statuses;
	}

	/**
	 * Modify order statuses array
	 *
	 * @param array $array Array or order statuses.
	 */
	private function alter_wc_statuses( $array ) {
		$new_array = array();
		foreach ( $array as $key => $value ) {
			$new_array[ substr( $key, 3 ) ] = $value;
		}

		return $new_array;
	}



	/**
	 * Output the settings.
	 */
	public function output() {
		if ( 'yes' === get_option( 'woocommerce_sf_sandbox', 'no' ) ) {
			echo wp_kses( '<div class="sf-sandbox-notice">' . __( 'Plugin SuperFaktura WooCommerce is in sandbox mode.', 'woocommerce-superfaktura' ) . '</div>', $this->allowed_tags );
		}

		global $current_section;
		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );

		if ( 'invoice' === $current_section ) {
			$this->get_country_specific_settings();
		}

		?>
		<script>
		function wc_sf_generate_secret_key() {
			<?php $nonce = wp_create_nonce( 'wc_sf' ); ?>
			jQuery.ajax({
				type: "post",
				url: "admin-ajax.php",
				data: {
					action: 'wc_sf_generate_secret_key',
					_ajax_nonce: '<?php echo esc_js( $nonce ); ?>'
				},
				success: function(response) {
					if (response) {
						document.getElementById('woocommerce_sf_sync_secret_key').value = response;
					}
				}
			});
		}

		var createSecretKey = document.getElementById('createSecretKey');
		if (createSecretKey) {
			createSecretKey.addEventListener('click', wc_sf_generate_secret_key);
		}
		</script>
		<?php
	}



	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;
		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );

		if ( isset( $_POST['woocommerce_sf_country_settings'] ) ) {
			update_option( 'woocommerce_sf_country_settings', sanitize_text_field( wp_unslash( $_POST['woocommerce_sf_country_settings'] ) ) );
		}
	}



	/**
	 * Country specific settings.
	 */
	private function get_country_specific_settings() {
		$countries       = new WC_Countries();
		$country_options = array_merge( [ '*' => __('ALL', 'woocommerce-superfaktura') ], $countries->__get( 'countries' ) );
		$default_country = get_option( 'woocommerce_default_country' );

		$country_settings    = array( 'template' );
		$country_settings_db = json_decode( get_option( 'woocommerce_sf_country_settings', false ), true );
		if ( is_array( $country_settings_db ) ) {
			$country_settings = array_merge( array( 'template' ), $country_settings_db );
		}
		?>

		<h2><?php esc_html_e( 'Countries', 'woocommerce-superfaktura' ); ?></h2>
		<div id="woocommerce_sf_invoice_title9-description"><p><?php esc_html_e( 'Override invoice settings for specific countries based on customer billing address.', 'woocommerce-superfaktura' ); ?></p></div>

		<input type="hidden" name="woocommerce_sf_country_settings">

		<table class="wc_input_table widefat">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Country', 'woocommerce-superfaktura' ); ?></th>
					<th><?php esc_html_e( 'VAT #', 'woocommerce-superfaktura' ); ?></th>
					<th><?php esc_html_e( 'Use VAT # only for final consumer', 'woocommerce-superfaktura' ); ?></th>
					<th><?php esc_html_e( 'TAX ID #', 'woocommerce-superfaktura' ); ?></th>
					<th><?php esc_html_e( 'Bank Account ID', 'woocommerce-superfaktura' ); ?></th>
					<th><?php esc_html_e( 'Proforma Invoice Sequence ID', 'woocommerce-superfaktura' ); ?></th>
					<th><?php esc_html_e( 'Invoice Sequence ID', 'woocommerce-superfaktura' ); ?></th>
					<th><?php esc_html_e( 'Credit Note Sequence ID', 'woocommerce-superfaktura' ); ?></th>
					<th></th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<th colspan="9">
						<a href="#" class="button sf-add-country-settings"><?php esc_html_e( 'Add country', 'woocommerce-superfaktura' ); ?></a>
					</th>
				</tr>
			</tfoot>

			<tbody id="sf-countries">

				<?php foreach ( $country_settings as $country ) : ?>

					<tr <?php echo ( 'template' === $country ) ? 'data-name="template" style="display: none;"' : ''; ?>>
						<td style="padding: 5px 10px;">
							<select name="_country_country" class="_wc-enhanced-select">
								<?php foreach ( $country_options as $value => $text ) : ?>
									<option value="<?php echo esc_attr( $value ); ?>" <?php echo ( ( 'template' === $country && $default_country === $value ) || ( 'template' !== $country && $country['country'] === $value ) ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $text ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>

						<td><input type="text" name="_country_vat" placeholder="<?php esc_html_e( 'VAT #', 'woocommerce-superfaktura' ); ?>" value="<?php echo ( 'template' !== $country ) ? esc_attr( $country['vat_id'] ) : ''; ?>" style="padding: 12px 10px !important;"></td>
						<td style="text-align: center; vertical-align: middle;"><input type="checkbox" name="_country_vat_id_only_final_consumer" <?php echo ( 'template' !== $country && ! empty( $country['vat_id_only_final_consumer'] ) ) ? 'checked="checked"' : ''; ?>></td>
						<td><input type="text" name="_country_tax" placeholder="<?php esc_html_e( 'TAX ID #', 'woocommerce-superfaktura' ); ?>" value="<?php echo ( 'template' !== $country ) ? esc_attr( $country['tax_id'] ) : ''; ?>" style="padding: 12px 10px !important;"></td>
						<td><input type="text" name="_country_bank_account_id" placeholder="<?php esc_html_e( 'Bank Account ID', 'woocommerce-superfaktura' ); ?>" value="<?php echo ( 'template' !== $country ) ? esc_attr( $country['bank_account_id'] ) : ''; ?>" style="padding: 12px 10px !important;"></td>
						<td><input type="text" name="_country_proforma_invoice_sequence_id" placeholder="<?php esc_html_e( 'Proforma Invoice Sequence ID', 'woocommerce-superfaktura' ); ?>" value="<?php echo ( 'template' !== $country ) ? esc_attr( $country['proforma_sequence_id'] ) : ''; ?>" style="padding: 12px 10px !important;"></td>
						<td><input type="text" name="_country_invoice_sequence_id" placeholder="<?php esc_html_e( 'Invoice Sequence ID', 'woocommerce-superfaktura' ); ?>" value="<?php echo ( 'template' !== $country ) ? esc_attr( $country['invoice_sequence_id'] ) : ''; ?>" style="padding: 12px 10px !important;"></td>
						<td><input type="text" name="_country_cancel_sequence_id" placeholder="<?php esc_html_e( 'Credit Note Sequence ID', 'woocommerce-superfaktura' ); ?>" value="<?php echo ( 'template' !== $country ) ? esc_attr( $country['cancel_sequence_id'] ) : ''; ?>" style="padding: 12px 10px !important;"></td>
						<td><a href="#" class="sf-delete-country-settings" style="display: block; padding: 12px 10px; white-space: nowrap;"><span class="dashicons dashicons-dismiss"></span><?php esc_html_e( 'Delete', 'woocommerce-superfaktura' ); ?></a></td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

		<?php
	}
}
