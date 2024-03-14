<?php
/**
 * Settings Page
 *
 * @package Popup Anything on Click
 * @since 1.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $popupaoc_options;

// Plugin settings tab
$sett_tab		= popupaoc_settings_tab();
$sett_tab_count	= count( $sett_tab );
$tab			= isset( $_GET['tab'] ) ? popupaoc_clean( $_GET['tab'] ) : 'general';

// If no valid tab is there
if( ! isset( $sett_tab[ $tab ] ) ) {
	popupaoc_display_message( 'error' );
	return;
}

// Save button name
if( $sett_tab[ $tab ] == 'Integration' ) {
	$save_btn_name = 'paoc_intgs_mc_sett_submit';
} else if( $sett_tab[ $tab ] == 'display_rule' ) {
	$save_btn_name = 'paoc_display_rule_sett';
} else {
	$save_btn_name = 'paoc_settings_submit';
}
?>

<div class="wrap">

	<h2><?php esc_html_e( 'Popup Anything - Settings', 'popup-anything-on-click' ); ?></h2>

	<?php
	// Reset message
	if( ! empty( $_POST['popupaoc_reset_settings'] ) ) {
		popupaoc_display_message( 'reset' );
	}

	// Success message
	if( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
		popupaoc_display_message( 'update' );
	}

	settings_errors( 'popupaoc_sett_error' );

	// If more than one settings tab
	if( $sett_tab_count > 1 ) { ?>

	<h2 class="nav-tab-wrapper">
		<?php foreach ( $sett_tab as $tab_key => $tab_val ) {
			$tab_url 		= add_query_arg( array( 'post_type' => POPUPAOC_POST_TYPE, 'page' => 'popupaoc-settings', 'tab' => $tab_key ), admin_url('edit.php') );
			$active_tab_cls = ($tab == $tab_key) ? 'nav-tab-active' : '';
		?>
			<a class="nav-tab <?php echo esc_attr( $active_tab_cls ); ?>" href="<?php echo popupaoc_clean_url( $tab_url ); ?>"><?php echo wp_kses_post( $tab_val ); ?></a>
		<?php } ?>
	</h2>

	<?php } ?>

	<div class="paoc-sett-wrap paoc-settings paoc-cnt-wrap paoc-pad-top-20">

		<!-- Plugin reset settings form -->
		<form action="" method="post" id="paoc-reset-sett-form" class="paoc-right paoc-reset-sett-form">
			<input type="submit" class="button button-primary paoc-confirm paoc-btn paoc-reset-sett paoc-resett-sett-btn paoc-reset-sett" name="popupaoc_reset_settings" id="paoc-reset-sett" value="<?php esc_attr_e( 'Reset All Settings', 'popup-anything-on-click' ); ?>" />
			<?php wp_nonce_field( 'popupaoc_reset_setting', 'popupaoc_reset_sett_nonce' ); ?>
		</form>

		<form action="options.php" method="POST" id="paoc-settings-form" class="paoc-settings-form">

			<?php settings_fields( 'popupaoc_plugin_options' ); ?>

			<div class="textright paoc-clearfix">
				<input type="submit" name="<?php echo esc_attr( $save_btn_name ); ?>" class="button button-primary right paoc-btn paoc-sett-submit paoc-sett-submit" value="<?php esc_attr_e('Save Changes', 'popup-anything-on-click'); ?>" />
			</div>

			<div class="metabox-holder">
				<div class="post-box-container">
					<div class="meta-box-sortables ui-sortable">

						<?php
						// Setting files
						switch ( $tab ) {
							case 'general':
								include_once( POPUPAOC_DIR . '/includes/admin/settings/general-settings.php' );
								break;

							case 'display_rule':
								include_once( POPUPAOC_DIR . '/includes/admin/settings/display-rule-settings.php' );
								break;

							case 'integration':
								include_once( POPUPAOC_DIR . '/includes/admin/settings/integration-settings.php' );
								break;

							default:
								do_action( 'popupaoc_sett_panel_' . $tab );
								do_action( 'popupaoc_sett_panel', $tab );
								break;
						}
						?>

					</div><!-- end .meta-box-sortables -->
				</div><!-- end .post-box-container -->
			</div><!-- end .metabox-holder -->

		</form><!-- end .paoc-settings-form -->
	</div><!-- end .paoc-sett-wrap -->
</div><!-- end .wrap -->