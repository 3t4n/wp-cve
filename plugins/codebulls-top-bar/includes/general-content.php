<?php 
add_action( 'generate_top_bar_settings_general_content', 'cb_top_bar_generate_top_bar_settings_general_content', 1 );
/**
 * Method that adds the container on the general settings tab 
 */
function cb_top_bar_generate_top_bar_settings_general_content(){
	global $cb_top_bar_active_tab;
	if ( '' || 'general' != $cb_top_bar_active_tab )
		return;
	?>
 
	<h3><?php _e( 'General', 'cb_top_bar' ); ?></h3>
	<div class="wrap">
		<form method="POST" action="options.php" enctype="multipart/form-data">
			<?php
				settings_fields( 'cb_top_bar_settings' );                    
			?>
			<table class="form-table">
				<tbody>
					<?php cb_top_bar_get_top_bar_general_options(); ?>
				</tbody>
			</table>
			<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'top-bar-codebulls' ); ?>" />
			<input type="hidden" name="ps-cb-top-bar-submit" value="Y" />
		</form>
	</div>
	<?php
}
/**
 * Method that adds the options inside the general settings tab container created above
 */
function cb_top_bar_get_top_bar_general_options(){
	$options=get_option('options_cb_top_bar');
	if ( isset($options['available-plugin']) ) {
		$options['available-plugin'] = wp_kses($options['available-plugin'],array('input','textarea','checkbox','label'));
	} else {
		$options['available-plugin'] = __( '0', 'top-bar-codebulls' );
	}
	if ( isset($options['color-top-bar-plugin']) ) {
		$options['color-top-bar-plugin'] = wp_kses($options['color-top-bar-plugin'],array('input','textarea','checkbox','label'));
	} else {
		$options['color-top-bar-plugin'] = __( 'red', 'top-bar-codebulls' );
	}
	if ( isset($options['color-text-top-bar-plugin']) ) {
		$options['color-text-top-bar-plugin'] = wp_kses($options['color-text-top-bar-plugin'],array('input','textarea','checkbox','label'));
	} else {
		$options['color-text-top-bar-plugin'] = __( 'white', 'top-bar-codebulls' );
	}
	if ( isset($options['left-content-top-bar-plugin']) ) {
		$options['left-content-top-bar-plugin'] = wp_kses($options['left-content-top-bar-plugin'],array('input','textarea','checkbox','label'));
	} else {
		$options['left-content-top-bar-plugin'] = __( '', 'top-bar-codebulls' );
	}
	if ( isset($options['height-top-bar-plugin']) ) {
		$options['height-top-bar-plugin'] = wp_kses($options['height-top-bar-plugin'],array('input','textarea','checkbox','label','select','option'));
	} else {
		$options['height-top-bar-plugin'] = __( '35px', 'top-bar-codebulls' );
	}
	if ( isset($options['number-columns-top-bar-plugin']) ) {
		$options['number-columns-top-bar-plugin'] = wp_kses($options['number-columns-top-bar-plugin'],array('input','textarea','checkbox','label','select','option'));
	} else {
		$options['number-columns-top-bar-plugin'] = __( '1', 'top-bar-codebulls' );
	}
	if ( isset($options['custom-css-top-bar-plugin']) ) {
		$options['custom-css-top-bar-plugin'] = wp_kses($options['custom-css-top-bar-plugin'],array('input','textarea','checkbox','label','select','option'));
	} else {
		$options['custom-css-top-bar-plugin'] = __( '', 'top-bar-codebulls' );
	}
	if ( isset($options['user-can-close-top-bar']) ) {
		$options['user-can-close-top-bar'] = wp_kses($options['user-can-close-top-bar'],array('input','textarea','checkbox','label'));
	} else {
		$options['user-can-close-top-bar'] = __( '0', 'top-bar-codebulls' );
	}



	if ( isset($options['sticky-top-bar']) ) {
		$options['sticky-top-bar'] = wp_kses($options['sticky-top-bar'],array('input','textarea','checkbox','label'));
	} else {
		$options['sticky-top-bar'] = __( '0', 'top-bar-codebulls' );
	}




    ob_start();
	?>
		<tr valign="top"><th scope="row"><?php _e( 'Activate', 'top-bar-codebulls' ); ?></th>
			<td>
				<?php
					if($options['available-plugin'] == '1'){
						echo '<input type="checkbox" name="options_cb_top_bar[available-plugin]" id="options_cb_top_bar[available-plugin]" value="1" checked >';
					}else{
						echo '<input type="checkbox" name="options_cb_top_bar[available-plugin]" id="options_cb_top_bar[available-plugin]" value="1" >';
					}
				?>
			</td>
		</tr>
		<tr valign="top"><th scope="row"><?php _e( 'User can close top bar', 'top-bar-codebulls' ); ?></th>
			<td>
				<?php
					if($options['user-can-close-top-bar'] == '1'){
						echo '<input type="checkbox" name="options_cb_top_bar[user-can-close-top-bar]" id="options_cb_top_bar[user-can-close-top-bar]" value="1" checked >';
					}else{
						echo '<input type="checkbox" name="options_cb_top_bar[user-can-close-top-bar]" id="options_cb_top_bar[user-can-close-top-bar]" value="1" >';
					}
				?>
			</td>
		</tr>



		<tr valign="top"><th scope="row"><?php _e( 'Sticky top bar', 'top-bar-codebulls' ); ?></th>
			<td>
				<?php
					if($options['sticky-top-bar'] == '1'){
						echo '<input type="checkbox" name="options_cb_top_bar[sticky-top-bar]" id="options_cb_top_bar[sticky-top-bar]" value="1" checked >';
					}else{
						echo '<input type="checkbox" name="options_cb_top_bar[sticky-top-bar]" id="options_cb_top_bar[sticky-top-bar]" value="1" >';
					}
				?>
			</td>
		</tr>			




		<tr valign="top"><th scope="row"><?php _e( 'Background Color', 'top-bar-codebulls' ); ?></th>
			<td style="width: 1%;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Top bar color: this field allow color names,html code color and rgb code. e.g. (white,#FFFFFF,rgb(255,255,255))"></span></td>
			<td>
				<?php
					echo '<input type="text" name="options_cb_top_bar[color-top-bar-plugin]" id="options_cb_top_bar[color-top-bar-plugin]" value="'.$options['color-top-bar-plugin'].'">';
				?>
			</td>
		</tr>
		<tr valign="top"><th scope="row"><?php _e( 'Text color', 'top-bar-codebulls' ); ?></th>
			<td style="width: 1%;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Top bar text color: this field allow color names,html code color and rgb code. e.g. (white,#FFFFFF,rgb(255,255,255))"></span></td>
			<td>
				<?php
					echo '<input type="text" name="options_cb_top_bar[color-text-top-bar-plugin]" id="options_cb_top_bar[color-text-top-bar-plugin]" value="'.$options['color-text-top-bar-plugin'].'">';
				?>
			</td>
		</tr>
		<tr valign="top"><th scope="row"><?php _e( 'Height', 'top-bar-codebulls' ); ?></th>
			<td style="width: 1%;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Enter a value in px or % to determine the height of the top bar. e.g. (40px,12%)"></span></td>
			<td>
				<?php
					echo '<input type="text" name="options_cb_top_bar[height-top-bar-plugin]" id="options_cb_top_bar[height-top-bar-plugin]" value="'.$options['height-top-bar-plugin'].'">';
				?>
			</td>
		</tr>

		<tr valign="top"><th scope="row"><?php _e( 'Custom CSS', 'top-bar-codebulls' ); ?></th>
			<td style="width: 1%; vertical-align:baseline;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="This field allow css code, this code will be added in the site"></span></td>
			<td>
				<?php
					echo '<textarea name="options_cb_top_bar[custom-css-top-bar-plugin]" id="options_cb_top_bar[custom-css-top-bar-plugin]">'.$options['custom-css-top-bar-plugin'].'</textarea>';
				?>
			</td>
		</tr>
		
		<tr valign="top"><th scope="row"><?php _e( 'Columns number', 'top-bar-codebulls' ); ?></th>
			<td style="width: 1%;"><span class="woocommerce-help-tip" data-toggle="tooltip" title="Number of top bar columns/sections"></span></td>
			<td>
				<?php
				switch ($options['number-columns-top-bar-plugin']) {
					case '1':
						echo '<select name="options_cb_top_bar[number-columns-top-bar-plugin]" id="options_cb_top_bar[number-columns-top-bar-plugin]">
							<option value=1 selected>1 Column</option>
							<option value=2>2 Columns</option>
							<option value=3>3 Columns</option>
						</select>';
						break;
					case '2':
						echo '<select name="options_cb_top_bar[number-columns-top-bar-plugin]" id="options_cb_top_bar[number-columns-top-bar-plugin]">
							<option value=1>1 Column</option>
							<option value=2 selected>2 Columns</option>
							<option value=3>3 Columns</option>
						</select>';
						break;
					case '3':
						echo '<select name="options_cb_top_bar[number-columns-top-bar-plugin]" id="options_cb_top_bar[number-columns-top-bar-plugin]">
							<option value=1>1 Column</option>
							<option value=2>2 Columns</option>
							<option value=3 selected>3 Columns</option>
						</select>';
						break;
				}
				?>	
			</td>
		</tr>
	<?php
}
?>