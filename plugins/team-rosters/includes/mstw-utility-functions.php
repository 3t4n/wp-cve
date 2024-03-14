<?php
/*----------------------------------------------------------------------------
 * mstw-utility-functions.php
 * 	'Helper functions' used throughout the MSTW plugin family
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.

 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
/*------------------------------------------------------------------------------
 * MSTW UTLITY FUNCTIONS
 *	'Helper functions' used throughout the MSTW plugin family
 *
 * 1. mstw_log_msg - writes debug messages to /wp-content/debug.log
 *					 if the WP_DEBUG settings in wp-config are correct
 * 2. mstw_requires_wordpress_version - checks for the right WordPress version
 * 3. mstw_safe_ref - prevents uninitialized string errors 
 * 3.1	mstw_safe_get - Safely get value for a key from an array
 * 4. mstw_build_css_rule - builds css rules from an array of options
 *							generally from get_option() without errors
 * 5. mstw_date_loc - handles localization for the PHP date function
 * 6. mstw_build_admin_edit_screen - builds admin UI data entry screens
 * 7. mstw_build_admin_edit_field - Builds HTML for all admin form fields
 * 8. mstw_build_settings_screen - builds admin settings form (using settings api)
 * 9. mstw_build_settings_field - builds form fields using settings api
 * 10. mstw_sanitize_hex_color - Sanitizes/validates hex colors (for options)
 * 11. mstw_is_valid_url - validates a url using both filter_var() & preg_match()
 * 12. mstw_validate_url - convenience function to handle url validation 
 *						   in a CPT save_post (validation) callback
 * 13. mstw_get_current_post_type - get the current post type in the WordPress Admin
 * 14. mstw_get_the_slug - get the post slug from the post id
 * 15. mstw_has_admin_rights - checks is the CURRENT USER has mstw admin rights
 * 16. mstw_user_has_plugin_rights - check if the CURRENT USER has admin rights for
 *									 specified plugin
 * 16.1 mstw_user_has_ss_rights - FOR COMPATIBILITY ONLY - use mstw_user_has_plugin_rights('ss')
 *			check if the CURRENT USER has admin rights for MSTW Schedules & Scoreboards plugin
 * 17. mstw_admin_notice - Displays all admin notices; callback for admin_notices action
 * 18. mstw_add_admin_notice - Adds admin notices to transient for display on admin_notices hook
 * 19. mstw_add_role_caps - adds the MSTW capabilities to a WP role
 *----------------------------------------------------------------------------*/
 
//------------------------------------------------------------------------------
//	1. mstw_log_msg - logs messages to /wp-content/debug IF WP_DEBUG is true
//		ARGUMENTS:
//			$msg - string, array, or object to log
//					note: if $msg == 'divider' a divider is output to the log
//		RETURNS:
//			None. Outputs to WP error_log
//
if ( !function_exists( 'mstw_log_msg' ) ) {
	function mstw_log_msg( $msg ) {
		if ( WP_DEBUG === true ) {
			if ( $msg == 'divider' ) {
				error_log( '------------------------------------------------------' );
			}
			else if( is_array( $msg ) || is_object( $msg ) ) {
				error_log( print_r( $msg, true ) );
			} 
			else {
				error_log( $msg );
			}
		}
	} //End: mstw_log_msg( )
}

//------------------------------------------------------------------------------
//	2. mstw_requires_wordpress_version - checks for the right WordPress version
//		Arguments:
//			$msg - string, array, or object to log
//		Returns:
//			none - prints message to upgrade and exits
//	THIS FUNCTION ONLY WORKS IN ADMIN (because it calls get_plugin_data()
//
if ( !function_exists( 'mstw_requires_wordpress_version' ) ) {
	function mstw_requires_wordpress_version( $version = '3.9.2' ) {
		global $wp_version;
		
		$plugin = MSTW_SS_PLUGIN_NAME;
		//$plugin_data = get_plugin_data( __FILE__, false );
		$plugin_data = get_plugin_data( MSTW_SS_PLUGIN_DIR . '/mstw-schedules-scoreboards.php', 
										false );

		if ( version_compare( $wp_version, $version, "<" ) ) {
			if( is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
				$die_msg = $plugin_data['Name'] . " requires WordPress $version or higher, and has been deactivated! <br/> Please upgrade WordPress and try again.<br /><br /><a href='".admin_url()."'>Back to admin dashboard</a>.";
				die( $die_msg );
			}
		}
	} //end mstw_requires_wordpress_version()
}

