<?php

defined('ABSPATH') or die("No script kiddies please!");

	wp_nonce_field( 'NKgoogleanalytics_meta_box', 'NKgoogleanalytics_meta_box_nonce' );

?>

<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e( 'Tracking code location', 'NKgoogleanalytics' ); ?></th>
	<td>
	<select name="nkweb_code_in_head">
	<option value="default"<?php if ( get_post_meta( $post->ID, 'nkweb_code_in_head', true) == "default") { echo ' selected="selected"'; } ?>><?php _e( 'Default', 'NKgoogleanalytics' ); ?></option>
	<option value="true"<?php if ( get_post_meta( $post->ID, 'nkweb_code_in_head', true) == "true") { echo ' selected="selected"'; } ?>><?php _e( 'Head', 'NKgoogleanalytics' ); ?></option>
	<option value="false"<?php if ( get_post_meta( $post->ID, 'nkweb_code_in_head', true) == "false") { echo ' selected="selected"'; } ?>><?php _e( 'End of the page', 'NKgoogleanalytics' ); ?></option>
	</select>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row"><?php _e( 'Javascript code executed before pageview', 'NKgoogleanalytics' ); ?></th>
	<td>
	<select name="nkweb_Use_Custom_js">
	<option name="nkweb_Use_Custom_js" value="default"<?php if ( get_post_meta( $post->ID, 'nkweb_Use_Custom_js', true) == "default") { echo ' selected="selected"'; } ?>><?php _e( 'Default, use global settings', 'NKgoogleanalytics' ); ?></option>
	<option name="nkweb_Use_Custom_js" value="true"<?php if ( get_post_meta( $post->ID, 'nkweb_Use_Custom_js', true) == "true") { echo ' selected="selected"'; } ?>><?php _e( 'Yes, use the following code', 'NKgoogleanalytics' ); ?></option>
	<option name="nkweb_Use_Custom_js" value="false"<?php if ( get_post_meta( $post->ID, 'nkweb_Use_Custom_js', true) == "false") { echo ' selected="selected"'; } ?>><?php _e( 'No, do not use any', 'NKgoogleanalytics' ); ?></option>
	</select><br>
	<textarea name="nkweb_Custom_js" class="nk-textarea<?php if (get_post_meta( $post->ID, 'nkweb_Use_Custom_js', true) != "true"){ echo ' input-disabled" readonly="readonly'; } ?>"><?php echo get_post_meta( $post->ID, 'nkweb_Custom_js', true); ?></textarea>
	<div class="description"><?php _e('Get elements from the page and do operations before passing them as values, dimensions or metrics', 'NKgoogleanalytics'); ?></div>
	</td>
	</tr>
	
	<tr valign="top">
	<th scope="row"><?php _e( 'Other values, dimensions and metrics', 'NKgoogleanalytics' ); ?></th>
	<td>
	<select name="nkweb_Use_Custom_Values">
	<option name="nkweb_Use_Custom_Values" value="default"<?php if ( get_post_meta( $post->ID, 'nkweb_Use_Custom_Values', true) == "default") { echo ' selected="selected"'; } ?>><?php _e( 'Default, use global settings', 'NKgoogleanalytics' ); ?></option>
	<option name="nkweb_Use_Custom_Values" value="true"<?php if ( get_post_meta( $post->ID, 'nkweb_Use_Custom_Values', true) == "true") { echo ' selected="selected"'; } ?>><?php _e( 'Yes, use the following code', 'NKgoogleanalytics' ); ?></option>
	<option name="nkweb_Use_Custom_Values" value="false"<?php if ( get_post_meta( $post->ID, 'nkweb_Use_Custom_Values', true) == "false") { echo ' selected="selected"'; } ?>><?php _e( 'No, do not use any', 'NKgoogleanalytics' ); ?></option>
	</select><br>
	<textarea name="nkweb_Custom_Values" class="nk-textarea<?php if (get_post_meta( $post->ID, 'nkweb_Use_Custom_Values', true) != "true"){ echo ' input-disabled" readonly="readonly'; } ?>"><?php echo get_post_meta( $post->ID, 'nkweb_Custom_Values', true); ?></textarea>
	<div class="description"><?php esc_attr_e('Example: \'forceSSL\': true, \'dimension1\': \'some data\', \'metric2\': totalprice', 'NKgoogleanalytics'); ?></div>
	</td>
	</tr>
	
	<tr valign="top">
	<th scope="row"><?php _e( 'Use custom Google Analytics tracking code', 'NKgoogleanalytics' ); ?></th>
	<td>
	<input type="radio" id="nkweb_Use_Custom_true" name="nkweb_Use_Custom" value="true" <?php if (get_post_meta( $post->ID, 'nkweb_Use_Custom', true) == "true"){ echo "checked "; } ?>> Yes, use the following code<br>
	<input type="radio" id="nkweb_Use_Custom_false" name="nkweb_Use_Custom" value="false" <?php if (get_post_meta( $post->ID, 'nkweb_Use_Custom', true) != "true"){ echo "checked "; } ?>> No, use global configuration<br>
	<textarea name="nkweb_Custom_Code" class="nk-textarea<?php if (get_post_meta( $post->ID, 'nkweb_Use_Custom', true) != "true"){ echo ' input-disabled" readonly="readonly'; } ?>"><?php echo get_post_meta( $post->ID, 'nkweb_Custom_Code', true); ?></textarea>
	</td>
	</tr>
</table>
