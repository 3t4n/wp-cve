<?php
/**
 * wp247 Settings API wrapper class
 *
 * @version 2.0
 *
 */

// Don't allow direct execution
function_exists( 'add_action' ) or die ( 'Forbidden' );

/* Skip namespace usage due to errors
namespace wp247sapi;
*/

/* Skip namespace usage due to errors
if ( !class_exists( '\wp247sapi\WP247_Settings_API' ) )
*/
if ( !class_exists( 'WP247_Settings_API_2' ) )
{
	require_once 'wp247-settings-api.class.php';
}