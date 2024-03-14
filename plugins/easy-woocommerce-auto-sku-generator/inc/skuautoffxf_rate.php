<?php
/**
 * Notifications to the user about the plugin revocation
 **/

add_action( 'admin_notices', 'ffxf_plugin_notice_rate' );
function ffxf_plugin_notice_rate() {
	if ( current_user_can( 'manage_options' ) ) {
		$glideffxf_data_install = get_option( 'glideffxf_data_install' );
		if ( $glideffxf_data_install === null || $glideffxf_data_install === false || strtotime( date( "Y-m-d" ) ) >= strtotime( $glideffxf_data_install ) ) {
			wp_enqueue_script( 'ffxf-rate-sku' );
			?>
            <div id="ffxf_rate_sku" class="notice notice-info is-dismissible">
                <p><img src="https://ps.w.org/easy-woocommerce-auto-sku-generator/assets/icon-128x128.png" alt="" style="float: left; width: 96px; margin-right: 14px; border-radius: 4px;">
					<?php echo __( 'Hello!', 'easy-woocommerce-auto-sku-generator' ); ?><br>
					<?php echo __( 'We are very pleased that you are using the <b>Easy Auto SKU Generator for WooCommerce</b> plugin within a few days.', 'easy-woocommerce-auto-sku-generator' ); ?>
                    <br>
					<?php echo __( 'Please rate plugin. It will help us a lot.', 'easy-woocommerce-auto-sku-generator' ); ?>
                </p>
                <p>
                    <a id="i_have_already_by_ajax_callback" class="button button-secondary"
                       href="#"><?php echo __( 'Don\'t show again!', 'easy-woocommerce-auto-sku-generator' ); ?>
                        <span style="font-size: 16px; position: relative; bottom: -5px;"
                              class="dashicons dashicons-smiley"></span></a>
                    <a id="remind_me_later_by_ajax_callback" class="button button-secondary"
                       href="#"><?php echo __( 'Remind me later', 'easy-woocommerce-auto-sku-generator' ); ?> <span
                                style="font-size: 16px; position: relative; bottom: -5px;"
                                class="dashicons dashicons-backup"></span></a>
                    <a target="_blank" id="leave_feedback" class="button button-primary"
                       href="https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/reviews/#new-post"><?php echo __( 'Leave feedback', 'easy-woocommerce-auto-sku-generator' ); ?>
                        <span style="font-size: 16px; position: relative; bottom: -5px;"
                              class="dashicons dashicons-format-status"></span></a>
                </p>
            </div>

			<?php
		}
	}
}


add_action( 'wp_ajax_i_have', 'i_have_already_by_ajax_callback' );

function i_have_already_by_ajax_callback() {
	if ( current_user_can( 'manage_options' ) ) {
		update_option( 'glideffxf_data_install', date( 'Y-m-d', strtotime( '+4 years' ) ) );
		wp_die();
	}
}

add_action( 'wp_ajax_remind_me_later', 'remind_me_later_by_ajax_callback' );

function remind_me_later_by_ajax_callback() {
	if ( current_user_can( 'manage_options' ) ) {
		update_option( 'glideffxf_data_install', date( 'Y-m-d', strtotime( '+1 days' ) ) );
		wp_die();
	}
}

add_action( 'wp_ajax_leave_feedback', 'leave_feedback_by_ajax_callback' );

function leave_feedback_by_ajax_callback() {
	if ( current_user_can( 'manage_options' ) ) {
		update_option( 'glideffxf_data_install', date( 'Y-m-d', strtotime( '+5 years' ) ) );
		wp_die();
	}
}