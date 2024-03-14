<?php
/**
 * Get Global Shortcodes
 * 
 * @package Shortcode_Lister
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Sorry, you are not allowed to access this page directly.' );
}
add_action( 'media_buttons', 'shortcode_lister_menu', 15 );

/**
 * Shortcode Menu Lister
 * 
 * Creates and displays array of all shortcodes; for both the editor dropdown and for the Shortcode Lister plugin whitelisting/settings page.
 *
 * @param string $location Location is either the settings page or the default which is the <select> dropdown in the classic editor.
 * @return void
 */
function shortcode_lister_menu( $location ) {
	// retrieve registered shortcode tags.
	global $shortcode_tags;
	
	// set up include array.
	$includes = array();
	
	global $shortcode_lister_settings;
	$shortcode_lister_settings = get_option( 'shortcode_lister_settings' );

	switch ( $location ) {
		case 'settings':
			foreach ( $shortcode_tags as $code => $function ) { ?>
				<tr valign="top">
					<td>
						<input id="shortcode_lister_settings[<?php echo esc_textarea( $code ); ?>]" name="shortcode_lister_settings[<?php echo esc_textarea( $code ); ?>]" type="checkbox" value="1" 
							<?php 
							if ( is_array( $shortcode_lister_settings ) && array_key_exists( $code, $shortcode_lister_settings ) ) {
								checked( 1, $shortcode_lister_settings[ $code ] ); } 
							?>
						/>
						<label class="description" for="shortcode_lister_settings[<?php echo esc_textarea( $code ); ?>]">[<?php echo esc_textarea( $code ); ?>]</label>
					</td>
				</tr>
				<?php
			}
			break;
		
		default:
			// loop through each shortcode tag to create array of tags.
			foreach ( $shortcode_tags as $code => $function ) {
				if ( is_array( $shortcode_lister_settings ) && array_key_exists( $code, $shortcode_lister_settings ) && 1 == $shortcode_lister_settings[ $code ] ) {
					// do nothing.
					continue;
				} else {
					$includes[] = '[' . $code . ']';
				}
			}
			echo '&nbsp;<select id="sl_select"><option class="noclick">Shortcodes</option>';
			foreach ( $includes as $include ) {
				echo '<option value="' . esc_attr( $include ) . '">' . esc_html( $include ) . '</option>';
			}
			echo '</select>';
			break;
	}
}
