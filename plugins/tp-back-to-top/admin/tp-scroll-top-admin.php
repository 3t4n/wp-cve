<?php
	if ( ! defined( 'ABSPATH' ) ) {
	    exit;
	}

	if(empty($_POST['tp_scroll_to_top_hidden'])){
		$tp_scroll_top_option_enable         = get_option( 'tp_scroll_top_option_enable' );
		$tp_scroll_top_visibility_fade_speed = get_option( 'tp_scroll_top_visibility_fade_speed' );
		$tp_scroll_top_scroll_fade_speed     = get_option( 'tp_scroll_top_scroll_fade_speed' );
		$tp_scroll_top_scroll_position       = get_option( 'tp_scroll_top_scroll_position' );
		$tp_scroll_top_scrollbg              = get_option( 'tp_scroll_top_scrollbg' );
		$tp_scroll_top_scrollbg_hover        = get_option( 'tp_scroll_top_scrollbg_hover' );
		$tp_scroll_top_scrollradious         = get_option( 'tp_scroll_top_scrollradious' );	
	}
	else{
		if($_POST['tp_scroll_to_top_hidden'] == 'Y'){
			$tp_scroll_top_visibility_fade_speed = sanitize_text_field( $_POST['tp_scroll_top_visibility_fade_speed'] );
			update_option('tp_scroll_top_visibility_fade_speed', $tp_scroll_top_visibility_fade_speed);
			$tp_scroll_top_scroll_fade_speed = sanitize_text_field( $_POST['tp_scroll_top_scroll_fade_speed'] );
			update_option('tp_scroll_top_scroll_fade_speed', $tp_scroll_top_scroll_fade_speed);	
			$tp_scroll_top_scroll_position = sanitize_text_field( $_POST['tp_scroll_top_scroll_position']);
			update_option('tp_scroll_top_scroll_position', $tp_scroll_top_scroll_position);
			$tp_scroll_top_option_enable = sanitize_text_field( $_POST['tp_scroll_top_option_enable'] );
			update_option('tp_scroll_top_option_enable', $tp_scroll_top_option_enable);
			$tp_scroll_top_scrollbg = sanitize_text_field( $_POST['tp_scroll_top_scrollbg'] );
			update_option('tp_scroll_top_scrollbg', $tp_scroll_top_scrollbg);
			$tp_scroll_top_scrollbg_hover = sanitize_text_field( $_POST['tp_scroll_top_scrollbg_hover'] );
			update_option('tp_scroll_top_scrollbg_hover', $tp_scroll_top_scrollbg_hover);
			$tp_scroll_top_scrollradious = sanitize_text_field( $_POST['tp_scroll_top_scrollradious'] );
			update_option('tp_scroll_top_scrollradious', $tp_scroll_top_scrollradious);
			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p>
			</div>
			<?php
		}
	}

	?>

	<div class="wrap">
		<h2><?php echo __( 'Scroll Top Settings', 'scrolltop' ); ?></h2>
		<form method="post" action="">
			<input type="hidden" name="tp_scroll_to_top_hidden" value="Y">
	        <?php 
	        	settings_fields( 'tp_scroll_to_top_plugin_options' );
				do_settings_sections( 'tp_scroll_to_top_plugin_options' );
			?>
	        <table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="tp_scroll_top_option_enable"><?php echo __( 'Show/Hide:', 'scrolltop' ); ?></label>
					</th>
					<td style="vertical-align:middle;">
						<select name="tp_scroll_top_option_enable">
							<option value="true" <?php if($tp_scroll_top_option_enable=='true') echo "selected"; ?> ><?php echo __( 'Show', 'scrolltop' ); ?></option>
							<option value="false" <?php if($tp_scroll_top_option_enable=='false') echo "selected"; ?> ><?php echo __( 'Hide', 'scrolltop' ); ?></option>
						</select><br>
						<span><?php echo __( 'Use Dropdown Menu to Select Scroll To Top enable/disable.', 'scrolltop' ); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tp_scroll_top_visibility_fade_speed"><?php echo __( 'Visibility Fade Speed:', 'scrolltop' ); ?></label>
					</th>
					<td style="vertical-align:middle;">
						<select name="tp_scroll_top_visibility_fade_speed">
							<option value="100" <?php if($tp_scroll_top_visibility_fade_speed=='100') echo "selected"; ?> ><?php echo __( '100', 'scrolltop' ); ?></option> 
							<option value="400" <?php if($tp_scroll_top_visibility_fade_speed=='400') echo "selected"; ?> ><?php echo __( '400', 'scrolltop' ); ?></option> 
							<option value="600" <?php if($tp_scroll_top_visibility_fade_speed=='600') echo "selected"; ?> ><?php echo __( '600', 'scrolltop' ); ?></option> 
							<option value="800" <?php if($tp_scroll_top_visibility_fade_speed=='800') echo "selected"; ?> ><?php echo __( '800', 'scrolltop' ); ?></option>
						</select><br>
						<span><?php echo __( 'Use Dropdown Menu to Select Scroll To Top enable/disable.', 'scrolltop' ); ?></span>
					</td>
				</tr>  
				<tr valign="top">
					<th scope="row">
						<label for="tp_scroll_top_scroll_fade_speed"><?php echo __( 'Scroll Speed:', 'scrolltop' ); ?></label>
					</th>
					<td style="vertical-align:middle;">
						<select name="tp_scroll_top_scroll_fade_speed">
							<option value="100" <?php if($tp_scroll_top_scroll_fade_speed=='100') echo "selected"; ?> ><?php echo __( '100', 'scrolltop' ); ?></option>
							<option value="400" <?php if($tp_scroll_top_scroll_fade_speed=='400') echo "selected"; ?> ><?php echo __( '400', 'scrolltop' ); ?></option>
							<option value="500" <?php if($tp_scroll_top_scroll_fade_speed=='500') echo "selected"; ?> ><?php echo __( '500', 'scrolltop' ); ?></option>
							<option value="600" <?php if($tp_scroll_top_scroll_fade_speed=='600') echo "selected"; ?> ><?php echo __( '600', 'scrolltop' ); ?></option>
							<option value="700" <?php if($tp_scroll_top_scroll_fade_speed=='700') echo "selected"; ?> ><?php echo __( '700', 'scrolltop' ); ?></option>
						</select><br>
						<span><?php echo __( 'Use Dropdown Menu to Select Scroll To Top enable/disable.', 'scrolltop' ); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tp_scroll_top_scrollbg"><?php echo __( 'Background Color:', 'scrolltop' ); ?></label>
					</th>
					<td style="vertical-align:middle;">
						<input size='10' name='tp_scroll_top_scrollbg' class='scroll_title_bg' type='text' id="scroll-bg" value='<?php echo esc_attr($tp_scroll_top_scrollbg); ?>' /><br />
						<span><?php echo __( 'Select back to top bg color.', 'scrolltop' ); ?></span>
					</td>
				</tr>	
				<tr valign="top">
					<th scope="row">
						<label for="tp_scroll_top_scrollbg_hover"><?php echo __( 'Hover Background Color:', 'scrolltop' ); ?></label>
					</th>
					<td style="vertical-align:middle;">
						<input  size='10' name='tp_scroll_top_scrollbg_hover' class='scroll_hover_bg' type='text' id="scroll-hoverbg" value='<?php echo esc_attr($tp_scroll_top_scrollbg_hover); ?>' /><br />
						<span><?php echo __( 'Select back to top hover bg color.', 'scrolltop' ); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tp_scroll_top_scrollradious"><?php echo __( 'Border Radius:', 'scrolltop' ); ?></label>
					</th>
					<td style="vertical-align:middle;">
						<input type="text" size='10' name='tp_scroll_top_scrollradious' class='scroll_hover_bg' type='text' id="scroll-radious" value='<?php echo esc_attr($tp_scroll_top_scrollradious); ?>' />%<br />
						<span><?php echo __( 'Select back to top border radius.default border radius 50%', 'scrolltop' ); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tp_scroll_top_scroll_position"><?php echo __( 'Position:', 'scrolltop' ); ?></label>
					</th>
					<td style="vertical-align:middle;">
						<select name="tp_scroll_top_scroll_position">
							<option value="bottom right" <?php if($tp_scroll_top_scroll_position=='bottom right') echo "selected"; ?> ><?php echo __( 'Bottom Right', 'scrolltop' ); ?></option>
							<option value="bottom center" <?php if($tp_scroll_top_scroll_position=='bottom center') echo "selected"; ?> ><?php echo __( 'Bottom Center', 'scrolltop' ); ?></option>
							<option value="bottom left" <?php if($tp_scroll_top_scroll_position=='bottom left') echo "selected"; ?> ><?php echo __( 'Bottom Left', 'scrolltop' ); ?></option>
							<option value="top left" <?php if($tp_scroll_top_scroll_position=='top left') echo "selected"; ?> ><?php echo __( 'Top Left', 'scrolltop' ); ?></option>
							<option value="top center" <?php if($tp_scroll_top_scroll_position=='top center') echo "selected"; ?> ><?php echo __( 'Top Center', 'scrolltop' ); ?></option> 
							<option value="top right" <?php if($tp_scroll_top_scroll_position=='top right') echo "selected"; ?> ><?php echo __( 'Top Right', 'scrolltop' ); ?></option> 
						</select><br>
						<span><?php echo __( 'Use dropdown menu to select scroll button position.', 'scrolltop' ); ?></span>
					</td>
				</tr>
	        </table>
			<p class="submit">
				<input class="button button-primary" type="submit" name="Submit" value="<?php _e( 'Save Changes' ) ?>" />
			</p>
		</form>
		<script>
			jQuery(document).ready(function($){
				jQuery('#scroll-bg, #scroll-hoverbg').wpColorPicker();
			});
		</script>
	</div>