// ----------------------------------------------------------------
// 3. mstw_safe_ref - prevents uninitialized string errors 
//		Arguments:
//			$array - the array to reference
//			$index - the index into $array
//		Returns;
//			$array[$index] if it is set, '' otherwise
//
if( !function_exists( 'mstw_safe_ref' ) ) {
	function mstw_safe_ref( $array, $index ) {
		return ( isset( $array[$index] ) ? $array[$index] : '' );
	}
}

//--------------------------------------------------------------------------------
// 3.1 mstw_safe_get - Safely get value for a key ($needle) from an 
//			array (haystack). Returns $default if key does not exist
// 	ARGUMENTS:
// 		$needle - a (candidate) array key
//		$haystack - an array
//		$default - returned if $needle is not (a key) in $haystack
//
// 	RETURNS
//		$haystack[ $needle ] if $needle exists as a key. $default otherwise.
//
if ( !function_exists ( 'mstw_safe_get' ) ) {
	function mstw_safe_get( $needle, $haystack, $default = '' ) {
		//mstw_log_msg( "mstw_safe_get: needle= $needle" );
		//mstw_log_msg( $haystack );
		
		if ( array_key_exists( $needle, $haystack ) ) {
			return $haystack[ $needle ];
			
		} else {
			return $default;
			
		}

	} //End: mstw_safe_get( )
}

//------------------------------------------------------------------------------
//	4. mstw_build_css_rule - builds css rules
//		Arguments:
//			$options: array of options (settings DB)
//			$option_key: key for options in array 
//			$css_base: base for css rule (e.g. 'background-color' )
//			$suffix: string to add on end of rule (E.G. 'px' at the end of width)
//		Returns:
//			css rule "css_base:options[option_key]; \n"
//				or "" on an error	
//
if ( !function_exists( 'mstw_build_css_rule' ) ) {		
	function mstw_build_css_rule( $options, $option_key, $css_base, $suffix='' ) {
		if ( isset( $options[$option_key] ) and !empty( $options[$option_key] ) ) {
			return $css_base . ":" . $options[$option_key] . $suffix . "; \n";	
		} 
		else {
			return "";
		}
	} //end: mstw_build_css_rule()
}

//-------------------------------------------------------------------------------
// 5. mstw_date_loc - handles localization for the PHP date function
//
// This is a modification of the PHP date function for use
// in WP internationalization/localization. If you have created a translation file
// for the plugin and set the WP_LANG variable in the wp-config.php file, 
// this function will work (at least for most formats). 
//
// If you don't understand WordPress internationalization, you would
// be well advised to read the codex before jumping in to this pool.
//
if ( !function_exists( 'mstw_date_loc' ) ) {
	function mstw_date_loc($format, $timestamp = null) {
		$param_D = array( '', 
							__( 'Mon', 'mstw-loc-domain' ), 
							__( 'Tue', 'mstw-loc-domain' ), 
							__( 'Wed', 'mstw-loc-domain' ), 
							__( 'Thr', 'mstw-loc-domain' ), 
							__( 'Fri', 'mstw-loc-domain' ), 
							__( 'Sat', 'mstw-loc-domain' ), 
							__( 'Sun', 'mstw-loc-domain' ) );
		
		$param_l = array( '', 
							__( 'Monday', 'mstw-loc-domain' ), 
							__( 'Tuesday', 'mstw-loc-domain' ), 
							__( 'Wednesday', 'mstw-loc-domain' ), 
							__( 'Thursday', 'mstw-loc-domain' ), 
							__( 'Friday', 'mstw-loc-domain' ), 
							__( 'Saturday', 'mstw-loc-domain' ), 
							__( 'Sunday', 'mstw-loc-domain' ) );
							
		$param_F = array( '', 
							__( 'January', 'mstw-loc-domain' ), 
							__( 'February', 'mstw-loc-domain' ), 
							__( 'March', 'mstw-loc-domain' ), 
							__( 'April', 'mstw-loc-domain' ), 
							__( 'May', 'mstw-loc-domain' ), 
							__( 'June', 'mstw-loc-domain' ),
							__( 'July', 'mstw-loc-domain' ),
							__( 'August', 'mstw-loc-domain' ),
							__( 'September', 'mstw-loc-domain' ),
							__( 'October', 'mstw-loc-domain' ),
							__( 'November', 'mstw-loc-domain' ),
							__( 'December', 'mstw-loc-domain' ) );
							
		$param_M = array( '', 
							__( 'Jan', 'mstw-loc-domain' ), 
							__( 'Feb', 'mstw-loc-domain' ), 
							__( 'Mar', 'mstw-loc-domain' ), 
							__( 'Apr', 'mstw-loc-domain' ), 
							__( 'May', 'mstw-loc-domain' ), 
							__( 'Jun', 'mstw-loc-domain' ),
							__( 'Jul', 'mstw-loc-domain' ),
							__( 'Aug', 'mstw-loc-domain' ),
							__( 'Sep', 'mstw-loc-domain' ),
							__( 'Oct', 'mstw-loc-domain' ),
							__( 'Nov', 'mstw-loc-domain' ),
							__( 'Dec', 'mstw-loc-domain' ) );
		
		$return = '';
		
		if ( is_null( $timestamp ) ) { 
			$timestamp = current_time( 'timestamp' ); 
		}
		
		for( $i = 0, $len = strlen( $format ); $i < $len; $i++ ) {
			switch($format[$i]) {
				case '\\' : // double.slashes
					$i++;
					$return .= isset($format[$i]) ? $format[$i] : '';
					break;
				case 'D' :
					$return .= $param_D[date('N', $timestamp)];
					break;
				case 'l' :
					$return .= $param_l[date('N', $timestamp)];
					break;
				case 'F' :
					$return .= $param_F[date('n', $timestamp)];
					break;
				case 'M' :
					$return .= $param_M[date('n', $timestamp)];
					break;
				default :
					$return .= date($format[$i], $timestamp);
					break;
			}
		}
		
		return $return;
		
	}
}

