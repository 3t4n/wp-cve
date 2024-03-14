<?php

/**
 * Compatibility Plugin:       Mailchimp for WooCommerce By Mailchimp
 * Compatibility Version:           2.5.0
 * Compatibility  URL:        https://wordpress.org/plugins/mailchimp-for-woocommerce/
 */

#[AllowDynamicProperties]

  class WFACP_Compatibility_Mailchimp_For_WC {
	private $object = null;

	public function __construct() {
		add_filter( 'wfacp_template_load', [ $this, 'add_action' ], 20 );
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_wfacp_mailchimp_for_wc', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'display_field' ], 999, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );
	}

	public function is_enabled() {
		$page_version = WFACP_Common::get_checkout_page_version();
		if ( false === class_exists( 'MailChimp_Newsletter' ) ) {
			return false;
		}
		if ( false === version_compare( $page_version, '2.3.0', '>' ) ) {
			return false;
		}
		if ( false === function_exists( 'mailchimp_is_configured' ) || false === mailchimp_is_configured() ) {
			return false;
		}


		$this->object = MailChimp_Newsletter::instance();

		if ( ! $this->object instanceof MailChimp_Newsletter ) {

			return false;
		}

		if ( false === $this->object->isConfigured() ) {
			return false;
		}

		return true;
	}

	public function add_action() {
		if ( false === $this->is_enabled() ) {
			return '';
		}
		$render_on = $this->object->getOption( 'mailchimp_checkbox_action', 'woocommerce_after_checkout_billing_form' );

		WFACP_Common::remove_actions( $render_on, 'MailChimp_Newsletter', 'applyNewsletterField' );
	}

	public function add_field( $fields ) {
		if ( false === $this->is_enabled() ) {
			return $fields;
		}
		$fields['wfacp_mailchimp_for_wc'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_mailchimp_for_wc' ],
			'id'         => 'wfacp_mailchimp_for_wc',
			'field_type' => 'wfacp_mailchimp_for_wc',
			'label'      => __( 'MailChimp', 'woofunnels-aero-checkout' ),
		];

		return $fields;
	}

	public function display_field( $field, $key ) {

		if ( empty( $key ) || 'wfacp_mailchimp_for_wc' !== $key || false === $this->is_enabled() ) {
			return;
		}

		echo "<div  id='wfacp_mailchild_field_wrap'>";
		$this->object->applyNewsletterField( WC()->checkout() );
		echo '</div>';
	}

	public function wfacp_internal_css() {
		if ( false === $this->is_enabled() ) {
			return;
		}
		?>
        <style>
            #wfacp_mailchild_field_wrap {
                position: relative;
                clear: both;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form #wfacp_mailchild_field_wrap > p.form-row {
                margin-left: 0px;
                margin-right: 0px;
            }


            body .wfacp_main_form.woocommerce #wfacp_checkout_form #wfacp_mailchild_field_wrap input[type="checkbox"] {
                position: relative;
                top: 0;
                margin: 0 5px 0 0;
                float: none;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form #wfacp_mailchild_field_wrap label {
                padding-left: 0 !important;
                font-weight: normal;
            }

            body .wfacp_main_form.woocommerce #wfacp_checkout_form #wfacp_mailchild_field_wrap label span {
                font-weight: normal;
            }
            body .wfacp_main_form.woocommerce #wfacp_checkout_form #wfacp_mailchild_field_wrap label span{
                vertical-align: middle;
            }


            body .wfacp_main_form #wfacp_checkout_form #payment p.form-row.form-row-wide.mailchimp-newsletter {
                margin-bottom: 0;
                margin-left: 0;
            }

            body .wfacp_main_form #wfacp_checkout_form p.form-row.form-row-wide.mailchimp-newsletter input[type=checkbox] + label {
                padding: 0 !important;
                display: inline-block !important;
            }
        </style>
		<?php
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_Mailchimp_For_WC(), 'wfacp-mailchimp-for-wc' );
