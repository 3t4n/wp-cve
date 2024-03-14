<?php 
add_action( 'generate_top_bar_settings_content', 'cb_top_bar_generate_top_bar_settings_content', 1 );
/**
 * Method that add the content per the content tab
 */
function cb_top_bar_generate_top_bar_settings_content(){
	global $cb_top_bar_active_tab;
	if ( '' || 'content' != $cb_top_bar_active_tab )
		return;
	?>
 
	<h3><?php _e( 'You can add content as HTML code and Wordpress shortcodes', 'cb_top_bar' ); ?></h3>
	<div class="wrap">
		<form method="POST" action="options.php" enctype="multipart/form-data">
			<?php
				settings_fields( 'cb_top_bar_content_settings' );                    
			?>
			<table class="form-table">
				<tbody>
					<?php cb_top_bar_get_top_bar_content_options(); ?>
				</tbody>
			</table>
			<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'top-bar-codebulls' ); ?>" />
			<input type="hidden" name="ps-cb-top-bar-submit" value="Y" />
		</form>
	</div>
	<?php
}
/**
 * Method that add the options per content tab on the plugin options
 */
function cb_top_bar_get_top_bar_content_options(){
	$options=get_option('options_cb_top_bar');
	$options_content=get_option('options_cb_top_bar_content');
	if ( isset($options_content['left-content-top-bar-plugin']) ) {
		$options_content['left-content-top-bar-plugin'] = wp_kses($options_content['left-content-top-bar-plugin'],array('input','textarea','checkbox','label'));
	} else {
		$options_content['left-content-top-bar-plugin'] = __( '', 'top-bar-codebulls' );
	}
	if ( isset($options_content['center-content-top-bar-plugin']) ) {
		$options_content['center-content-top-bar-plugin'] = wp_kses($options_content['center-content-top-bar-plugin'],array('input','textarea','checkbox','label'));
	} else {
		$options_content['center-content-top-bar-plugin'] = __( '', 'top-bar-codebulls' );
	}
	if ( isset($options_content['right-content-top-bar-plugin']) ) {
		$options_content['right-content-top-bar-plugin'] = wp_kses($options_content['right-content-top-bar-plugin'],array('input','textarea','checkbox','label'));
	} else {
		$options_content['right-content-top-bar-plugin'] = __( '', 'top-bar-codebulls' );
	}
	if ( isset($options['number-columns-top-bar-plugin']) ) {
		$options['number-columns-top-bar-plugin'] = wp_kses($options['number-columns-top-bar-plugin'],array('input','textarea','checkbox','label','select','option'));
	} else {
		$options['number-columns-top-bar-plugin'] = __( '1', 'top-bar-codebulls' );
	}
	ob_start();
	switch ($options['number-columns-top-bar-plugin']) {
		case '1':
			?>		
			<tr valign="top"><th scope="row"><?php _e( 'Top Bar Content', 'top-bar-codebulls' ); ?></span></th>
				<td style="width: 1%; vertical-align:baseline;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Inside here the content that you want in the top bar.This field allows content in HTML and Wordpress shortcodes."></td>
				<td>
					<?php
						echo '<textarea name="options_cb_top_bar_content[center-content-top-bar-plugin]" id="options_cb_top_bar_content[center-content-top-bar-plugin]">'.html_entity_decode($options_content['center-content-top-bar-plugin']).'</textarea>';
					?>
				</td>
			</tr>
			<?php
			break;
		case '2':
			?>		
			<tr valign="top"><th scope="row"><?php _e( 'Contents left side of the top bar', 'top-bar-codebulls' ); ?> </th>
				<td style="width: 1%; vertical-align:baseline;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Inside here the content that you want in the top bar.This field allows content in HTML and Wordpress shortcodes."></td>
				<td>
					<?php				
						echo '<textarea name="options_cb_top_bar_content[left-content-top-bar-plugin]" id="options_cb_top_bar_content[left-content-top-bar-plugin]">'.html_entity_decode($options_content['left-content-top-bar-plugin']).'</textarea>';
					?>
				</td>
			</tr>
			<?php
			?>		
			<tr valign="top"><th scope="row"><?php _e( 'Contents right side of the top bar', 'top-bar-codebulls' ); ?></th>
				<td style="width: 1%; vertical-align:baseline;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Inside here the content that you want in the top bar.This field allows content in HTML and Wordpress shortcodes."></td>
				<td>
					<?php				
						echo '<textarea name="options_cb_top_bar_content[right-content-top-bar-plugin]" id="options_cb_top_bar_content[right-content-top-bar-plugin]">'.html_entity_decode($options_content['right-content-top-bar-plugin']).'</textarea>';
					?>
				</td>
			</tr>
			<?php
			break;
		case '3':
			?>		
			<tr valign="top"><th scope="row"><?php _e( 'Contents left side of the top bar', 'top-bar-codebulls' ); ?> </th>
				<td style="width: 1%; vertical-align:baseline;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Inside here the content that you want in the top bar.This field allows content in HTML and Wordpress shortcodes."></td>
				<td>
					<?php				
						echo '<textarea name="options_cb_top_bar_content[left-content-top-bar-plugin]" id="options_cb_top_bar_content[left-content-top-bar-plugin]">'.html_entity_decode($options_content['left-content-top-bar-plugin']).'</textarea>';
					?>
				</td>
			</tr>
			<?php
			?>		
			<tr valign="top"><th scope="row"><?php _e( 'Contents center of the top bar', 'top-bar-codebulls' ); ?> </th>
				<td style="width: 1%; vertical-align:baseline;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Inside here the content that you want in the top bar.This field allows content in HTML and Wordpress shortcodes."></td>
				<td>
					<?php				
						echo '<textarea name="options_cb_top_bar_content[center-content-top-bar-plugin]" id="options_cb_top_bar_content[center-content-top-bar-plugin]">'.html_entity_decode($options_content['center-content-top-bar-plugin']).'</textarea>';
					?>
				</td>
			</tr>
			<?php
			?>		
			<tr valign="top"><th scope="row"><?php _e( 'Contents right side of the top bar', 'top-bar-codebulls' ); ?> </th>
				<td style="width: 1%; vertical-align:baseline;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Inside here the content that you want in the top bar.This field allows content in HTML and Wordpress shortcodes."></td>
				<td>
					<?php				
						echo '<textarea name="options_cb_top_bar_content[right-content-top-bar-plugin]" id="options_cb_top_bar_content[right-content-top-bar-plugin]">'.html_entity_decode($options_content['right-content-top-bar-plugin']).'</textarea>';
					?>
				</td>
			</tr>
			<?php
			break;
	}
}
?>