//-------------------------------------------------------------------------------
// 6. mstw_build_admin_edit_screen - Convenience function to build admin UI 
//									 data entry screens
//	ARGUMENTS: $fields = array(
//		'type'       => $type,
//		'id'         => $id,
//		'desc'       => $desc,
//		'curr_value' => current field value,
//		'options'    => array of options in key=>value pairs
//			e.g., array( __( '08:00 (24hr)', 'mstw-loc-domain' ) => 'H:i', ... )
//		'label_for'  => $id,
//		'class'      => $class,
//		'name'		 => $name,
//	);
//
if( !function_exists( 'mstw_build_admin_edit_screen' ) ) {	
	function mstw_build_admin_edit_screen( $fields ) {
		
		foreach( $fields as $field_id=>$field_data ) {
			//HANDLE table dividers here ... NEW
			if ( $field_data['type'] == 'divider' ) {
				$divider_msg = ( isset( $field_data['curr_value'] ) ) ? $field_data['curr_value'] : '&nbsp;&nbsp;';
				?>
				<tr class='mstw-divider-spacer'><td>&nbsp;&nbsp;</td></tr>
				<tr class='mstw-divider'><th colspan=2 ><?php echo $divider_msg ?></th></tr>
				<?php
			}
			else {
				$field_data['id'] = ( !isset( $field_data['id'] ) || empty( $field_data['id'] ) ) ? $field_id : $field_data['id'];
				$field_data['name'] = ( !isset( $field_data['name'] ) || empty( $field_data['name'] ) ) ? $field_id : $field_data['name'];
				
				// check the field label/title
				if ( array_key_exists( 'label', $field_data ) && !empty( $field_data['label'] ) )
					$label = $field_data['label'];
				else
					$label = '';
				?>
				
				<tr>
					<th><label for '<?php echo $field_data['id']?>' >
						<?php echo $label ?>
					</label></th>
					<?php 
					// media-uploader will add it's own cells (3 of theme)
					if ( $field_data['type'] != 'media-uploader' ) { 
						echo "<td>\n";
					}

						
						mstw_build_admin_edit_field( $field_data );

					if ( $field_data['type'] != 'media-uploader' ) { 
						echo "</td>\n";
					}
					?>
					</tr>
				<?php
			}
		}
		
	} //End: mstw_build_admin_edit_screen()
}

