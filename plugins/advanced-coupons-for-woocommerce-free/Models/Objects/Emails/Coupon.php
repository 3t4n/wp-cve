<?php

namespace ACFWF\Models\Objects\Emails;

use ACFWF\Models\Objects\Advanced_Coupon;

/**
 * Model that houses the data model of an advanced coupon email.
 *
 * @since 4.5.3
 */
class Coupon extends \WC_Email {

    /**
     * Class constructor.
     *
     * @since 4.5.3
     * @access public
     */
    public function __construct() {
        $this->id             = 'acfw_coupon_email';
        $this->customer_email = true;
        $this->title          = __( 'Advanced Coupons - send to customer', 'advanced-coupons-for-woocommerce-free' );
        $this->description    = __( 'Email of the coupon that is sent to the customer.', 'advanced-coupons-for-woocommerce-free' );
        $this->template_html  = 'emails/email-advanced-coupon.php';
        $this->template_plain = 'emails/plain/email-advanced-coupon.php';
        $this->placeholders   = array(
            '{coupon_code}'    => '',
            '{customer_name}'  => '',
            '{customer_email}' => '',
        );

        add_action( 'acfwf_send_advanced_coupon_email', array( $this, 'trigger' ), 10, 2 );

        parent::__construct();
    }

    /**
     * Get email's default subject.
     *
     * @since 4.5.3
     * @access public
     *
     * @return string
     */
    public function get_default_subject() {
        /* Translators: %s: Site title */
        return sprintf( __( '%s: You just received a coupon!', 'advanced-coupons-for-woocommerce-free' ), '[{site_title}]' );
    }

    /**
     * Get email subject.
     *
     * @since 4.5.3
     * @access public
     *
     * @return string
     */
    public function get_default_heading() {
        return __( 'You just received a coupon!', 'advanced-coupons-for-woocommerce-free' );
    }

    /**
     * Get default message content.
     *
     * @since 4.5.3
     * @access public
     *
     * @return string
     */
    public function get_default_message() {
        /* Translators: %s: Site title */
        return sprintf( __( 'Congrats! You just received a coupon from %s.', 'advanced-coupons-for-woocommerce-free' ), '{site_title}' );
    }

    /**
     * Get default message content.
     *
     * @since 4.5.3
     * @access public
     *
     * @return string
     */
    public function get_default_button_text() {
        return __( 'Click here to redeem', 'advanced-coupons-for-woocommerce-free' );
    }

    /**
     * Default content to show below main email content.
     *
     * @since 4.5.3
     * @access public
     *
     * @return string
     */
    public function get_default_additional_content() {
        return '';
    }

    /**
     * Set advanced coupon instance.
     *
     * @since 4.5.3
     * @access public
     *
     * @param Advanced_Coupon $coupon Coupon object.
     */
    public function set_coupon( Advanced_Coupon $coupon ) {
        $this->coupon = $coupon;

        $this->placeholders['{coupon_code}'] = $coupon->get_code();
    }

    /**
     * Set advanced coupon instance.
     *
     * @since 4.5.3
     * @access public
     *
     * @param \WC_Customer $customer Customer object.
     */
    public function set_customer( \WC_Customer $customer ) {
        $this->customer = $customer;

        $this->placeholders['{customer_name}']  = $customer->get_display_name();
        $this->placeholders['{customer_email}'] = $customer->get_email();
    }

    /**
     * Trigger sending of this email.
     *
     * @since 4.5.3
     * @access public
     *
     * @param Advanced_Coupon $coupon Coupon object.
     * @param WC_Customer     $customer Customer object.
     */
    public function trigger( $coupon, $customer ) {
        do_action( 'acfw_before_send_coupon_email', $coupon, $customer );

        $this->setup_locale();
        $this->set_coupon( $coupon );
        $this->set_customer( $customer );

        $this->recipient = $customer->get_email();

        if ( $this->is_enabled() && $this->get_recipient() ) {

            $this->send(
                $this->get_recipient(),
                $this->get_subject(),
                $this->get_content(),
                $this->get_headers(),
                $this->get_attachments()
            );

        }

        $this->restore_locale();

        do_action( 'acfw_after_send_coupon_email', $coupon, $customer );
    }

