<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly
}


if(!class_exists('WC_Email_Admin_Order_Cancel_Request')):

    class WC_Email_Admin_Order_Cancel_Request extends WC_Email {
        /**
         * Constructor
         */
        function __construct() {

            $this->id = 'admin_order_cancel_request';
            $this->title = __( 'Admin Order Cancellation Request', 'cancel-order-request-woocommerce' );
            $this->description= __( 'Order cancellation request email sent to the admin when customer send the order cancellation request.', 'cancel-order-request-woocommerce' );
            $this->heading = __( 'Order cancellation request received for order #{order_number_link}', 'cancel-order-request-woocommerce' );
            $this->subject      = __( 'Order cancellation request received for order #{order_number}', 'cancel-order-request-woocommerce' );
            $this->template_base = PISOL_CORW_BASE_DIR.'/templates/';
            $this->template_html = 'emails/admin-order-cancel-request.php';
            $this->template_plain = 'emails/plain/admin-order-cancel-request.php';
            $this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
	        $this->placeholders   = array(
		        '{site_title}'   => $this->get_blogname(),
		        '{order_date}'   => '',
                '{order_number}' => '',
                '{order_number_link}' => '',
            );

            parent::__construct();
        }

        /**
         * trigger function.
         *
         * @access public
         * @return void
         */
        function trigger( $order_id ) {
	        $this->setup_locale();
            $order = wc_get_order( $order_id );
            $this->order_id = $order_id;
            $this->cancellation_reason = $order->get_meta( 'order_cancel_reason', true);
            $this->predefined_reason = $order->get_meta( 'predefined_reason', true);
	        if ( is_a( $order, 'WC_Order' ) ) {
		        $this->object                         = $order;
		        $this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
                $this->placeholders['{order_number}'] = $this->object->get_order_number();
                $this->placeholders['{order_number_link}'] = sprintf('<a href="%s">%s</a>',  $this->object->get_edit_order_url(), $this->object->get_order_number());
	        }

	        if ( $this->is_enabled() && $this->get_recipient() ) {
		        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	        }
	        $this->restore_locale();
        }

        /**
         * get_content_html function.
         *
         * @access public
         * @return string
         */
        function get_content_html() {

            return wc_get_template_html(
                $this->template_html,
                array(
                'order' => $this->object,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => true,
                'plain_text'=>false,
                'email' => $this,
                'cancellation_reason' => $this->cancellation_reason,
                'predefined_reason' => $this->predefined_reason,
                ),
                '',
	            $this->template_base);

        }

        /**
         * get_content_plain function.
         *
         * @access public
         * @return string
         */
        function get_content_plain() {

            return wc_get_template_html(
                $this->template_plain,
                array(
                'order' => $this->object,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => true,
                'plain_text'=>true,
                'email' => $this,
                'cancellation_reason' => $this->cancellation_reason,
                'predefined_reason' => $this->predefined_reason,
                ),
                '',
	            $this->template_base);

        }

        function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'         => __( 'Enable/Disable', 'cancel-order-request-woocommerce' ),
                    'type'          => 'checkbox',
                    'label'         => __( 'Enable this email notification', 'cancel-order-request-woocommerce' ),
                    'default'       => 'yes'
                ),
                'recipient' => array(
                    'title'         => __( 'Recipient(s)', 'cancel-order-request-woocommerce' ),
                    'type'          => 'text',
                    'description'   => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'cancel-order-request-woocommerce' ), esc_attr( get_option('admin_email') ) ),
                    'placeholder'   => '',
                    'default'       => esc_attr( get_option('admin_email') ),
                    'desc_tip'      => true
                ),
                
                'email_type' => array(
                    'title'         => __( 'Email type', 'cancel-order-request-woocommerce' ),
                    'type'          => 'select',
                    'description'   => __( 'Choose which format of email to send.', 'cancel-order-request-woocommerce' ),
                    'default'       => 'html',
                    'class'         => 'email_type wc-enhanced-select',
                    'options'       => $this->get_email_type_options(),
                    'desc_tip'      => true
                )
            );
        }

    }

endif;