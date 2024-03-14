<?php

/************************************
 Settings UI -- USED IN FREE EDITION SINCE THERE IS NO OTHER LICENSING PAGE
 ************************************* */
 
function fca_ept_hex2rgb( $hex_string ) {
	$r = base_convert ( substr( $hex_string, 1, 2 ), 16, 10 );
	$g = base_convert ( substr( $hex_string, 3, 2 ), 16, 10 );
	$b = base_convert ( substr( $hex_string, 5, 2 ), 16, 10 );
	
	return "$r, $g, $b";
}


function dh_ptp_settings_menu() {
		
	add_submenu_page( 'edit.php?post_type=easy-pricing-table', __('Settings', 'easy-pricing-tables'), __('Settings', 'easy-pricing-tables'), 'manage_options', 'easy-pricing-tables-settings', 'dh_ptp_settings' );
	
}
add_action( 'admin_menu', 'dh_ptp_settings_menu' );

function dh_ptp_settings() {

	$dh_ptp_show_legacy_tables = ( get_option( 'dh_ptp_show_legacy_tables') == 'no' || get_option( 'dh_ptp_show_legacy_tables') == '' ? false : 'checked' );
	?>
	
	<div class="wrap">
		<form method="post" action="options.php">

				<h2><?php _e('Plugin Options', 'easy-pricing-tables'); ?></h2>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Compatibility mode', 'easy-pricing-tables'); ?>
							</th>
							<td>
								<input id="dh_ptp_show_legacy_tables" name="dh_ptp_show_legacy_tables" type="checkbox" value="yes" <?php echo $dh_ptp_show_legacy_tables ?>/>
								<label for="dh_ptp_show_legacy_tables" class="description"><?php _e('Show legacy (pre-Gutenberg) pricing table builder', 'easy-pricing-tables'); ?>
							</td>
						</tr>
					</tbody>
				</table>

			<?php
			settings_fields('dh_ptp_settings'); 
			submit_button();
			?>
	
		</form>
	</div>
	<?php
}

do_action( 'update_option_dh_ptp_show_legacy_tables', 'dh_ptp_show_legacy_tables' );


function dh_ptp_register_settings() {
	// creates our settings in the options table
	register_setting('dh_ptp_settings', 'dh_ptp_show_legacy_tables');
}
add_action('admin_init', 'dh_ptp_register_settings');

