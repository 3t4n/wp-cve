<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A custom Expedited Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class Sibs_WC_Payment_Processed_Email extends WC_Email {

    /**
     * Set email defaults
     *
     * @since 0.1
     */
    public function __construct() {

        // set ID, this simply needs to be a unique name
        $this->id = 'wc_payment_processed';

        // this is the title in WooCommerce Email settings
        $this->title = 'Payment Successfully Processed';

        // this is the description in WooCommerce email settings
        $this->description = 'Payment Successfully Processed Notification emails are sent when a customer successfully pays for an order';

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = 'Payment Successfully Processed';
        $this->subject = 'Payment Successfully Processed';

        // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
        $this->template_html  = 'emails/payment-successful.php';
        $this->template_plain = 'emails/plain/payment-successful.php';

        // Trigger on new paid orders
        // add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'trigger' ) );
        // add_action( 'woocommerce_order_status_failed_to_processing_notification',  array( $this, 'trigger' ) );

        // Call parent constructor to load any other defaults not explicity defined here
        parent::__construct();

        // this sets the recipient to the settings defined below in init_form_fields()
        $this->recipient = $this->get_option( 'recipient' );

        // if none was entered, just use the WP admin email as a fallback
        if ( ! $this->recipient )
            $this->recipient = get_option( 'admin_email' );
    }

    /**
     * Determine if the email should actually be sent and setup email merge variables
     *
     * @since 0.1
     * @param int $order_id
     */
    public function trigger( $order_id ) {

        // bail if no order ID is present
        if ( ! $order_id )
            return;

        // setup order object
        $this->object = new WC_Order( $order_id );

        // replace variables in the subject/headings
        $this->placeholders = array_merge(
            [
                '{order_date}' => date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) ),
                '{order_number}' => $this->object->get_order_number()
            ],
            $this->placeholders
        );

        if ( ! $this->is_enabled() || ! $this->get_recipient() )
            return;

        // woohoo, send the email!
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), [] );
    }

    /**
     * get_content_html function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_html() {
        ob_start();
        wc_get_template( $this->template_html, array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading()
        ) );
        return ob_get_clean();
    }


    /**
     * get_content_plain function.
     *
     * @since 0.1
     * @return string
     */
    public function get_content_plain() {
        ob_start();
        wc_get_template( $this->template_plain, array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading()
        ) );
        return ob_get_clean();
    }

    /**
     * Initialize Settings Form Fields
     *
     * @since 0.1
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
                    'plain'     => 'Plain text',
                    'html'      => 'HTML', 'woocommerce',
                    'multipart' => 'Multipart', 'woocommerce',
                )
            )
        );
    }

} // end \WC_Expedited_Order_Email class
