<?php
namespace ADTW;

defined( 'ABSPATH' ) || exit;

class Autoloader
{

	private static $namespace_root = 'ADTW\\';

	public static function exec()
	{
		spl_autoload_register([ __CLASS__, 'autoload' ]);
	}
    
	private static function autoload($resource = '')
	{
        
        $resource_path  = false;
		$resource       = trim( $resource, '\\' );
        
		if ( empty( $resource ) || strpos( $resource, '\\' ) === false || strpos( $resource, self::$namespace_root ) !== 0 ) {
            return;
		}
        
		// Remove our root namespace.
		$resource = str_replace( self::$namespace_root, '', $resource );
        
		$path = explode(
            '\\',
			str_replace( '_', '-', $resource)
		);
        //wp_die(print_r($path,true));
        
		if ( empty( $path[0] )) {
			return;
		}
		$file_name = $path[0];
		$resource_path = sprintf( '%s/inc/%s.php', untrailingslashit( ADTW_PATH ),$file_name );
		$is_valid_file = validate_file( $resource_path );

		if ( ! empty( $resource_path ) && file_exists( $resource_path ) && ( 0 === $is_valid_file || 2 === $is_valid_file ) ) {
			require_once( $resource_path ); // phpcs:ignore
		}

	}

}