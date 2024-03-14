<?PHP
/*****************************
* remove the options
*****************************/
// Check that we should be doing this
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit; // Exit if accessed directly
}
delete_option('astound_options' );
delete_option('astound_cache' );

?>