//-------------------------------------------------------------------------------
// 7. mstw_build_admin_edit_field - Helper function for building HTML for all admin 
//								form fields ... ECHOES OUTPUT
//
//	ARGUMENTS: $args = array(
//		'type'       => $type,
//		'id'         => $id,
//		'desc'       => $desc,
//		'curr_value' => current field value,
//		'options'    => array of options in key=>value pairs
//			e.g., array( __( '08:00 (24hr)', 'mstw-loc-domain' ) => 'H:i', ... )
//		'label_for'  => $id,
//		'class'      => $class,
//		'name'		 => $name,
//	);
//		
//
if( !function_exists( 'mstw_build_admin_edit_field' ) ) {
	function mstw_build_admin_edit_field( $args ) {
	
		$defaults = array(
				'type'		   => 'text',
				'id'      	 => 'default_field', // the ID of the setting in our options array, and the ID of the HTML form element
				'title'   	 => __( 'Default Field', 'mstw-loc-domain' ), // the label for the HTML form element
				'label'   	 => __( 'Default Label', 'mstw-loc-domain' ), // the label for the HTML form element
				'desc'   	   => '', // the description displayed under the HTML form element
				'default'	   => '',  // the default value for this setting
				'type'    	 => 'text', // the HTML form element to use
				'options' 	 => array(), // (optional): the values in radio buttons or a drop-down menu
				'name' 		   => '', //name of HTML form element. should be options_array[option]
				'class'   	 => '',  // the HTML form element class. Also used for validation purposes!
				'curr_value' => '',  // the current value of the setting
				'maxlength'	 => '',  // maxlength attrib of some input controls
				'size'	 	   => '',  // size attrib of some input controls
				'img_width'  => 60,
				'btn_label'  => 'Upload from Media Library',
				);
		
		// "extract" to be able to use the array keys as variables in our function output below
		$args = wp_parse_args( $args, $defaults );
	
		extract( $args );
		
		// default name to id
		$name = ( !empty( $name ) ) ? $name : $id;
		
		$name = esc_attr( $name );
		
		$id   = esc_attr( $id );
		
		// pass the standard value if the option is not yet set in the database
		
		// Additional field class. Output only if the class is defined in the $args()
		$class_str = ( !empty( $class ) ) ? "class='$class'" : '';
		$maxlength_str = ( !empty( $maxlength ) ) ? "maxlength='$maxlength'" : '' ;
		$size_str = ( !empty( $size ) ) ? "size='$size'" : '' ;
		$attrib_str = " $class_str $maxlength_str $size_str ";
		
		$desc = esc_html( $desc );
		$desc_html = ( !empty( $desc ) ) ? "<br /><span class='description'>$desc</span>" : "";
		

		// switch html display based on the setting type.
		switch ( $args['type'] ) {
			//TEXT & COLOR CONTROLS
			case 'text':	// this is the default type
			case 'color':  	// color field is just a text field with associated JavaScript
			?>
				<input type="text" id="<?php echo $id ?>" name="<?php echo $name ?>" value="<?php echo $curr_value ?>" <?php echo $attrib_str ?> />
			<?php
				echo $desc_html;
				break;
				
			//SELECT OPTION CONTROL
			case 'select-option':
				//not sure why this is needed given the extract() above
				//but without it you get an extra option with the 
				//'option-name' displayed (huh??)
				$options = $args['options'];
					
				echo "<select id='$id' name='$name' $attrib_str >";
					foreach( $options as $key=>$value ) {
						$selected = ( $curr_value == $value ) ? 'selected="selected"' : '';
						$value = esc_attr( $value );
						$key   = esc_html( $key );
						echo "<option value='$value' $selected>$key</option>";
					}
				echo "</select>";
				echo $desc_html;
				break;
			
			// CHECKBOX
			case 'checkbox':
				echo "<input class='checkbox $class_str' type='checkbox' id='$id' name='$name' value=1 " . checked( $curr_value, 1, false ) . " />";
				echo $desc_html;
				break;
				
			// LABEL
			case 'label':
				echo "<span class='description'>" . esc_html( $curr_value ) . "</span>";
				echo $desc_html;
				break;
				
			// MEDIA UPLOADER
			case 'media-uploader':
				?>
				<td class="uploader">
					<input type="text" name="<?php echo $id  ?>" id="<?php echo $id ?>" class="mstw_logo_text" size="32" value="<?php echo esc_attr( $curr_value )?>"/>
					<?php echo $desc_html; ?>
				</td>
				
				<td class="uploader">
				  <input type="button" class="button" name="<?php echo $id . '_btn'?>" id="<?php echo $id . '_btn'?>" value="<?php echo esc_attr( $btn_label ) ?>" />
				<!-- </div> -->
				</td>
				<td>
				<img id="<?php echo $id . '_img' ?>" width="<?php echo esc_attr( $img_width )?>" src="<?php echo esc_attr( $curr_value ) ?>" />
				</td>
		<?php
				break;
				
			default:
				echo "CONTROL TYPE $type NOT RECOGNIZED.";
				break;
			
		}	//End: switch ( $args['type'] ) {
			
	} //End: mstw_build_admin_edit_field()
}

