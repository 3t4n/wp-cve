<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH\PreOrder\Includes\Emails
 * @author YITH <plugins@yithemes.com>
 */

if ( ! defined( 'YITH_WCPO_VERSION' ) ) {
	exit( 'Direct access forbidden.' );
}

if ( ! class_exists( 'YITH_Pre_Order_New_Pre_Order_Email' ) ) {
	/**
	 * Class YITH_Pre_Order_New_Pre_Order_Email
	 */
	class YITH_Pre_Order_New_Pre_Order_Email extends WC_Email {

		/**
		 * Email content.
		 *
		 * @var string $email_body
		 */
		public $email_body;

		/**
		 * Email additional data.
		 *
		 * @var array $data
		 */
		public $data;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id = 'ywpo_new_pre_order_email';

			$this->title       = __( 'YITH Pre-Order: New pre-order', 'yith-pre-order-for-woocommerce' );
			$this->description = sprintf(
				// translators: %s: placeholders.
				__( 'New pre-order emails are sent to chosen recipient(s) when a new pre-order is received. Available placeholders: %s', 'yith-pre-order-for-woocommerce' ),
				'<code>{customer_name}, {product_title}, {product_url}, {product_link}, {order_number}, {order_link}, {release_date}, {site_title}.</code>'
			);

			$this->heading    = __( 'New pre-order', 'yith-pre-order-for-woocommerce' );
			$this->subject    = __( 'New pre-order received', 'yith-pre-order-for-woocommerce' );
			$this->email_body = __(
				"Hi admin,\n\nWe would like to inform you that you received a new pre-order from customer {customer_name}.
{order_link}\n{product_table}\n\nRegards,\n{site_title}",
				'yith-pre-order-for-woocommerce'
			);

			$this->template_html = 'emails/ywpo-email-admin-new-pre-order.php';

			add_action( 'ywpo_new_pre_order_email', array( $this, 'trigger' ), 10, 3 );

			parent::__construct();
			$this->email_type = 'html';
			$this->recipient  = $this->get_option( 'recipient', get_option( 'admin_email' ) );
		}

		/**
		 * Triggers the email sending process.
		 *
		 * @param WC_Order   $order The WC_Order object.
		 * @param WC_Product $product The WC_Product object.
		 * @param string|int $item_id The Order Item ID.
		 */
		public function trigger( $order, $product, $item_id ) {
			if ( ! $order || ! $product || 'cancelled' === $order->get_status() ) {
				return;
			}

			// Avoid errors with third-party plugins that use $this->object for the WC_Order instance.
			$this->object = $order;

			$this->data = apply_filters(
				'ywpo_new_pre_order_email_object',
				array(
					'order'   => $order,
					'product' => $product,
					'item_id' => $item_id,
				),
				$order,
				$product,
				$item_id
			);

			$product_url = get_admin_url( null, 'post.php?post=' . $product->get_id() . '&action=edit' );

			$product_link = apply_filters(
				'ywpo_new_pre_order_email_product_link',
				'<a href="' . $product_url . '">' . $product->get_title() . '</a>',
				$order,
				$product,
				$item_id
			);

			$order_link = apply_filters(
				'ywpo_new_pre_order_email_order_link',
				'<a href="' . $order->get_edit_order_url() . '">#' . $order->get_id() . '</a>',
				$order,
				$product,
				$item_id
			);

			$release_date        = $order->get_item( $item_id )->get_meta( '_ywpo_item_for_sale_date' );
			$no_release_date_msg = apply_filters( 'ywpo_new_pre_order_email_no_release_date', __( 'at a future date', 'yith-pre-order-for-woocommerce' ), $order, $product, $item_id );

			$this->placeholders['{customer_name}']    = $order->get_formatted_billing_full_name();
			$this->placeholders['{product_title}']    = $product->get_title();
			$this->placeholders['{product_url}']      = $product_url;
			$this->placeholders['{product_link}']     = $product_link;
			$this->placeholders['{order_number}']     = $order->get_order_number();
			$this->placeholders['{order_link}']       = $order_link;
			$this->placeholders['{release_date}']     = $release_date ? ywpo_print_date( $release_date ) : $no_release_date_msg;
			$this->placeholders['{release_datetime}'] = $release_date ? ywpo_print_datetime( $release_date ) : $no_release_date_msg;
			$this->placeholders['{offset}']           = ywpo_get_timezone_offset_label();

			$this->email_body = $this->format_string( $this->get_option( 'email_body', $this->email_body ) );

			if ( $this->is_enabled() && $this->get_recipient() ) {
				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			}
		}

		/**
		 * Get content html.
		 *
		 * @return string
		 */
		public function get_content_html() {
			return wc_get_template_html(
				$this->template_html,
				array(
					'email_heading' => $this->get_heading(),
					'sent_to_admin' => true,
					'plain_text'    => false,
					'email'         => $this,
				),
				'',
				YITH_WCPO_TEMPLATE_PATH
			);
		}

		/**
		 * Initialise Settings Form Fields - these are generic email options most will use.
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'    => array(
					'title'   => __( 'Enable/Disable', 'yith-pre-order-for-woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this email notification', 'yith-pre-order-for-woocommerce' ),
					'default' => 'yes',
				),
				'recipient'  => array(
					'title'       => __( 'Recipient(s)', 'yith-pre-order-for-woocommerce' ),
					'type'        => 'text',
					// translators: %s: email recipient.
					'description' => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to %s.', 'yith-pre-order-for-woocommerce' ), '<code>' . esc_attr( get_option( 'admin_email' ) ) . '</code>' ),
					'placeholder' => '',
					'default'     => '',
				),
				'subject'    => array(
					'title'       => __( 'Subject', 'yith-pre-order-for-woocommerce' ),
					'type'        => 'text',
					// translators: %s: email subject.
					'description' => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>', 'yith-pre-order-for-woocommerce' ), $this->subject ),
					'placeholder' => '',
					'default'     => $this->subject,
				),
				'heading'    => array(
					'title'       => __( 'Email heading', 'yith-pre-order-for-woocommerce' ),
					'type'        => 'text',
					// translators: %s: email heading.
					'description' => sprintf( __( 'This controls the main heading included in the email notification. Leave blank to use the default heading: <code>%s</code>', 'yith-pre-order-for-woocommerce' ), $this->heading ),
					'placeholder' => '',
					'default'     => $this->heading,
				),
				'email_body' => array(
					'title'       => __( 'Email body', 'yith-pre-order-for-woocommerce' ),
					'type'        => 'textarea',
					// translators: %s: email body.
					'description' => sprintf( __( 'Defaults to <code>%s</code>', 'yith-pre-order-for-woocommerce' ), $this->email_body ),
					'placeholder' => '',
					'default'     => $this->email_body,
				),
			);
		}
	}
}

return new YITH_Pre_Order_New_Pre_Order_Email();
