<?php
/**
 * Template Footer.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// It displays the save button and related messages
function eos_dp_save_button( $css_class = false ) {
	$extra_class         = '';
	$warning             = '';
	$permalink_structure = get_option( 'permalink_structure' );
	$permalinks_label    = __( 'the actual permalinks structure is not supported' );
	if ( '' === $permalink_structure ) {
		$permalinks_label = __( 'the permalinks are set as plain', 'freesoul-deactivate-plugins' );
	} elseif ( '/archives/%post_id%' === $permalink_structure ) {
		$permalinks_label = __( 'the permalinks are set as numeric', 'freesoul-deactivate-plugins' );
	}
	if ( false === strpos( basename( $permalink_structure ), '%postname%' ) && ! in_array( $_GET['page'], array( 'eos_dp_admin', 'eos_dp_admin_url', 'eos_dp_url' ) ) ) {
			$extra_class = ' eos-no-events';
			$warning     = '<div style="background:#fff;color:#000;padding:10px;margin-bottom:10px;border-left:4px solid  #dc3232">' . sprintf( esc_html__( "You can't save because %s", 'freesoul-deactivate-plugins' ), esc_html( $permalinks_label ) );
			$warning    .= '<p><a class="button" target="_blank" href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">' . esc_html__( 'Change Permalinks Structure', 'freesoul-deactivate-plugins' ) . '</a></p>';
			$warning    .= '</div>';
	}
	if ( $css_class ) {
		$extra_class .= ' ' . $css_class;
	}
	$dir     = is_rtl() ? 'left' : 'right';
	$antiDir = is_rtl() ? 'right' : 'left';
	$page    = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
	if ( current_user_can( 'activate_plugins' ) ) {
		?>
	<div class="<?php echo apply_filters( 'fdp_save_button_wrapper_css_class', 'eos-dp-btn-wrp' ); ?>" style="margin-top:40px">
		<?php echo wp_kses_post( $warning ); ?>
		<input type="submit" name="submit" class="eos-dp-save-<?php
		echo esc_attr( $page );
		echo esc_attr( apply_filters( 'fdp_submit_button_class', $extra_class ) );
		?> button button-primary submit-dp-opts" data-backup="false" value="<?php esc_attr_e( 'Save all changes', 'freesoul-deactivate-plugins' ); ?>"  />
		<?php eos_dp_ajax_loader_img(); ?>
		<div style="margin-<?php echo esc_attr( $dir ); ?>:30px">
			<div class="eos-hidden eos-dp-opts-msg eos-dp-opts-msg_success msg_response" style="padding:10px;margin:10px;border-left:4px solid #00a32a;background:#fff">
				<span><?php esc_html_e( 'Options saved.', 'freesoul-deactivate-plugins' ); ?></span>
			</div>
			<div class="eos-dp-opts-msg_failed eos-dp-opts-msg eos-hidden msg_response" style="padding:10px;margin:10px;border-left:4px solid #d63638;background:#fff">
				<span><?php echo wp_kses_post( apply_filters( 'fdp_generic_failure_message', __( 'Something went wrong, maybe you need to refresh the page and try again, but you will lose all your changes', 'freesoul-deactivate-plugins' ) ) ); ?></span>
			</div>
			<div class="eos-dp-opts-msg_warning eos-dp-opts-msg eos-hidden msg_response" style="padding:10px;margin:10px;border-left:4px solid #dba617;background:#fff">
				<span></span>
			</div>
			<div class="eos-dp-opts-response_msg eos-dp-opts-msg eos-hidden msg_response" style="height:100vh;overflow-y:auto;padding:10px;position:fixed;top:40px;background:#fff">
				<p><button id="fdp-response-popup-close" type="button" class="notice-dismiss"></button></p>
				<div></div>
				<p><img id="fdp-response-screenshot" class="eos-hidden" src="" /></p>
				<p>
					<?php wp_nonce_field( 'eos_dp_pro_remove_test', 'eos_dp_pro_remove_test' ); ?>
					<span id="fdp-remove-from-report" class="button"><?php esc_html_e( 'Discard', 'eos-dp-pro' ); ?></span>
					<span id="fdp-test-close" class="button"><?php esc_html_e( 'Close', 'eos-dp-pro' ); ?></span>
					<a id="fdp-see-all-gtmetrix-tests" class="button fdp-see-all-tests eos-hidden" style="position:relative;padding-<?php echo esc_attr( $dir ); ?>:30px" href="<?php echo esc_url( admin_url( '/admin.php?page=eos_dp_report&tool=gtmetrix' ) ); ?>" target="_fdp_gtmetrix_report"><span style="position:absolute;<?php echo esc_attr( $dir ); ?>:0" class="eos-dp-gtmetrix-icon"></span><?php esc_html_e( 'All tests', 'eos-dp-pro' ); ?></a>
					<a id="fdp-see-all-gpsi-tests" class="button fdp-see-all-tests eos-hidden" style="position:relative;padding-<?php echo esc_attr( $dir ); ?>:30px" href="<?php echo esc_url( admin_url( '/admin.php?page=eos_dp_report&tool=gpsi' ) ); ?>" target="_fdp_gpsi_report"><span style="position:absolute;<?php echo esc_attr( $dir ); ?>:0" class="eos-dp-gpsi-icon"></span><?php esc_html_e( 'All tests', 'eos-dp-pro' ); ?></a>
				</p>
			</div>
		</div>
	</div>
		<?php
		do_action( 'fdp_after_save_button' );
	}
	if ( function_exists( 'get_user_locale' ) ) {
		$locale = get_user_locale();
		if ( $locale ) {
			$locA = explode( '_', $locale );
			if ( isset( $locA[0] ) && ! in_array( $locA[0], array( 'en', 'it' ) ) ) {
				?>
			<div id="eos-dp-translate" class="eos-dp-margin-top-48">
				<p><?php echo wp_kses_post( sprintf( __( 'Click %1$shere%2$s if you want to translate Freesoul Deactivate Plugins in your language.', 'freesoul-deactivate-plugins' ), '<a href="https://translate.wordpress.org/projects/wp-plugins/freesoul-deactivate-plugins/stable/' . esc_attr( $locA[0] ) . '/default/" rel="noopener" target="_blank">', '</a>' ) ); ?></p>
			</div>
				<?php
			}
		}
	}
	?>
	<div id="eos-dp-popup" style="display:none;z-index:9999999999;background:#fff;padding:0 20px 20px 20px;position:fixed;<?php echo esc_attr( $antiDir ); ?>:50%;-o-transform:translateX(-50%);-ms-transform:translateX(-50%);-moz-transform:translateX(-50%);-webkit-transform:translateX(-50%);transform:translateX(-50%);top:50px;width:800px;height:auto;max-width:80%;box-sizing:border-box;max-height:80vh;overflow-y:auto">
		<div id="eos-dp-popup-top" style="<?php echo esc_attr( $dir ); ?>:0;text-align:<?php echo esc_attr( $dir ); ?>;position:absolute;position:sticky;top:0">
			<span id="eos-dp-popup-close" class="hover dashicons dashicons-no-alt" style="padding:10px;font-size:24px" title="<?php esc_attr_e( 'Close', 'freesoul-deactivate-plugins' ); ?>"></span>
		</div>
		<h3><?php echo wp_kses_post( sprintf( __( 'The %1$spage%2$s was checked simulating a logged user.', 'freesoul-deactivate-plugins' ), '<a id="eos-dp-popup-page-link" href="#" target="_blank">', '</a>' ) ); ?></h3>
		<div id="eos-dp-popup-txt"></div>
	</div>
	<?php
	do_action( 'eos_dp_after_footer' );
	eos_dp_pro_version_notice();
}
