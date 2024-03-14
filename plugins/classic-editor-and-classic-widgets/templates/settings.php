<div class="wrap">
	<h1><?php esc_html_e( 'Classic Editor & Classic Widgets Settings', 'classic-editor-and-classic-widgets' ); ?></h1>
	<form method="post" action="">
		<?php wp_nonce_field( CEW_BASENAME . '-settings', 'cew_settings_nonce' ); ?>
		<div class="tabs">
			<ul class="tabs-list">
				<li class="active-tab" data-id="general"><?php esc_html_e( 'General', 'classic-editor-and-classic-widgets' ); ?></li>
				<li data-id="user_roles"><?php esc_html_e( 'User Roles', 'classic-editor-and-classic-widgets' ); ?></li>
				<li data-id="advanced"><?php esc_html_e( 'Advanced', 'classic-editor-and-classic-widgets' ); ?></li>
			</ul>

			<div class="tabs-content">
				<section class="tab-panel">
					<?php load_template( CEW_PATH . '/templates/settings/general.php' ); ?>
				</section>
				<section class="tab-panel">
					<?php
					load_template( CEW_PATH . '/templates/settings/users.php' );
					cew_show_pro_overlay();
					?>
				</section>
				<section class="tab-panel">
					<?php
					load_template( CEW_PATH . '/templates/settings/advanced.php' );
					cew_show_pro_overlay();
					?>
				</section>
			</div>
		</div>

		<input type="hidden" name="save_settings">
		<input type="submit" name="submit" class="button button-primary" value="<?php esc_html_e( 'Save Settings', 'classic-editor-and-classic-widgets' ); ?>">
	</form>

	<?php if ( isset( $_POST['save_settings'] ) ) { ?>
		<div class="saved_notification">
			<p><?php esc_html_e( 'Changes saved!', 'classic-editor-and-classic-widgets' ); ?></p>
		</div>
	<?php } ?>
</div>
