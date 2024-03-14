<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Email_Customer_Quote' ) ) :

/**
 * Customer Quote.
 *
 * An email sent to the customer via admin.
 *
 * @class       WC_Email_Customer_Quote
 * @version     2.3.0
 * @package     WooCommerce/Classes/Emails
 * @author      WooThemes
 * @extends     WC_Email
 */
class WC_Email_Customer_Quote extends WC_Email {

	/**
	 * Strings to find in subjects/headings.
	 *
	 * @var array
	 */
	public $find;

	/**
	 * Strings to replace in subjects/headings.
	 *
	 * @var array
	 */
	public $replace;

	/**
	 * Constructor.
	 */
	function __construct() {

		$this->id             = 'customer_quote';
		$this->title          = sprintf( __( 'Customer %s', 'woo-invoices' ), sliced_get_quote_label() );
		$this->description    = sprintf( __( 'Customer %s emails can be sent to customers containing their order information.', 'woo-invoices' ), sliced_get_quote_label() );

		$this->template_html  = 'emails/customer-invoice.php';
		$this->template_plain = 'emails/plain/customer-invoice.php';

		$this->subject        = sprintf( __( '%s for order {order_number} from {order_date}', 'woo-invoices'), sliced_get_quote_label() );
		$this->heading        = sprintf( __( '%s for order {order_number}', 'woo-invoices'), sliced_get_quote_label() );

		// Call parent constructor
		parent::__construct();

		$wc_si = get_option( 'woocommerce_sliced-invoices_settings' );
        if ( isset( $wc_si['auto_quote_email'] ) && $wc_si['auto_quote_email'] === 'yes' ) {
        	// Triggers for this email
			add_action( 'woocommerce_order_status_pending_to_quote_notification', array( $this, 'trigger' ) );
		}

		$this->customer_email = true;
		$this->manual         = true;
	}

	/**
	 * Trigger.
	 *
	 * @param int|WC_Order $order
	 */
	function trigger( $order ) {

		if ( ! is_object( $order ) ) {
			$order = wc_get_order( absint( $order ) );
		}

		if ( $order ) {
			$this->object                  = $order;
			$this->recipient               = $this->object->get_billing_email();

			$this->find['order-date']      = '{order_date}';
			$this->find['order-number']    = '{order_number}';

			$this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( $this->object->get_date_created() ) );
			$this->replace['order-number'] = $this->object->get_order_number();
		}

		if ( ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

	}

	/**
	 * Get email subject.
	 *
	 * @access public
	 * @return string
	 */
	function get_subject() {
		return apply_filters( 'woocommerce_email_subject_customer_quote', $this->format_string( $this->subject ), $this->object );
	}

	/**
	 * Get email heading.
	 *
	 * @access public
	 * @return string
	 */
	function get_heading() {
		return apply_filters( 'woocommerce_email_heading_customer_quote', $this->format_string( $this->heading ), $this->object );
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		return wc_get_template_html( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false,
			'email'			=> $this
		) );
	}

	/**
	 * Get content plain.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true,
			'email'			=> $this
		) );
	}

	/**
	 * Initialise settings form fields.
	 */
	function init_form_fields() {
		$this->form_fields = array(
			'subject' => array(
				'title'         => __( 'Email Subject', 'woo-invoices' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Defaults to <code>%s</code>', 'woo-invoices' ), $this->subject ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true
			),
			'heading' => array(
				'title'         => __( 'Email Heading', 'woo-invoices' ),
				'type'          => 'text',
				'description'   => sprintf( __( 'Defaults to <code>%s</code>', 'woo-invoices' ), $this->heading ),
				'placeholder'   => '',
				'default'       => '',
				'desc_tip'      => true
			),
			'email_type' => array(
				'title'         => __( 'Email Type', 'woo-invoices' ),
				'type'          => 'select',
				'description'   => __( 'Choose which format of email to send.', 'woo-invoices' ),
				'default'       => 'html',
				'class'         => 'email_type wc-enhanced-select',
				'options'       => $this->get_email_type_options(),
				'desc_tip'      => true
			)
		);
	}

	/**
	 * Prepare and send the customer invoice email on demand.
	 */
	public function customer_quote( $order ) {
		$email = $this->emails['WC_Email_Customer_Quote'];
		$email->trigger( $order );
	}
}

endif;

return new WC_Email_Customer_Quote();
