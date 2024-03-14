<?php
/*	jonradio Common Functions,
	intended for use in more than one jonradio plugin,
	and others are encouraged to use for their own purposes.
	See details below license.
*/

/*  Copyright 2014  jonradio  (email : info@zatz.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*	Concept and Usage
	Each function name is prefixed with jr_v followed by a Version Number (integer) then another underscore
	then the function name.
	Each function is preceded by a check for previous existence,
	so that multiple plugins can use the same function without generating duplicate function definition errors.
	By incorporating the Version Number into the function name, there is no danger of a plugin using the wrong version.
	Standard usage is to have all these functions stored in each plugin's folder as /includes/common-functions.php
	Each function has its own Version Number, which only increases when the function actually changes;
	which means that common-functions.php will normally include many different version numbers in its functions;
	i.e. - the version number applies independently to each function, not to the common-functions.php file as a whole.
*/

/*	Exit if .php file accessed directly
*/
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check for missing Settings and set them to defaults
 * 
 * Ensures that the Named Setting exists, and populates it with defaults for any missing values.
 * Removes any Settings not found in the Defaults provided.
 * Safe to use on every execution of a plugin because it only does an expensive Database Write
 * when it finds missing Settings.
 *
 * @param	string	$name		Name of Settings as looked up with get_option(), get_site_option or get_blog_option
 * @param	array	$defaults	Each default Settings value in [key] => value format
 * @param	int		$blog_id	(optional) Which Site of a WordPress Network ("Multi-Site")?
 *								0 = not a Network
 *								-1 = Network Settings
 * @return  bool/Null			Return value from update_option(), or NULL if update_option() not called
 */
if ( !function_exists( 'jr_v1_validate_settings' ) ) {
	function jr_v1_validate_settings( $name, $defaults, $blog_id = 0 ) {
		switch ( $blog_id ) {
			case 0:
				$settings = get_option( $name );
				break;
			case -1:
				$settings = get_site_option( $name );
				break;
			default:
				$settings = get_blog_option( $blog_id, $name );
		}
		
		$updated = FALSE;
		if ( FALSE === $settings ) {
			$settings = $defaults;
			$updated = TRUE;
		} else {
			/*	Add any missing Settings, and set to Default values.
			*/
			foreach ( $defaults as $key => $value ) {
				if ( !isset( $settings[$key] ) ) {
					$settings[$key] = $value;
					$updated = TRUE;
				} else {
					if ( is_array( $value ) ) {
						foreach ( $value as $key2 => $value2 ) {
							if ( !isset( $settings[$key][$key2] ) ) {
								$settings[$key][$key2] = $value;
								$updated = TRUE;
							}
						}
					}
				}
			}
			/*	Remove any Settings not found in Defaults provided.
			*/
			foreach ( $settings as $key => $value ) {
				if ( !isset( $defaults[$key] ) ) {
					unset( $settings[$key] );
					$updated = TRUE;
				} else {
					if ( is_array( $value ) ) {
						foreach ( $value as $key2 => $value2 ) {
							if ( !isset( $defaults[$key][$key2] ) ) {
								$defaults[$key][$key2] = $value;
								$updated = TRUE;
							}
						}
					}
				}
			}
		}
		
		if ( $updated ) {
			switch ( $blog_id ) {
				case 0:
					$return = update_option( $name, $settings );
					break;
				case -1:
					$return = update_site_option( $name, $settings );
					break;
				default:
					$return = update_blog_option( $blog_id, $name, $settings );
			}
		} else {
			$return = NULL;
		}
		return $return;
	}
}

?>