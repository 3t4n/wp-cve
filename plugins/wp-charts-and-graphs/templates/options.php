<div id="screen_preloader" style="position: absolute;width: 100%;height: 1000px;z-index: 9999;text-align: center;background: #fff;padding-top: 200px;">
	<h3>WP Charts and Graphs<br><small><i>by Pantherius</i></small></h3>
	<img src="<?php print( plugins_url( '../assets/img/screen_preloader.gif' , __FILE__ ) );?>">
	<h5><?php esc_html_e( 'LOADING', PWPC_CHARTS_TEXT_DOMAIN );?><br><br><?php esc_html_e( 'Please wait...', 'pantherius-wordpress-survey-polls' );?></h5>
</div>
<div class="wrap pwpc" style="visibility:hidden">
	<h3 class="pwpc-title">WP Charts and Graphs</h3>
	<div id="pwp-charts-left">
		<div id="wp-charts-settings">
			<form method="post" action="options.php">
			<?php
				if ( isset( $_REQUEST[ 'settings-updated' ] ) ) {
			?>
			<div id="message" class="updated below-h2">
				<p>
					<?php esc_html_e( 'Settings saved.', MODAL_SURVEY_TEXT_DOMAIN );?>
				</p>
			</div>
			<?php 
				}
			?>
				<?php settings_fields( 'pantherius_wp_charts-group' ); ?>
				<?php do_settings_fields( 'pantherius_wp_charts-group', 'pantherius_wp_charts-section' ); ?>
				<?php do_settings_sections( 'pantherius_wp_charts' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	</div>
	<?php require_once( plugin_dir_path( __FILE__ ) . '/sidebar.php' );?>
</div>