<?php
/**
 * @package     blazing-shipment-tracking/API
 * @category    API
 * @since       1.0
 *
 * Handles BST-Tracking Email
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( ! class_exists( 'BST_Tracking_Order_Email' ) ) :

/**
 * A custom tracking Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class BST_Tracking_Order_Email extends WC_Email {


	/**
	 * Set email defaults
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// set ID, this simply needs to be a unique name
		$this->id = 'BST_Tracking_Order_Email';
		$this->customer_email = true;

		// this is the title in WooCommerce Email settings
		$this->title          = __( 'Order Shipment Tracking', 'bs_ship_track' );
		// // this is the description in WooCommerce email settings
		$this->description    = __( 'Order Tracking Number Notification emails are sent when the tracking number of the order is entered or changed.', 'bs_ship_track' );

		// // these are the default heading and subject lines that can be overridden using the settings
		$this->subject        = __( 'Tracking Number of the Order {site_title} order from {order_date}', 'bs_ship_track');
		$this->heading        = __( 'Tracking Number of the Order', 'bs_ship_track');

		// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = 'emails/bst-shipment-tracking.php';
		$this->template_plain = 'emails/bst-shipment-tracking-plain.php';

		$this->template_base  = untrailingslashit( dirname(plugin_dir_path( __FILE__ ) ) ) . '/templates/';
		add_action( 'woocommerce_tracking_number_notification', array( $this, 'trigger' ), 10 , 3 );

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();
	}

	public function trigger( $order_id) {
		if ( ! $this->is_enabled() || ! $order_id ) {
			return false;
		}
		// setup order object
		$this->object = wc_get_order( $order_id );

		$user = $this->object->post_author;
		$user_email = get_the_author_meta('user_email',$user);
		$this->recipient               = $this->object->billing_email;
		if ( ! $this->get_recipient() ) {
			return false;
		}
		$value = get_post_meta($post->ID, '_bst_tracking_number', true);
		$shipping_method = get_post_meta($post->ID, '_bst_tracking_provider_name', true);
		$this->customer_note           = $value . ':' . $shipping_method;
		$this->find['order-date']      = '{order_date}';
		$this->find['order-number']    = '{order_number}';

		$this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( $this->object->order_date ) );
		$this->replace['order-number'] = $this->object->get_order_number();
		// $user_firstname = get_the_author_meta('user_firstname',$user); // retrieve firstname
		// $user_nickname = get_the_author_meta('nickname',$user); // retrieve user nickname

		// send the email!
		$this->send( $this->recipient, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		return true;
	}

	/**
	 * Determine if the email should actually be sent and setup email merge variables
	 *
	 * @since 0.1
	 * @param int $order_id
	 */
	public function check_if_should_send_email( $value, $post_id, $field ) {
		// bail if no order ID is present
		if ( ! $post_id )
			return $value;
		$t = get_post_type($post_id);
		var_dump($t);
		if ( $t != 'shop_order' )
			return $value;
		// bail if tracking number is not entered or did not change
		$currentValue = get_field("tracking_number", $post_id);
		var_dump($currentValue);
		if ( $currentValue === false || $currentValue === '')
			return $value;
		if ( $currentValue === $value)
			return $value;
		trigger($post_id);
		return $value;
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html( $this->template_html, array(
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
				'customer_note' => $this->customer_note,
				'sent_to_admin' => false,
				'plain_text'    => false,
				'email'			=> $this
			),
			'',
			$this->template_base
		);
	}

	/**
	 * Get content plain.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
				'customer_note' => $this->customer_note,
				'sent_to_admin' => false,
				'plain_text'    => true,
				'email'			=> $this
			),
			'',
			$this->template_base
		);
	}


	/**
	 * Initialize Settings Form Fields
	 *
	 * @since 2.0
	 */
	public function init_form_fields() {

		$this->form_fields = array(
			'enabled'    => array(
				'title'   => 'Enable/Disable',
				'type'    => 'checkbox',
				'label'   => 'Enable this email notification',
				'default' => 'yes'
			),
			'subject'    => array(
				'title'       => 'Subject',
				'type'        => 'text',
				'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
				'placeholder' => '',
				'default'     => ''
			),
			'heading'    => array(
				'title'       => 'Email Heading',
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
				'placeholder' => '',
				'default'     => ''
			),
			'email_type' => array(
				'title'       => 'Email type',
				'type'        => 'select',
				'description' => 'Choose which format of email to send.',
				'default'     => 'html',
				'class'       => 'email_type',
				'options'     => array(
					'plain'	    => __( 'Plain text', 'woocommerce' ),
					'html' 	    => __( 'HTML', 'woocommerce' ),
					'multipart' => __( 'Multipart', 'woocommerce' ),
				)
			)
		);
	}


} // end BST_Tracking_Order_Email class
endif;

//return new BST_Tracking_Order_Email();
