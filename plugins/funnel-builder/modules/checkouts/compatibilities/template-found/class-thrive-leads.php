<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Thrive_Leads {

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'add_styling' ] );


	}

	public function add_styling() {
		if ( class_exists( 'Thrive\Theme\Integrations\WooCommerce\Filters' ) ) {
			WFACP_Common::remove_actions( 'thrive_theme_template_meta', 'Thrive\Theme\Integrations\WooCommerce\Filters', 'modify_template_meta' );
		}
		if ( function_exists( 'tve_leads_display_form_lightbox' ) ) {
			add_action( 'wp_head', function () {
				?>
                <style>


                    body.wfacp_main_wrapper.wfacp_cls_layout_9.tve-o-hidden.tve-l-open.tve-hide-overflow,
                    body.wfacp_main_wrapper.wfacp_cls_layout_1.tve-o-hidden.tve-l-open.tve-hide-overflow,
                    body.wfacp_main_wrapper.tve-o-hidden.tve-hide-overflow.tve-l-open:not(.bp-t) {
                        height: 100% !important;
                    }

                    body.wfacp_main_wrapper.wfacp_cls_layout_9.tve-o-hidden.tve-l-open.tve-hide-overflow .wfacp_footer_sec_for_script,
                    body.wfacp_main_wrapper.wfacp_cls_layout_1.tve-o-hidden.tve-l-open.tve-hide-overflow .wfacp_footer_sec_for_script,
                    body.wfacp_main_wrapper.tve-o-hidden.tve-l-open.tve-hide-overflow .wfacp_footer_sec_for_script {
                        display: block;
                    }

                    .ic_loader:after, .ic_loader:before {
                        display: none;
                    }

                </style>
				<?php
			} );
		}
	}

	public function thrive_enabled( $post_id ) {
		$editor_enabled  = get_post_meta( $post_id, 'tcb_editor_enabled', true );
		$editor_disabled = get_post_meta( $post_id, 'tcb_editor_disabled', true );

		return ! empty( $editor_enabled ) && empty( $editor_disabled );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Thrive_Leads(), 'thrive-leads' );
