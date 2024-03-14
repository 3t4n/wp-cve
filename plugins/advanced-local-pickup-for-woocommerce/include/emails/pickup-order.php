<?php
/**
 * Class WC_Email_Customer_Pickup_Order file.
 *
 * @package WooCommerce\Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WC_Email_Customer_Pickup_Order', false ) ) :

	/**
	 * Customer Completed Order Email.
	 *
	 * Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.
	 *
	 * @class       WC_Email_Customer_Pickup_Order
	 * @version     2.0.0
	 * @package     WooCommerce/Classes/Emails
	 * @extends     WC_Email
	 */
	class WC_Email_Customer_Pickup_Order extends WC_Email {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id             = 'customer_pickup_order';
			$this->customer_email = true;
			$this->title          = esc_html( 'Picked up order', 'advanced-local-pickup-for-woocommerce' );
			$this->description    = esc_html( 'Picked up Order emails are sent to customers when their orders are marked picked up.', 'advanced-local-pickup-for-woocommerce' );
			$this->template_html  = 'emails/pickup-order.php';
			$this->template_plain = 'emails/plain/pickup-order.php';
			$this->placeholders   = array(
				'{customer_first_name}'   => '',
				'{customer_last_name}'   => '',
				'{order_date}'   => '',
				'{order_number}' => '',
				'{customer_username}' => '',
			);
			$this->template_base = WC_LOCAL_PICKUP_TEMPLATE_PATH;
			// Triggers for this email.		
			//add_action( 'woocommerce_order_status_pickup', array( $this, 'trigger' ), 10, 2 );
			// Call parent constructor.
			parent::__construct();
		}

		/**
		 * Trigger the sending of this email.
		 *
		 * @param int            $order_id The order ID.
		 * @param WC_Order|false $order Order object.
		 */
		public function trigger( $order_id, $order = false ) {
			$this->setup_locale();

			if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
				$order = wc_get_order( $order_id );
			}

			if ( is_a( $order, 'WC_Order' ) ) {
				$this->object                         = $order;
				$this->recipient                      = $this->object->get_billing_email();
				$this->placeholders['{customer_first_name}']   = $this->object->get_billing_first_name();
				$this->placeholders['{customer_last_name}']   = $this->object->get_billing_last_name();
				$this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
				$this->placeholders['{order_number}'] = $this->object->get_order_number();
				$customer = new WC_Customer( $this->object->get_customer_id() );
				$this->placeholders['{customer_username}'] = $customer->get_username();
			}
			
			$customer = new WC_Customer( $this->object->get_customer_id() );
			$get_content = str_replace(array('{site_title}', '{customer_email}', '{customer_first_name}', '{customer_last_name}', '{customer_username}', '{order_number}' ), array( get_bloginfo( 'name' ), $order->get_billing_email(), $order->get_billing_first_name(), $order->get_billing_last_name(), $customer->get_username(), $order->get_order_number()), $this->get_content());
			
			if ( $this->is_enabled() && $this->get_recipient() ) {
				$this->send( $this->get_recipient(), $this->get_subject(), $get_content, $this->get_headers(), $this->get_attachments() );
			}

			$this->restore_locale();
		}

		/**
		 * Get email subject.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_subject() {
			return wp_kses_post( 'Your order from {site_title} was picked up', 'advanced-local-pickup-for-woocommerce' );
		}

		/**
		 * Get email heading.
		 *
		 * @since  3.1.0
		 * @return string
		 */
		public function get_default_heading() {
			return wp_kses_post( "You've Got it!", 'advanced-local-pickup-for-woocommerce' );
		}

		/**
		 * Get content html.
		 *
		 * @return string
		 */
		public function get_content_html() {
			$template = $this->get_template( 'template_html' );			
			$local_file    = $this->get_theme_template_file( $template );
			if ( file_exists( $local_file ) && is_writable( $local_file )) {						
				return wc_get_template_html(
					$this->template_html,
					array(
						'order'         => $this->object,
						'email_heading' => $this->get_heading(),
						'additional_content' => $this->get_additional_content(),
						'sent_to_admin' => false,
						'plain_text'    => false,
						'email'         => $this,
					)
				);
			} else {	
				return wc_get_template_html(
					'emails/pickup-order.php',
					array(
						'order'              => $this->object,
						'email_heading'      => $this->get_heading(),
						'additional_content' => $this->get_additional_content(),
						'sent_to_admin'      => false,
						'plain_text'         => false,
						'email'              => $this,
					),
					'woocommerce-local-pickup/', 
					wc_local_pickup()->get_plugin_path() . '/templates/'
				);
			}
		}

		/**
		 * Get content plain.
		 *
		 * @return string
		 */
		public function get_content_plain() {
			$template = $this->get_template( 'template_html' );			
			$local_file    = $this->get_theme_template_file( $template );
			if ( file_exists( $local_file ) && is_writable( $local_file )) {						
				return wc_get_template_html(
					$this->template_html,
					array(
						'order'         => $this->object,
						'email_heading' => $this->get_heading(),
						'additional_content' => $this->get_additional_content(),
						'sent_to_admin' => false,
						'plain_text'    => false,
						'email'         => $this,
					)
				);
			} else {	
				return wc_get_template_html(
					'emails/pickup-order.php',
					array(
						'order'              => $this->object,
						'email_heading'      => $this->get_heading(),
						'additional_content' => $this->get_additional_content(),
						'sent_to_admin'      => false,
						'plain_text'         => false,
						'email'              => $this,
					),
					'woocommerce-local-pickup/', 
					wc_local_pickup()->get_plugin_path() . '/templates/'
				);
			}
		}

		/**
		 * Default content to show below main email content.
		 *
		 * @since 3.7.0
		 * @return string
		 */
		public function get_default_additional_content() {
			return esc_html( 'Hi {customer_first_name}. Thank you for picking up your {site_title} order #{order_number}. We hope you enjoyed your shopping experience.', 'advanced-local-pickup-for-woocommerce' );
		}
	}

endif;

return new WC_Email_Customer_Pickup_Order();
