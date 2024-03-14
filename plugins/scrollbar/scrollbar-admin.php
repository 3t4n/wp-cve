<?php

	// Check if the code is being accessed directly, and exit if not
	if ( ! defined( 'ABSPATH' ) ) exit;

	if(empty($_POST['scrollbar_wp_hidden'])){
		$themepoints_scrollbar_colors   = get_option( 'themepoints_scrollbar_colors' );
		$themepoints_scrollbar_width    = get_option( 'themepoints_scrollbar_width' );
		$themepoints_scrollbar_radius   = get_option( 'themepoints_scrollbar_radius' );
		$themepoints_scrollbar_border   = get_option( 'themepoints_scrollbar_border' );
		$themepoints_scrollbar_speed    = get_option( 'themepoints_scrollbar_speed' );
		$themepoints_scrollbar_autohide = get_option( 'themepoints_scrollbar_autohide' );
	}else {
		if($_POST['scrollbar_wp_hidden'] == 'Y'){
			$themepoints_scrollbar_colors = stripslashes_deep($_POST['themepoints_scrollbar_colors']);
			update_option('themepoints_scrollbar_colors', $themepoints_scrollbar_colors);

			$themepoints_scrollbar_width = stripslashes_deep($_POST['themepoints_scrollbar_width']);
			update_option('themepoints_scrollbar_width', $themepoints_scrollbar_width);

			$themepoints_scrollbar_radius = stripslashes_deep($_POST['themepoints_scrollbar_radius']);
			update_option('themepoints_scrollbar_radius', $themepoints_scrollbar_radius);

			$themepoints_scrollbar_border = stripslashes_deep($_POST['themepoints_scrollbar_border']);
			update_option('themepoints_scrollbar_border', $themepoints_scrollbar_border);

			$themepoints_scrollbar_speed = stripslashes_deep($_POST['themepoints_scrollbar_speed']);
			update_option('themepoints_scrollbar_speed', $themepoints_scrollbar_speed);

			$themepoints_scrollbar_autohide = stripslashes_deep($_POST['themepoints_scrollbar_autohide']);
			update_option('themepoints_scrollbar_autohide', $themepoints_scrollbar_autohide);
			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p></div>
			<?php
		}
	}
?>

<style>
    .wrap.scrollbar-wp {
        max-width: 800px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .wrap.scrollbar-wp .updated {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .wrap.scrollbar-wp label {
        font-weight: bold;
    }
    .wrap.scrollbar-wp input[type="text"] {
        padding: 5px;
        margin-top: 0px;
        box-sizing: border-box;
    }
</style>


<div class="wrap scrollbar-wp">
	<h2><?php esc_html_e( 'Scrollbar Option Settings', 'tpscrollbars' ); ?></h2>
	<p><?php echo wp_kses_post( 'We would greatly appreciate it if you could take a moment to share your thoughts by giving us a rating in the <a href="https://wordpress.org/support/plugin/scrollbar/reviews/#new-post" target="_blank"><strong>WordPress.org</strong></a>?', 'tpscrollbars' ); ?></p>
	<form method="post" action="">
		<input type="hidden" name="scrollbar_wp_hidden" value="Y">
		<?php
			settings_fields( 'scrollbar_wp_plugin_options' );
			do_settings_sections( 'scrollbar_wp_plugin_options' );
		?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="themepoints_scrollbar_colors"><?php esc_html_e( 'Scrollbar Color:', 'tpscrollbars' ); ?></label>
				</th>
				<td style="vertical-align:middle;">
					<input size='10' name='themepoints_scrollbar_colors' class='scrollbar-color' type='text' id="scrollbar_color" value='<?php echo esc_attr($themepoints_scrollbar_colors); ?>' /><br />
				<span><?php esc_html_e('Select Scrollbar Color.', 'tpscrollbars'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="themepoints_scrollbar_width"><?php esc_html_e( 'Scrollbar Width:', 'tpscrollbars' ); ?></label>
				</th>
				<td style="vertical-align:middle;">
					<input  size='10' name='themepoints_scrollbar_width' class='scrollbar-width' type='number' id="scrollbar_width" value='<?php if ( !empty( $themepoints_scrollbar_width ) ) echo esc_attr($themepoints_scrollbar_width); else echo ''; ?>' />px<br />
				<span><?php esc_html_e('Select scrollbar width. Default Width:5.', 'tpscrollbars'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="themepoints_scrollbar_radius"><?php esc_html_e( 'Scrollbar Border Radius:', 'tpscrollbars' ); ?></label>
				</th>
				<td style="vertical-align:middle;">
					<input  size='10' name='themepoints_scrollbar_radius' class='scrollbar-radius' type='number' id="scrollbar_radius" value='<?php if ( !empty( $themepoints_scrollbar_radius ) ) echo esc_attr($themepoints_scrollbar_radius); else echo ''; ?>' />px<br />
				<span><?php esc_html_e('Select scrollbar border radius. Default border-radius:0.', 'tpscrollbars'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="themepoints_scrollbar_speed"><?php esc_html_e( 'Scrollbar Scroll Speed:', 'tpscrollbars' ); ?></label>
				</th>
				<td style="vertical-align:middle;">
					<input  size='10' name='themepoints_scrollbar_speed' class='scrollbar-speed' type='number' id="scrollbar_speed" value='<?php if ( !empty( $themepoints_scrollbar_speed ) ) echo esc_attr($themepoints_scrollbar_speed); else echo ''; ?>' /><br />
				<span><?php esc_html_e('Select scrollbar scroll speed. Default Speed:60.', 'tpscrollbars'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="themepoints_scrollbar_border"><?php esc_html_e( 'Scrollbar Border:', 'tpscrollbars' ); ?></label>
				</th>
				<td style="vertical-align:middle;">
					<input  size='10' name='themepoints_scrollbar_border' class='scrollbar-border' type='number' id="scrollbar_border" value='<?php if ( !empty( $themepoints_scrollbar_border ) ) echo esc_attr($themepoints_scrollbar_border); else echo ''; ?>' />px<br />
				<span><?php esc_html_e('Select scrollbar border. Default Border:0.', 'tpscrollbars'); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label style="padding-left:10px;" for="themepoints_scrollbar_autohide"><?php esc_html_e( 'Auto Hide Mode:', 'tpscrollbars' ); ?></label>
				</th>
				<td style="vertical-align:middle;">
					<select class="timezone_string" name="themepoints_scrollbar_autohide">
						<option value="true" <?php if($themepoints_scrollbar_autohide=='true') echo "selected"; ?> ><?php esc_html_e( 'Yes', 'tpscrollbars' ); ?></option>
						<option value="false" <?php if($themepoints_scrollbar_autohide=='false') echo "selected"; ?> ><?php esc_html_e( 'No', 'tpscrollbars' ); ?></option>
					</select><br/>
					<span><?php esc_html_e('Enable/Disable scrollbar Auto Hide Mode.', 'tpscrollbars'); ?></span>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes', 'tpscrollbars' ); ?>" />
		</p>
	</form>
	<script>
		jQuery(document).ready(function(jQuery){
			jQuery('#scrollbar_color').wpColorPicker();
		});
	</script>
</div>