    /**
     * Override setup locale function to remove customer email check.
     *
     * @since 4.5.3
     * @access public
     */
    public function setup_locale() {
        if ( apply_filters( 'woocommerce_email_setup_locale', true ) ) {
            wc_switch_to_site_locale();
        }
    }

    /**
     * Override restore locale function to remove customer email check.
     *
     * @since 4.5.3
     * @access public
     */
    public function restore_locale() {
        if ( apply_filters( 'woocommerce_email_restore_locale', true ) ) {
            wc_restore_locale();
        }
    }

    /**
	 * Get email heading.
     *
     * @since 4.5.3
     * @access public
	 *
	 * @return string
	 */
	public function get_message() {
		return apply_filters( 'acfw_email_heading_' . $this->id, $this->format_string( $this->get_option( 'message', $this->get_default_message() ) ), $this->object, $this );
	}

    /**
	 * Get button text.
     *
     * @since 4.5.3
     * @access public
	 *
	 * @return string
	 */
	public function get_button_text() {
		return apply_filters( 'acfw_email_heading_' . $this->id, $this->format_string( $this->get_option( 'button_text', $this->get_default_button_text() ) ), $this->object, $this );
	}

    /**
     * Get email content html.
     *
     * @since 4.5.3
     * @access public
     *
     * @return string Email html content.
     */
    public function get_content_html() {
        ob_start();

        \ACFWF()->Helper_Functions->load_template(
            $this->template_html,
            array(
                'coupon'             => $this->coupon,
                'customer'           => $this->customer,
                'email_heading'      => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'email'              => $this,
            )
        );

        return ob_get_clean();
    }

    /**
     * Get email plain content.
     *
     * @since 4.5.3
     * @access public
     *
     * @return string Email plain content.
     */
    public function get_content_plain() {
        ob_start();

        \ACFWF()->Helper_Functions->load_template(
            $this->template_plain,
            array(
                'coupon'             => $this->coupon,
                'customer'           => $this->customer,
                'email_heading'      => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'email'              => $this,
            )
        );

        return ob_get_clean();
    }

    /**
     * Initialize email setting form fields.
     *
     * @since 4.5.3
     * @access public
     */
    public function init_form_fields() {
        /* Translators: %s: list of available placeholder tags */
        $placeholder_text  = sprintf( __( 'Available placeholders: %s', 'advanced-coupons-for-woocommerce-free' ), '<code>' . implode( '</code>, <code>', array_keys( $this->placeholders ) ) . '</code>' );
        $this->form_fields = array(
            'enabled'            => array(
                'title'   => __( 'Enable/Disable', 'advanced-coupons-for-woocommerce-free' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable this email', 'advanced-coupons-for-woocommerce-free' ),
                'default' => 'yes',
            ),
            'subject'            => array(
                'title'       => __( 'Subject', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'description' => $placeholder_text,
                'placeholder' => $this->get_default_subject(),
                'default'     => '',
            ),
            'heading'            => array(
                'title'       => __( 'Email heading', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'description' => $placeholder_text,
                'placeholder' => $this->get_default_heading(),
                'default'     => '',
            ),
            'message'            => array(
                'title'       => __( 'Message', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'description' => $placeholder_text,
                'placeholder' => $this->get_default_message(),
                'default'     => '',
            ),
            'button_text'        => array(
                'title'       => __( 'Button text', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'description' => $placeholder_text,
                'placeholder' => $this->get_default_button_text(),
                'default'     => '',
            ),
            'additional_content' => array(
                'title'       => __( 'Additional content', 'advanced-coupons-for-woocommerce-free' ),
                'description' => __( 'Text to appear below the main email content.', 'advanced-coupons-for-woocommerce-free' ) . ' ' . $placeholder_text,
                'css'         => 'width:400px; height: 75px;',
                'placeholder' => __( 'N/A', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'textarea',
                'default'     => $this->get_default_additional_content(),
                'desc_tip'    => true,
            ),
            'email_type'         => array(
                'title'       => __( 'Email type', 'advanced-coupons-for-woocommerce-free' ),
                'type'        => 'select',
                'description' => __( 'Choose which format of email to send.', 'advanced-coupons-for-woocommerce-free' ),
                'default'     => 'html',
                'class'       => 'email_type wc-enhanced-select',
                'options'     => $this->get_email_type_options(),
                'desc_tip'    => true,
            ),
        );
    }
}
