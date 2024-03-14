<?php if ( defined( 'ABSPATH' ) ) { ?>
<div id="sarbacane_desktop_content">
	<p class="sarbacane_desktop_logo"></p>
	<div id="sarbacane_desktop_configuration">
		<form method="POST" action="" autocomplete="off">
			<div class="sarbacane_desktop_configuration_panel">
				<p class="sarbacane_desktop_configuration_title">
					<?php _e( 'Sarbacane\'s plugin setup', 'sarbacane-desktop' ) ?>
				</p>
				<p class="sarbacane_desktop_div_splitter"></p>
				<p class="sarbacane_desktop_configuration_subtitle">
					<?php _e( 'Lists synchronization', 'sarbacane-desktop' ) ?>
				</p>
				<p class="sarbacane_desktop_config_label">
					<input<?php if ( $sarbacane_users_list ) { ?> checked="checked"<?php } ?>
						   type="checkbox"
						   name="sarbacane_users_list"
						   id="sarbacane_users_list"
						   value="true"/>
					<label for="sarbacane_users_list">
						<?php _e( 'Synchronize a \'\'WordPress Users\'\' list', 'sarbacane-desktop' ) ?>
					</label>
					<br/>
					<?php _e( 'Creates a WordPress users list in Sarbacane with all users who have an account on your blog or website.', 'sarbacane-desktop' ) ?>
				</p>
				<p class="sarbacane_desktop_config_label">
					<input<?php if ( $sarbacane_news_list ) { ?> checked="checked"<?php } ?>
						   type="checkbox"
						   name="sarbacane_news_list"
						   onclick="sarbacaneNewsListChangePopup()"
						   id="sarbacane_news_list"
						   value="true"/>
					<label for="sarbacane_news_list">
						<?php _e( 'Automatically synchronize your subscriber list', 'sarbacane-desktop' ) ?>
					</label>
					<br/>
					<?php _e( 'Enables the widget menu on the left menu. It allows you to create an opt-in form which adds subscribers to a list in Sarbacane. This contact list will only be accessible in Sarbacane', 'sarbacane-desktop' ) ?>
				</p>
			</div>
			<div class="sarbacane_desktop_configuration_panel">
				<p class="sarbacane_desktop_div_splitter"></p>
				<p class="sarbacane_desktop_configuration_subtitle">
					<?php _e( 'Advanced settings', 'sarbacane-desktop' ) ?>
				</p>
				<p class="sarbacane_desktop_config_label">
					<input<?php if ( $sarbacane_theme_sync ) { ?> checked="checked"<?php } ?>
						   type="checkbox"
						   name="sarbacane_theme_sync"
						   id="sarbacane_theme_sync"
						   value="true"/>
					<label for="sarbacane_theme_sync">
						<?php _e( 'Synchronize your WordPress theme', 'sarbacane-desktop' ) ?>
					</label>
					<br/>
					<?php _e( 'Import the four main colors of your blog into a custom theme for the EmailBuilder', 'sarbacane-desktop' ) ?>
				</p>
				<p class="sarbacane_desktop_config_label">
					<input<?php if ( $sarbacane_blog_content ) { ?> checked="checked"<?php } ?>
						   type="checkbox"
						   name="sarbacane_blog_content"
						   id="sarbacane_blog_content"
						   value="true"/>
					<label for="sarbacane_blog_content">
						<?php _e( 'Synchronize blog content', 'sarbacane-desktop' ) ?>
					</label>
					<br/>
					<?php _e( 'Import all your post content in the EmailBuilder\'s blocks', 'sarbacane-desktop' ) ?>
				</p>
				<p class="sarbacane_desktop_config_label" style="display:none;">
					<input<?php if ( $sarbacane_media_content ) { ?> checked="checked"<?php } ?>
						   type="checkbox"
						   name="sarbacane_media_content"
						   id="sarbacane_media_content"
						   value="true"/>
					<label for="sarbacane_media_content">
						<?php _e( 'Synchronize media library', 'sarbacane-desktop' ) ?>
					</label>
					<br/>
					<?php _e( 'Import elements from your WordPress media library into the EmailBuilder image blocks', 'sarbacane-desktop' ) ?>
				</p>
				<p class="sarbacane_desktop_config_label">
					<input<?php if ( $sarbacane_rss_data ) { ?> checked="checked"<?php } ?>
						   type="checkbox"
						   name="sarbacane_rss_data"
						   id="sarbacane_rss_data"
						   value="true"/>
					<label for="sarbacane_rss_data">
						<?php _e( 'Synchronize RSS data', 'sarbacane-desktop' ) ?>
					</label>
					<br/>
					<?php _e( 'Add your blog as a source for the EmailBuilder\'s RSS module', 'sarbacane-desktop' ) ?>
				</p>
			</div>
			<div id="sarbacane_desktop_configuration_footer">
				<p class="sarbacane_desktop_configuration_footer_help sarbacane_desktop_configuration_button_container">
					<input type="submit"
						   class="sarbacane_desktop_configuration_button sarbacane_desktop_configuration_button_green"
						   value="<?php esc_attr_e( 'Save', 'sarbacane-desktop' ) ?>"/>
				</p>
			</div>
			<input type="hidden" name="sarbacane_config" value="1"/>
			<?php wp_nonce_field( 'sarbacane_config', 'sarbacane_token' ) ?>
		</form>
	</div>
	<div id="sarbacane_desktop_help">
		<div class="sarbacane_desktop_help_title">
			<?php _e( 'Need help?', 'sarbacane-desktop' ) ?>
		</div>
		<p>
			<?php _e( 'Email', 'sarbacane-desktop' ) ?> : <?php _e( 'support@sarbacane.com', 'sarbacane-desktop' ) ?>
			<br/>
			<?php _e( 'Phone', 'sarbacane-desktop' ) ?> : <?php _e( '+33(0) 328 328 040', 'sarbacane-desktop' ) ?>
		</p>
		<p>
			<?php _e( 'For more informations, please take a look to our website', 'sarbacane-desktop' ) ?> :
			<br/>
			<a href="<?php _e( 'http://sarbacane.com/?utm_source=module-wordpress&utm_medium=plugin&utm_content=lien-sarbacane&utm_campaign=wordpress', 'sarbacane-desktop' ); ?>">
				<?php _e( 'http://www.sarbacane.com', 'sarbacane-desktop' ) ?>
			</a>
		</p>
	</div>
</div>
<div class="sarbacane_desktop_popup">
	<div class="sarbacane_desktop_popup_background"></div>
	<div class="sarbacane_desktop_popup_content">
		<p><?php _e( 'Careful, if you deactivate this list, the \'Contact form\' widget and the associated list in Sarbacane will be deactivated too. Are you sure you wish to deactivate the list?', 'sarbacane-desktop' ) ?></p>
		<p>
			<input type="button"
				   onclick="sarbacaneNewsListChangePopupOk()"
				   class="sarbacane_desktop_configuration_button sarbacane_desktop_configuration_button_green"
				   value="<?php esc_attr_e( 'Yes', 'sarbacane-desktop' ) ?>"/>
			<input type="button"
				   onclick="sarbacaneNewsListChangePopupNo()"
				   class="sarbacane_desktop_configuration_button"
				   value="<?php esc_attr_e( 'No', 'sarbacane-desktop' ) ?>"/>
		</p>
	</div>
</div>
<?php } ?>