//-------------------------------------------------------------------------------
// 8. mstw_build_settings_screen - builds admin settings form (using settings api)
//		ARGUEMENTS:
//			$arguments:	array of argument arrays passed to mstw_build_settings_field()
//		RETURN:
//			None. HTML is ouput/echoed to the screen by mstw_build_settings_field()
//
if( !function_exists( 'mstw_build_settings_screen' ) ) {	
	function mstw_build_settings_screen( $arguments ) {
		foreach ( $arguments as $args ) {
			mstw_build_settings_field( $args );
		}
	}  //End: mstw_build_settings_screen()
}

//-------------------------------------------------------------------------------
// 9. mstw_build_settings_field - builds admin screen form fields
//		ARGUEMENTS:
//			$args: array of arguments used to build form field (see descriptions below
//			$is_setting: true: use settings API to create a settings field ( add_settings_field() ) 
//					 false: just build the html form/input field; don't create setting
//		RETURN:
//			None. HTML is output/echoed to screen
//		
if( !function_exists( 'mstw_build_settings_field' ) ) {	
	function mstw_build_settings_field( $args ) {
		// default array to overwrite when calling the function
		
		$defaults = array(
			'id'      => 'default_field', // the ID of the setting in our options array, and the ID of the HTML form element
			'title'   => 'Default Field',  // the label for the HTML form element
			'desc'    => '', // the description displayed under the HTML form element
			'default'     => '',  // the default value for this setting
			'type'    => 'text', // the HTML form element to use
			'section' => '', // settings section to which this setting belongs
			'page' => '', //page on which the section belongs
			'options' => array(), // (optional): the values in radio buttons or a drop-down menu
			'name' => '', //name of HTML form element. should be options_array[option]
			'class'   => '',  // the HTML form element class. Also used for validation purposes!
			'value' => ''  // the current value of the setting
		);
		
		//	ARGUMENTS: $field_args = array(
		//		'type'       => $type, 	*
		//		'id'         => $id,	*
		//		'desc'       => $desc,	*
		//		'curr_value' => $value,	*
		//		'options'    => $options,	*
		//		'label_for'  => $id,	* (use id)
		//		'class'      => $class, *
		//		'name'		 => $name,
		//	);
		
		// "extract" to be able to use the array keys as variables in our function output below
		extract( wp_parse_args( $args, $defaults ) );
		
		//Handle some MSTW custom field types; convert for generic select-option
		switch ( $type ) {
			case 'show-hide':
				$type = 'select-option';
				$options = array(	__( 'Show', 'mstw-loc-domain' ) => 1, 
									__( 'Hide', 'mstw-loc-domain' ) => 0, 
								  );
				break;
			case 'date-time':
				$type = 'select-option';
				
				$options = array ( 	__( 'Custom', 'mstw-loc-domain' ) => 'custom',
									__( 'Tuesday, 07 April 01:15 pm', 'mstw-loc-domain' ) => 'l, d M h:i a',
									__( 'Tuesday, 7 April 01:15 pm', 'mstw-loc-domain' ) => 'l, j M h:i a',
									__( 'Tuesday, 07 April 1:15 pm', 'mstw-loc-domain' ) => 'l, d M g:i a',
									__( 'Tuesday, 7 April 1:15 pm', 'mstw-loc-domain' ) => 'l, j M g:i a',
									__( 'Tuesday, 7 April 13:15', 'mstw-loc-domain' ) => 'l, d M H:i',
									__( 'Tuesday, 7 April 13:15', 'mstw-loc-domain' ) => 'l, j M H:i',
									__( '07 April 13:15', 'mstw-loc-domain' ) => 'd M H:i',
									__( '7 April 13:15', 'mstw-loc-domain' ) => 'j M H:i',
									__( '07 April 01:15 pm', 'mstw-loc-domain' ) => 'd M g:i a',
									__( '7 April 01:15 pm', 'mstw-loc-domain' ) => 'j M g:i a',		
									);
				
				if ( isset( $custom_format ) && $custom_format == 0 ) {
					//remove the custom option
					unset( $options[ __( 'Custom', 'mstw_loc_domain' ) ] );
				}
				
				if ( $desc == '' ) {
					$desc = __( 'Formats for 7 April 2013 13:15.', 'mstw-loc-domain' );
				}
				
				break;
			case 'date-only':
				$type = 'select-option';
				$options = array ( 	__( 'Custom', 'mstw-loc-domain' ) => 'custom',
									'2013-04-07' => 'Y-m-d',
									'13-04-07' => 'y-m-d',
									'04/07/13' => 'm/d/y',
									'4/7/13' => 'n/j/y',
									__( '07 Apr 2013', 'mstw-loc-domain' ) => 'd M Y',
									__( '7 Apr 2013', 'mstw-loc-domain' ) => 'j M Y',
									__( 'Tues, 07 Apr 2013', 'mstw-loc-domain' ) => 'D, d M Y',
									__( 'Tues, 7 Apr 13', 'mstw-loc-domain' ) => 'D, j M y',
									__( 'Tuesday, 7 Apr', 'mstw-loc-domain' ) => 'l, j M',
									__( 'Tuesday, 07 April 2013', 'mstw-loc-domain' ) => 'l, d F Y',
									__( 'Tuesday, 7 April 2013', 'mstw-loc-domain' ) => 'l, j F Y',
									__( 'Tues, 07 Apr', 'mstw-loc-domain' ) => 'D, d M',
									__( 'Tues, 7 Apr', 'mstw-loc-domain' ) => 'D, j M',
									__( '07 Apr', 'mstw-loc-domain' ) => 'd M',
									__( '7 Apr', 'mstw-loc-domain' ) => 'j M',
									);
									
				if ( isset( $custom_format ) && $custom_format == 0 ) {
					//remove the custom option
					unset( $options[ __( 'Custom', 'mstw_loc_domain' ) ] );
				}
				if ( $desc == '' ) {
					$desc = __( 'Formats for 7 Apr 2013. Default: 2013-04-07', 'mstw-loc-domain' );
				}
				break;
			case 'time-only':
				$type = 'select-option';
				$options = array ( 	__( 'Custom', 'mstw-loc-domain' ) 	=> 'custom',
									__( '08:00 (24hr)', 'mstw-loc-domain' ) => 'H:i',
									__( '8:00 (24hr)', 'mstw-loc-domain' ) 	=> 'G:i',
									__( '08:00 am', 'mstw-loc-domain' ) 	=> 'h:i a',
									__( '08:00 AM', 'mstw-loc-domain' ) 	=> 'h:i A',
									__( '8:00 am', 'mstw-loc-domain' ) 		=> 'g:i a',
									__( '8:00 AM', 'mstw-loc-domain' ) 		=> 'g:i A',
									);
									
				if ( isset( $custom_format ) && $custom_format == 0 ) {
					//remove the custom option
					unset( $options[ __( 'Custom', 'mstw_loc_domain' ) ] );
				}
				if ( $desc == '' ) {
					$desc = __( 'Formats for eight in the morning. Default: 08:00', 'mstw-loc-domain' );
				}
				break;
			default:
				break;
								
		}
		
		//
		// map arguments used by mstw_display_form_field() to create HTML output 
		//
		$field_args = array(
			'type'       => $type,
			'id'         => $id,
			'desc'       => $desc,
			'curr_value' => $value,
			'options'    => $options,
			'label_for'  => $id,
			'class'      => $class,
			'name'		 => $name,
		);
		
		add_settings_field( $id, 
			$title, 
			'mstw_build_admin_edit_field', 
			$page, 
			$section, 
			$field_args 
			);
		
	} //End: mstw_build_settings_field()
}

