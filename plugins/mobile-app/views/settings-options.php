<?php
/** @var string $active_tab */
?>
<div class="cas--settings cas-settings--wide canvas-block">
	<div class="cas--settings__title"><?php echo CanvasAdmin::$admin_pages[ $active_tab ]; ?></div>
	<div class="cas--settings__content">
		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Enable a different theme', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Use a different theme for your app', 'canvas' ); ?></p>
			</div>

			<div class="cas--settings__layout-row-item">
				<?php
				$current_theme_name = Canvas::get_option( Canvas::THEME_OPTION, '' );
				if ( empty( $current_theme_name ) ) {
					$current            = wp_get_theme();
					$current_theme_name = $current->get( 'TextDomain' );
				}

				$themes_list = apply_filters( 'canvas_themes', wp_get_themes() );
				?>
				<div>
					<input name="different_theme_for_app" type="checkbox" id="canvas_different_theme" value="1"<?php checked( Canvas::get_option( Canvas::THEME_DIFFERENT ) ); ?>>
					<span for="canvas_different_theme">Use a different theme for your mobile app</span>
				</div>
				<p id="theme_choice_block"
				<?php
				if ( ! Canvas::get_option( Canvas::THEME_DIFFERENT ) ) {
					echo ' class="canvas_hidden"';
				}
				?>
				>
					<select id="theme" name="theme">
						<?php foreach ( $themes_list as $key => $value ) { ?>
							<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $current_theme_name, $key, true ); ?>><?php echo esc_html( $value ); ?></option>
							<?php
						}
						?>
					</select>
					<span id="canvas_theme_link" style="display: block; margin-top: 8px;"><a class="button button-large" href="<?php echo Canvas::get_theme_customize_url(); ?>">Customize the theme</a></span>
					<span id="canvas_theme_warning" class="canvas_hidden canvas_warning">Please save settings before customizing this theme.</span>
				</p>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'WordPress Admin bar', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Control the visibility of the admin bar in the app', 'canvas' ); ?></p>
			</div>

			<div class="cas--settings__layout-row-item">
				<input
					name="wpadminbar_hide"
					type="checkbox"
					id="wpadminbar_hide"
					value="1"
					<?php checked( Canvas::get_option( 'wpadminbar_hide', false ) ); ?>>
				<span for="wpadminbar_hide">Hide WordPress Admin bar</span>
			</div>
		</div>

		<div class="cas--settings__layout-row">
			<div class="cas--settings__layout-row-item">
				<label for="">
					<span class="cas--label__text"><?php esc_html_e( 'Identify the app using', 'canvas' ); ?></span>
				</label>
				<p class="cas--description"><?php esc_html_e( 'Control the visibility of the admin bar in the app', 'canvas' ); ?></p>
			</div>

			<div class="cas--settings__layout-row-item">
				<?php $by_get_param = Canvas::identify_app_by_get_param(); ?>
				<div class="cas--radio-group">
					<input name="identify_app_by_get_param" type="radio" id="identify_app_0" value="0"
						<?php checked( ! $by_get_param ); ?>>
					<span for="identify_app_0">User Agent</span>
					</div>
				<div class="cas--radio-group">
					<input name="identify_app_by_get_param" type="radio" id="identify_app_1" value="1"
						<?php checked( $by_get_param ); ?>>
					<span for="identify_app_1">Get parameter</span>
				</div>
			</div>
		</div>
	</div>
</div>

