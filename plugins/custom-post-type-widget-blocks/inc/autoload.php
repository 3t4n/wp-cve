<?php
/**
 * Autoloader
 *
 * @package Custom_Post_Type_Widget_Blocks
 * @since 1.0.0
 */

/**
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt
 * to load the \Custom_Post_Type_Widget_Blocks\Blocks\Custom_Post_Type_Widget_Blocks_Foo class
 * from /dist/blocks/foo/class-bar.php:
 *     new \Custom_Post_Type_Widget_Blocks\Blocks\Custom_Post_Type_Widget_Blocks_Foo;
 *
 * @param string|mixed
 * @return void
 */
spl_autoload_register( // @phpstan-ignore-line
	/**
	 * Registed autoload function
	 *
	 * @param string $class The fully-qualified class name.
	 * @return void
	 */
	function( $class ) {
		/* plugin-specific namespace prefix */
		$prefix = 'Custom_Post_Type_Widget_Blocks\\';
		$len    = strlen( $prefix );

		if ( 0 !== strncmp( $prefix, $class, $len ) ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$relative_class = str_replace( '\\', '/', $relative_class );

		/**
		 * WordPress Naming Conventions
		 * See https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#naming-conventions
		 */
		$relative_class = strtolower( $relative_class );
		$relative_class = str_replace( '_', '-', $relative_class );

		if ( preg_match( '/^custom-post-type-widget-blocks$/', $relative_class ) ) {
			// load main class from /inc.
			$relative_class = preg_replace( '/^(.*)$/', 'inc/class-$1', $relative_class );
		}
		elseif ( preg_match( '/^blocks\/custom-post-type-widget-blocks-/', $relative_class ) ) {
			// load blocks class from /dist/blocks/foo.
			$relative_class = preg_replace( '/^blocks\/(custom-post-type-widget-blocks-(.*?))$/', 'dist/blocks/$2/class-$1', $relative_class );
		}

		$path = plugin_dir_path( CUSTOM_POST_TYPE_WIDGET_BLOCKS ) . $relative_class . '.php';

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
);