//-------------------------------------------------------------------------------
// 10. mstw_sanitize_hex_color - validates/sanitizes (3 or 6 digit) hex colors
//		Returns input string if valid hex color (or ''); returns null otherwise		
//
if( !function_exists( 'mstw_sanitize_hex_color' ) ) {
	function mstw_sanitize_hex_color( $color ) {
		// the empty string is ok
		if ( '' === $color )
			return '';

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;
		
		// return null if input $color is not valid
		return null;
		
	} //End: mstw_sanitize_hex_color()
}

//-------------------------------------------------------------------------------
// 11. mstw_is_valid_url - validates a url using both filter_var() & preg_match()
//		ARGUMENTS: $url - the url to validate
//		RETURNS: true if valid, false otherwise		
//
if( !function_exists( 'mstw_is_valid_url' ) ) {
	function mstw_is_valid_url( $url ) {
		if( filter_var( $url, FILTER_VALIDATE_URL ) ) { 
			if ( preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url ) ) 
				return true;
			else // preg_match failed
				return false;
		} else { // filter_var() failed
			return false;
		}
	} //End: mstw_is_valid_url()
}

//-------------------------------------------------------------------------------
// 12. mstw_validate_url - convenience function to handle url validation in a CPT
//			save_post (validation) callback
//		ARGUMENTS: 
//			$data_array: array of data (normally $_POST)
//			$key: key into the data array
//			$post_id: post ID to update if URL is valid
//			$notice_type: updated | error | nag; defaults to error
//			$notice: base of the admin notice; $key is appended to end 
//		RETURNS: true if valid/update, false otherwise (error generated)		
//
if( !function_exists( 'mstw_validate_url' ) ) {
	function mstw_validate_url( $data_array, $key, $post_id, $notice_type = 'error', $notice = 'Invalid URL:' ) {
		// grab the data from the array (or '' if it doesn't exist)
		$url = mstw_safe_ref( $data_array, $key );
		
		if( empty( $url ) or mstw_is_valid_url( $url ) ) {
			// url is empty or is valid, so update the DB
			update_post_meta( $post_id, $key, esc_url( $url ) );
			
		} else { 
			// url is not valid, display an error message (dont' update DB)
			$notice .= ' ' . $url;			
			if ( function_exists( 'mstw_add_admin_notice' ) ) {
				mstw_add_admin_notice( 'mstw_admin_messages', $notice_type, $notice );
			}
		}
	} //End: mstw_validate_url( )
}

