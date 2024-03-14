<?php

/*******************************************
* bbp last post settings page
*******************************************/

function rlp_settings_page()
{
	global $rlp_options;
		
	?>
	<div class="wrap">
		<div id="upb-wrap" class="upb-help">
			<h2><?php _e('Last Post Heading Setting', 'bbp-last-post'); ?></h2>
			<?php
			if ( ! isset( $_REQUEST['updated'] ) )
				$_REQUEST['updated'] = false;
			?>
			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Name saved', 'bbp-last-post'); ?> ); ?></strong></p></div>
			<?php endif; ?>
			
			
			<table class="form-table">
			<tr>
		
		<td>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="9NKN2K8SXGS9Q">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>
</td><td>
<?php _e("If you find this plugin useful, please consider donating just a couple of pounds to help me develop and maintain it. You support will be appreciated", 'bbp-last-post'); ?>
		
	</td>
	</tr>
	</table>			
			
			<form method="post" action="options.php">

				<?php settings_fields( 'rlp_settings_group' ); ?>
								
				<table class="form-table">
					
					<tr valign="top">
						<th colspan="2"><p> <?php _e('This plugin changes the freshness (eg. 4 hours ago) display on forums and topics to date and time of last post.
						The name below sets the heading of this column.', 'bbp-last-post') ?> </p></th>
					</tr>
					
					<tr>
										
					<!-------------------------------Heading---------------------------------------->
					
					<tr valign="top">
						<th colspan="2"><h3><?php _e('Heading', 'bbp-last-post'); ?></h3></th>
					</tr>
					
					
					
					<tr valign="top">
					<th><?php _e('Heading Name', 'bbp-last-post'); ?></th>
					<td>
						<input id="rlp_settings[heading_label]" class="large-text" name="rlp_settings[heading_label]" type="text" value="<?php echo isset( $rlp_options['heading_label'] ) ? esc_html( $rlp_options['heading_label'] ) : '';?>" /><br/>
						<label class="description" for="rlp_settings[heading_label]"><?php _e( 'Enter the heading description eg "Last Post", "Last updated", "Freshness" "Last activity" etc.', 'bbp-last-post' ); ?></label><br/>
					</td>
					</tr>
										
					
				
				</table>
				
				<!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Heading Name', 'bbp-last-post' ); ?>" />
				</p>
								
				
			</form>
		</div><!--end sf-wrap-->
	</div><!--end wrap-->
		
	<?php
}

// register the plugin settings
function rlp_register_settings() {

	// create whitelist of options
	register_setting( 'rlp_settings_group', 'rlp_settings' );
	}
//call register settings function
add_action( 'admin_init', 'rlp_register_settings' );


function rlp_settings_menu() {

	// add settings page
	add_submenu_page('options-general.php', __('bbp last post', 'bbp-last-post'), __('bbp last post', 'bbp-last-post'), 'manage_options', 'bbp-last-post-settings', 'rlp_settings_page');
}
add_action('admin_menu', 'rlp_settings_menu');

