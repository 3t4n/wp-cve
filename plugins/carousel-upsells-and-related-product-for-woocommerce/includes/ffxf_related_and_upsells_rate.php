<?php
/**
 * Notifications to the user about the plugin revocation
 **/

add_action( 'admin_notices', 'ffxf_plugin_notice_related_and_upsells_rate' );
function ffxf_plugin_notice_related_and_upsells_rate() {
	if ( current_user_can( 'manage_options' ) ) {
		$glideffxf_data_install_related_and_upsells = get_option( 'glideffxf_data_install_related_and_upsells' );
		if ( strtotime( date( "Y-m-d" ) ) >= strtotime( $glideffxf_data_install_related_and_upsells ) && $glideffxf_data_install_related_and_upsells != false && $glideffxf_data_install_related_and_upsells != null) {
			wp_enqueue_script( 'glideffxf-rate-related-and-upsells' );
			?>
            <div id="ffxf_rate_related_and_upsells" class="notice notice-info is-dismissible">
                <p> <img style="float:left;width:96px;margin-right:14px;" src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-128x128.png" alt="">
					<?php echo __( 'Hello!', 'carousel-upsells-and-related-product-for-woocommerce' ); ?><br>
					<?php echo __( 'We are very pleased that you are using the <b>Carousel Upsells and Related Product for Woocommerce</b> plugin within a few days.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>
                    <br>
					<?php echo __( 'Please rate plugin. It will help us a lot.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>
                </p>


                <p>
                    <a id="i_have_already_related_and_upsells_by_ajax_callback" class="button button-secondary" href="#">
                        <?php echo __( 'I have already left a review!', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>
                        <span style="font-size: 16px; position: relative; bottom: -5px;" class="dashicons dashicons-smiley"></span>
                    </a>
                    <a id="remind_me_later_related_and_upsells_by_ajax_callback" class="button button-secondary" href="#">
                        <?php echo __( 'Remind me later', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>
                        <span style="font-size: 16px; position: relative; bottom: -5px;" class="dashicons dashicons-backup"></span>
                    </a>
                    <a target="_blank" id="leave_feedback_related_and_upsells_by_ajax_callback" class="button button-primary" href="https://wordpress.org/support/plugin/carousel-upsells-and-related-product-for-woocommerce/reviews/#new-post">
                        <?php echo __( 'Leave feedback', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>
                        <span style="font-size: 16px; position: relative; bottom: -5px;" class="dashicons dashicons-format-status"></span>
                    </a>
                </p>
            </div>

			<?php
		}
	}
}


add_action( 'wp_ajax_i_have_related_and_upsells', 'i_have_already_related_and_upsells_by_ajax_callback' );

function i_have_already_related_and_upsells_by_ajax_callback() {
	if ( current_user_can( 'manage_options' ) ) {
		update_option( 'glideffxf_data_install_related_and_upsells', date( 'Y-m-d', strtotime( '+4 years' ) ) );
		wp_die();
	}
}

add_action( 'wp_ajax_remind_me_later_related_and_upsells', 'remind_me_later_related_and_upsells_by_ajax_callback' );

function remind_me_later_related_and_upsells_by_ajax_callback() {
	if ( current_user_can( 'manage_options' ) ) {
		update_option( 'glideffxf_data_install_related_and_upsells', date( 'Y-m-d', strtotime( '+1 days' ) ) );
		wp_die();
	}
}

add_action( 'wp_ajax_leave_feedback_related_and_upsells', 'leave_feedback_related_and_upsellsby_ajax_callback' );

function leave_feedback_related_and_upsellsby_ajax_callback() {
	if ( current_user_can( 'manage_options' ) ) {
		update_option( 'glideffxf_data_install_related_and_upsells', date( 'Y-m-d', strtotime( '+5 years' ) ) );
		wp_die();
	}
}