//-------------------------------------------------------------------------------
// 13. mstw_get_current_post_type - get the current post type in the WordPress Admin
//		ARGUMENTS: 
//			None. But uses global variables: $post, $typenow, and $current_screen 
//		RETURNS: post type on success or null on failure		
//
// Thanks to Brad Vincent for this function
// http://themergency.com/wordpress-tip-get-post-type-in-admin/
//
if ( !function_exists( 'mstw_get_current_post_type' ) ) {
	function mstw_get_current_post_type( ) {
	global $post, $typenow, $current_screen;
	 
		if ( $post and $post->post_type ) // see note below snippet
			return $post->post_type;
	 
		elseif( $typenow )
			return $typenow;
	 
		elseif( $current_screen and $current_screen->post_type )
			return $current_screen->post_type;
	 
		elseif( isset( $_REQUEST['post_type'] ) )
			return sanitize_key( $_REQUEST['post_type'] );
			
		else
			return null;
			
	} //End: mstw_get_current_post_type( )
}

//-------------------------------------------------------------------------------
// 14. mstw_get_the_slug - get the post slug from the post id
//		ARGUMENTS: 
//			post_id: the post id 
//		RETURNS: the post slug (post_name) or null if none is found		
//
// Thanks to Brad Vincent for this function
// http://themergency.com/wordpress-tip-get-post-type-in-admin/
//
if ( !function_exists( 'mstw_get_the_slug' ) ) {
	function mstw_get_the_slug( $post_id ) {
	
		$post_data = get_post( $post_id, ARRAY_A );
		$slug = $post_data['post_name'];
		
		return $slug;
			
	} //End: mstw_get_the_slug( )
}

//-------------------------------------------------------------------------------
// 15. mstw_has_admin_rights - check if the CURRENT USER has mstw admin rights
//		ARGUMENTS: 	none
//		RETURNS: 	true if the current user is a WP admin or an MSTW Admin;
//				 	false otherwise	
//
if ( !function_exists( 'mstw_has_admin_rights' ) ) { 	
	function mstw_has_admin_rights( ) {
		if ( current_user_can( 'edit_theme_options' )  or 
			 current_user_can( 'manage_mstw_plugins' ) ) {
			return true;
		}
		
		return false;
		
	} //End: mstw_has_admin_rights( )
}

//-------------------------------------------------------------------------------
// 16. mstw_has_plugin_rights - check if the CURRENT USER has 
//							Schedules & Scoreboards admin rights
//		ARGUMENTS: 	$plugin - plugin abbreviation - 'tr', 'ss', 'cs', 'ls', //							  'csvx', etc.
//		RETURNS: 	true if the current user has rights
//				 	false otherwise	
//
if ( !function_exists( 'mstw_user_has_plugin_rights' ) ) { 	
	function mstw_user_has_plugin_rights( $plugin = 'ss' ) {
		
		if ( current_user_can( 'edit_others_posts' ) or  //WP admins and editors
			 current_user_can( 'view_mstw_menus' ) or    //MSTW admins
			 current_user_can( 'view_mstw_' . $plugin . '_menus' )    //plugin admins
			 ) {
			return true;
		}
		return false;
		
	} //End: mstw_user_has_plugin_rights( )
}

//-------------------------------------------------------------------------------
// 16.1 mstw_user_has_ss_rights - check if the CURRENT USER has 
//							Schedules & Scoreboards admin rights
//		ARGUMENTS: 	none
//		RETURNS: 	true if the current user has rights
//				 	false otherwise	
//
// THIS IS HERE FOR COMPATIBILITY PURPOSES ONLY. 
// IT CALLS mstw_user_has_plugin_rights()
//
if ( !function_exists( 'mstw_user_has_ss_rights' ) ) { 	
	function mstw_user_has_ss_rights( ) {
		return mstw_user_has_plugin_rights( 'ss' );
	} //End: mstw_user_has_ss_rights( )
}

