<?php
/**
 * Useful functions
 *
 * @package YITH_Proteo_tookit
 */

/**
 * Display a navigation bar in setup wizard box
 *
 * @param string $step Current active step.
 * @return void
 */
function yith_proteo_toolkit_wizard_step_icon( $step = null ) {
	?>
	<div class="yith-proteo-toolkit-wizard-nav">
		<ul class="steps">
			<li class="step <?php echo 'child' === $step ? 'active' : ''; ?>">
				<img src="<?php echo esc_url( YITH_PROTEO_TOOLKIT_URL ); ?>assets/img/child-theme.svg" width="30">
			</li>
			<li class="step <?php echo 'skin' === $step ? 'active' : ''; ?>">
				<img src="<?php echo esc_url( YITH_PROTEO_TOOLKIT_URL ); ?>assets/img/art.svg" width="30">
			</li>
			<li class="step <?php echo 'plugins' === $step ? 'active' : ''; ?>">
				<img src="<?php echo esc_url( YITH_PROTEO_TOOLKIT_URL ); ?>assets/img/plugin.svg" width="30">
			</li>
			<li class="step <?php echo 'content' === $step ? 'active' : ''; ?>">
				<img src="<?php echo esc_url( YITH_PROTEO_TOOLKIT_URL ); ?>assets/img/content.svg" width="30">
			</li>
			<li class="step <?php echo 'done' === $step ? 'active' : ''; ?>">
				<img src="<?php echo esc_url( YITH_PROTEO_TOOLKIT_URL ); ?>assets/img/done.svg" width="30">
			</li>
		</ul>
	</div>
	<?php
}

/**
 * Disable plugin
 *
 * @return void
 */
function yith_proteo_toolkit_disable() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

/**
 * Check if plugin may be enabled
 */
function yith_proteo_toolkit_can_be_enabled() {
	return defined( 'YITH_PROTEO_VERSION' );
}

/**
 * Show admin notice when Proteo theme is not enabled
 *
 * @return void
 */
function yith_proteo_toolkit_admin_notice() {
	?>
	<div class="error">
		<?php /* translators: %1$1s: plugin name; %2$2s: theme name; */ ?>
		<p><?php echo sprintf( esc_html__( '%1$1s is meant to be used with %2$2s theme.', 'yith-proteo-toolkit' ), '<b>YITH Proteo Toolkit</b>', '<b>YITH Proteo</b>' ); ?></p>
	</div>
	<?php
}
