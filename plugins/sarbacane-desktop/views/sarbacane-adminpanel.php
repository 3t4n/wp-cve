<?php if ( defined( 'ABSPATH' ) ) { ?>
<div id="sarbacane_desktop_content">
	<p class="sarbacane_desktop_logo"></p>
	<div id="sarbacane_desktop_configuration">
		<div class="sarbacane_desktop_configuration_panel">
			<p class="sarbacane_desktop_configuration_title">
				<?php _e( 'Configuration', 'sarbacane-desktop' ) ?>
			</p>
			<p class="sarbacane_desktop_div_splitter"></p>
			<form method="POST" autocomplete="off">
				<label for="url" class="sarbacane_desktop_configuration_label">
					<?php _e( 'URL to paste in Sarbacane', 'sarbacane-desktop' ) ?> :
				</label>
				<input type="text"
					   class="sarbacane_desktop_configuration_input"
					   id="url"
					   name="url"
					   value="<?php echo get_site_url() . '/' ?>"
					   readonly="readonly"
					   onclick="this.select()"/>
				<p class="sarbacane_desktop_div_splitter"></p>
				<label for="key" class="sarbacane_desktop_configuration_label">
					<?php _e( 'Synchronization key to enter in Sarbacane', 'sarbacane-desktop' ) ?> :
					<span class="sarbacane_desktop_connection_status">
						<?php if ( $is_connected ) { ?>
						<span class='sarbacane_desktop_connection_ok'><?php _e( 'Connected', 'sarbacane-desktop' ) ?></span>
						<?php } else { ?>
						<span class='sarbacane_desktop_connection_nok'><?php _e( 'Disconnected', 'sarbacane-desktop' ) ?></span>
						<?php } ?>
						<?php if ( $is_failed ) { ?>
						<span> - <?php _e( 'Please generate a new key', 'sarbacane-desktop' ) ?></span>
						<?php } ?>
					</span>
				</label>
				<input type="text"
					   class="sarbacane_desktop_configuration_input"
					   id="key"
					   name="key"
					   value="<?php echo $key ?>"
					   readonly="readonly"
					   onclick="this.select()"/>
				<input type="hidden" name="sarbacane_redo_token" id="sarbacane_redo_token" value="1"/>
				<?php wp_nonce_field( 'sarbacane_redo_token', 'sarbacane_token' ) ?>
				<p>
					<?php if ( $key != '' ) { ?>
					<input type="submit"
						   class="sarbacane_desktop_configuration_button"
						   value="<?php esc_attr_e( 'Generate another key', 'sarbacane-desktop' ) ?>"/>
					<?php } else { ?>
					<input type="submit"
						   class="sarbacane_desktop_configuration_button"
						   value="<?php esc_attr_e( 'Generate a key', 'sarbacane-desktop' ) ?>"/>
					<?php }?>
				</p>
			</form>
		</div>
		<div id="sarbacane_desktop_configuration_footer">
			<p class="sarbacane_desktop_configuration_footer_help sarbacane_desktop_configuration_button_container">
				<?php if ( $sd_list_news ) { ?>
					<input type="button"
						   onclick="sarbacaneGoWidget()"
						   class="sarbacane_desktop_configuration_button sarbacane_desktop_configuration_button_green"
						   value="<?php esc_attr_e( 'Setup the widget', 'sarbacane-desktop' ) ?>"/>
				<?php }?>
			</p>
		</div>
	</div>
	<div id="sarbacane_desktop_help">
		<div class="sarbacane_desktop_help_title">
			<?php _e( 'How to set up the module?', 'sarbacane-desktop' ) ?>
		</div>
		<p><?php _e( 'Go in the plugins menu of Sarbacane and activate wordpress plugin', 'sarbacane-desktop' ) ?></p>
		<p class="sarbacane_desktop_help_subtitle">
			<?php _e( 'Learn more', 'sarbacane-desktop' ) ?> :
			<a href="<?php _e( 'https://www.sarbacane.com/ws/soft-redirect.asp?key=9Y4OtEZzaz&com=WordpressInfo', 'sarbacane-desktop' ) ?>">
				<?php _e( 'Take a look at the help section online', 'sarbacane-desktop' ) ?>
			</a>
		</p>
		<p class="sarbacane_desktop_div_splitter"></p>
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
			<a href="<?php _e( 'http://sarbacane.com/?utm_source=module-wordpress&utm_medium=plugin&utm_content=lien-sarbacane&utm_campaign=wordpress', 'sarbacane-desktop' ) ?>">
				<?php _e( 'http://www.sarbacane.com', 'sarbacane-desktop' ) ?>
			</a>
		</p>
	</div>
</div>
<script type="text/javascript">
	function sarbacaneGoWidget(){
		document.location = "admin.php?page=wp_news_widget";
	}
</script>
<?php } ?>
