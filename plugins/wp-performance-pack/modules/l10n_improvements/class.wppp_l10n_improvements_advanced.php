<?php

class WPPP_L10n_Improvements_Advanced extends WPPP_Admin_Renderer {
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();
	}

	public function add_help_tab () {
		$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_l10n',
			'title'	=> __( 'Overview', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( 'WPPP offers different options to significantly improve localization performance. These only affect localization of WordPress core, themes and plugins, not translation of content (e.g. by using plugins like WPML).', 'wp-performance-pack' ) . '</p>',
		) );
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_gettext',
			'title'	=> __( 'GNU gettext', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( 'Using native GNU gettext is the fastest way for localization. It requires the PHP gettext extension to be installed and your <em>wp-content</em> folder has to be writable. WPPP will store copies of translation files in the folder <em>wp-content/wppp/localize</em>.', 'wp-performance-pack' ) . '</p>',
		) );
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_moreader',
			'title'	=> __( 'MO reader', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( 'The alternative MO reader is a complete rewrite of the default MO reader. It loads translation files only when needed and only the needed translations. This improves memory usage and localization performance significantly. For best performance activate caching. This requires a persistent Object Cache to be effective.', 'wp-performance-pack' ) . '</p>',
		) );
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_jit',
			'title'	=> __( 'JIT', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( 'WordPress translates many texts by default, regardless if they are used or not. JIT script localization, as the name suggests, delays localizing default scripts to when (and if) they are used, thus reducing translation calls and improving performance a bit.', 'wp-performance-pack' ) . '</p>',
		) );
		$screen->add_help_tab( array(
			'id'	=> 'wppp_advanced_backend',
			'title'	=> __( 'Backend localization', 'wp-performance-pack' ),
			'content'	=> '<p>' . __( "The fastest option is to not localize WordPress. This might not be an option for the Frontend, but if you don't mind an english Backend, you can disable Backend localization. By activating <em>Allow user override</em> you can allow your users to reenable localization.", 'wp-performance-pack' ) . '</p>',
		) );
	}

	public function render_options () {
	?>
		<h3 class="title"><?php _e( 'Improve localization performance', 'wp-performance-pack' ); ?></h3>
		<table class="form-table" style="clear:none">
			<tr valign="top">
				<th scope="row"><?php _e( 'Use gettext', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_switchButton( 'use_native_gettext', $this->is_native_gettext_available() != 0 ); ?>
					<p class="description"><?php _e( 'Use php gettext extension for localization. This is in most cases the fastest way to localize your blog.', 'wp-performance-pack' ); ?></p>
					<?php $this->do_hint_gettext( true ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:15em"><?php _e( 'Use alternative MO reader', 'wp-performance-pack' ); ?></th>
				<td>
					<?php $this->e_switchButton( 'use_mo_dynamic' ); ?>
					<p class="description"><?php _e( 'Alternative MO reader using on demand translation and loading of localization files (.mo). Faster and less memory intense than the default WordPress implementation.' ,'wp-performance-pack' ); ?></p>
					<br/>
					<?php $this->e_checkbox( 'mo-caching', 'mo_caching', __( 'Use caching', 'wp-performance-pack' ) ); ?>
					<p class="description"><?php _e( "Cache translations using WordPress' Object Cache API", 'wp-performance-pack' ); ?></p>
					<?php $this->do_hint_caching(); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php _e( 'Use JIT localize', 'wp-performance-pack' ); ?>
				</th>
				<td>
					<?php $this->e_switchButton( 'use_jit_localize', !$this->is_jit_available() ); ?>
					<p class="description"><?php _e( 'Just in time localization of scripts.', 'wp-performance-pack' ); ?></p>
					<?php $this->do_hint_jit( true ); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<?php _e( 'Disable Backend localization', 'wp-performance-pack' ); ?>
				</th>
				<td>
					<?php $this->e_switchButton( 'disable_backend_translation' ); ?>
					<p class="description"><?php _e('Disables localization of Backend texts.', 'wp-performance-pack' ); ?></p>
					<br/>
					<?php $this->e_checkbox( 'allow-user-override', 'dbt_allow_user_override', __( 'Allow user override', 'wp-performance-pack' ) ); ?>
					<p class="description"><?php  _e( 'Allow users to reactivate Backend localization in their profile settings.', 'wp-performance-pack' ); ?></p>
					<br/>
					<p>
						<?php _e( 'Default user language:', 'wp-performance-pack' ); ?>&nbsp;
						<label for="user-default-english"><input id="user-default-english" type="radio" <?php $this->e_opt_name( 'dbt_user_default_translated' ); ?> value="false" <?php $this->e_checked( 'dbt_user_default_translated', false ); ?>><?php _e( 'English', 'wp-performance-pack' ); ?></label>&nbsp;
						<label for="user-default-translated"><input id="user-default-translated" type="radio" <?php $this->e_opt_name( 'dbt_user_default_translated' ); ?> value="true" <?php $this->e_checked( 'dbt_user_default_translated' ); ?>><?php _e( 'Blog language', 'wp-performance-pack' ); ?></label>
					</p>
					<p class="description"><?php _e( "Default Backend language for new and existing users, who haven't updated their profile yet.", 'wp-performance-pack' ); ?></p>
				</td>
			</tr>
		</table>
		<hr/>
	<?php
	}
}