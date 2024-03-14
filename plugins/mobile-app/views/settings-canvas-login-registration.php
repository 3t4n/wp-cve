<?php
	$editor_list = array(
		'canvas-login-editor'        => array(
			'name' => __( 'Login', 'canvas' ),
			'mode' => 'php',
			'option' => 'generated-existing-login-html-template',
		),
		'canvas-registration-editor' => array(
			'name' => __( 'Registration', 'canvas' ),
			'mode' => 'php',
			'option' => 'generated-existing-registration-html-template',
		),
		'canvas-common-css-editor'   => array(
			'name' => __( 'Common CSS', 'canvas' ),
			'mode' => 'css',
			'option' => array( 'generated-existing-css-template', 'login_register_css' )
		),
	);
?>

<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title"><?php esc_html_e( 'Template editor', 'canvas' ); ?></div>
	<div class="cas--settings__content">
		<div class="canvas-code-editor">
			<label id="canvas-code-editor-label" for="canvas-code-editor"><?php esc_html_e( 'Select a template:', 'canvas' ); ?>
			<select id="canvas-code-editor" name="canvas-code-editor">
				<?php foreach ( $editor_list as $editor_id => $editor_meta ) : ?>
					<option data-mode="<?php echo esc_attr( $editor_meta['mode'] ); ?>" value="<?php echo esc_attr( $editor_id ); ?>"><?php echo esc_html( $editor_meta['name'] ); ?></option>
				<?php endforeach; ?>
			</select>
			</label>
		
			<?php foreach ( $editor_list as $editor_id => $editor_meta ) :
				if ( is_array( $editor_meta['option'] ) ) {
					$template_string = '';
					foreach ( $editor_meta['option'] as $mul_option ) {
						$template_string .= Canvas::get_option( $mul_option );
					}
				} else {
					$template_string = Canvas::get_option( $editor_meta['option'] );
				}
				$template_string = stripslashes( $template_string );
			?>
				<textarea id="<?php echo esc_attr( $editor_id ); ?>" name="<?php echo esc_attr( $editor_id ); ?>" data-mode="<?php echo esc_attr( $editor_meta['mode'] ); ?>" style="display: none;"><?php echo $template_string; ?></textarea>
			<?php endforeach; ?>
		
			<a class="button canvas-restore-default-templates" href="<?php echo esc_url( admin_url( 'admin.php?page=canvas&tab=canvas-login-registration&canvas-delete-editor-templates' ) ); ?>">Restore default templates</a>
		</div>
	</div>
</div>

<!-- Custom layout -->
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title"><?php esc_html_e( 'Custom layout', 'canvas' ) ?></div>
	<div class="cas--settings__content">
		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Logo max width', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Accept any unit (px, %, em...)', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<input
					name="canvas_login_register_logo_max_width"
					type="text"
					id="canvas_login_register_logo_max_width"
					value="<?php echo esc_attr( Canvas::get_option( 'login_register_logo_max_width', '100%' ) ); ?>">
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Logo', 'canvas' ); ?></span>
				</label>
			</div>
			<div class="cas--settings__layout-row-item">
				<?php
					$custom_login_register_logo = Canvas::get_option( 'login_register_logo' );
					echo CanvasViews::render_image_uploader_field( 'canvas-login-register-logo', 'canvas_login_register_logo', $custom_login_register_logo );
				?>
			</div>
		</div>

	</div>
</div>

<!-- Custom content -->
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title"><?php esc_html_e( 'Custom content', 'canvas' ) ?></div>
	<div class="cas--settings__content">

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Redirect URL', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Leave blank to redirect to Home page', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_login_register_redirect_url" type="url" id="canvas_login_register_redirect_url" value="<?php echo esc_attr( Canvas::get_option( 'login_register_redirect_url', add_query_arg( array( 'login_successful' => true ), home_url() ) ) ); ?>">
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'User role', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Select the default user role after registering from the app', 'canvas' ); ?></p>
			</div>
			<div class="cas--settings__layout-row-item">
				<?php
					global $wp_roles;
					$roles              = $wp_roles->get_names();
					$selected_user_role = Canvas::get_option( 'user_role', 'app_user' );
				?>
				<select class="canvas-user-role" name="canvas_user_role" id="canvas_user_role">
					<?php foreach ( $roles as $role_key => $role_name ) : ?>
						<?php $selected_txt = $selected_user_role === $role_key ? ' selected' : ''; ?>
						<option value="<?php echo $role_key; ?>"<?php echo $selected_txt; ?>><?php echo $role_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Enabled registration?', 'canvas' ); ?></span>
				</label>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_enabled_registration" type="checkbox" id="canvas_enabled_registration" value="1"
					<?php
					if ( Canvas::get_option( 'enabled_registration', false ) ) {
						echo 'checked="checked"';
					};
					?>
				/>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Keep users logged-in?', 'canvas' ); ?></span>
				</label>
			</div>
			<div class="cas--settings__layout-row-item">
				<input name="canvas_forever_logged_in" type="checkbox" id="canvas_forever_logged_in" value="1"
					<?php
					if ( Canvas::get_option( 'forever_logged_in', false ) ) {
						echo 'checked="checked"';
					};
					?>
				>
				<span><?php esc_html_e( 'Mark this option to prevent users from having to login every time they open the app', 'canvas' ); ?></span>
			</div>
		</div>

		<div class="clearfix"></div>
	</div>
</div>