//----------------------------------------------------------------
// 17. mstw_admin_notice - Displays all admin notices; callback for admin_notices action
//		ARGUMENTS: 	$transient - transient where messages are stored
//		RETURNS:	None. Displays all messages in the $transient transient
//					(then deletes it)
//
if ( !function_exists ( 'mstw_admin_notice' ) ) {
	function mstw_admin_notice( $transient = 'mstw_admin_messages' ) {
		if ( get_transient( $transient ) !== false ) {
			// get the types and messages
			$messages = get_transient( $transient );
			// display the messages
			foreach ( $messages as $message ) {
				$msg_type = $message['type'];
				$msg_notice = $message['notice'];
				
				// Kludge to get warning messages to appear after page title
				$msg_type = ( $msg_type == 'warning' ) ? $msg_type . ' updated' : $msg_type ;
			?>
				<div class="<?php echo $msg_type; ?>">
					<p><?php echo $msg_notice; ?></p>
				</div>
			
			<?php
			}

			delete_transient( $transient );
			
		} //End: if ( get_transient( $transient ) )
	} //End: function mstw_admin_notice( )
}

//----------------------------------------------------------------
// 18. mstw_add_admin_notice - Adds admin notices to transient for display on admin_notices hook
//
//	ARGUMENTS: 	$transient - transient to store message
//				$type - type of notice [updated|error|update-nag|warning]
//				$notice - notice text
//
//	RETURNS:	None. Stores notice and type in transient for later display on admin_notices hook
//
if ( !function_exists ( 'mstw_add_admin_notice' ) ) {
	function mstw_add_admin_notice( $transient = 'mstw_admin_messages', $type = 'updated', $notice ) {
		//default type to 'updated'
		if ( !( $type == 'updated' or $type == 'success' or $type == 'error' or $type =='update-nag' or $type == 'warning' ) ) $type = 'updated';
		
		//set the admin message
		$new_msg = array( array(
							'type'	  => "notice notice-$type",
							'notice'	=> $notice
							)
						);

		//either create or add to the sss_admin transient
		$existing_msgs = get_transient( $transient );
		
		if ( $existing_msgs === false ) {
			// no transient exists, create it with the current message
			set_transient( $transient, $new_msg, HOUR_IN_SECONDS );
		} 
		else {
			// transient exists, append current message to it
			$new_msgs = array_merge( $existing_msgs, $new_msg );
			set_transient ( $transient, $new_msgs, HOUR_IN_SECONDS );
		}
	} //End: function mstw_add_admin_notice( )
}

//------------------------------------------------------------------------
// 19. mstw_add_role_caps - adds the MSTW capabilities to either the $role_obj or 
//		$role_name using the custom post type names (from the capability_type 
//		arg in register_post_type( )
//
//	ARGUMENTS:
//		$role_obj: a WP role object to which to add the MSTW capabilities. Will
//					be used of $role_name is none (the default)
//		$role_name: a WP role name to which to add the MSTW capabilities. Will
//					be used if present (not null)
//		$cpt: the custom post type for the capabilities 
//				( map_meta_cap is set in register_post_type() )
//		$cpt_s: the plural of the custom post type
//				( $cpt & $cpt_s must match the capability_type argument
//					in register_post_type( ) )
//	RETURN: none
//
if( function_exists( 'mstw_add_role_caps' ) ) {
	function mstw_add_role_caps( $role_obj = null, $role_name = null, $cpt, $cpt_s ) {
		
		$cap = array( 'edit_', 'read_', 'delete_' );
		$caps = array( 'edit_', 'edit_others_', 'publish_', 'read_private_', 'delete_', 'delete_published_', 'delete_others_', 'edit_private_', 'edit_published_' );
		
		if ( $role_name != null ) {
			$role_obj = get_role( $role_name );
		}
		
		if( $role_obj != null ) {
			//'singular' capabilities
			foreach( $cap as $c ) {
				$role_obj -> add_cap( $c . $cpt );
			}
			
			//'plural' capabilities
			foreach ($caps as $c ) {
				$role_obj -> add_cap( $c . $cpt_s );
			}
			
			$role_obj -> add_cap( 'read' );
		}
		else {
			$role_name = ( $role_name == null ) ? 'null' : $role_name;
			if( function_exists( 'mstw_log_msg' ) ) {
				mstw_log_msg( 'Bad args passed to mstw_add_role_caps( ). $role_name = ' . $role_name . ' and $role_obj = null' );
			}
		}
		
	} //End: mstw_add_role_caps( )
}
?>