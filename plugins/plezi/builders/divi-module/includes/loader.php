<?php
if ( ! class_exists( 'ET_Builder_Element' ) ) :
	return;
endif;

$module_files = glob( __DIR__ . '/modules/*/*.php' );

foreach ( (array) $module_files as $module_file ) :
	if ( $module_file && preg_match( "/\/modules\/\b([^\/]+)\/\\1\.php$/", $module_file ) ) :
		require_once $module_file;
	endif;
endforeach;
