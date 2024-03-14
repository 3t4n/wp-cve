<?php

$module_files = array_merge(
	glob( __DIR__ . '/modules/*/*.php' ),
	glob( __DIR__ . '/modules/*/*/*.php' )
);

// Load custom Divi Builder modules
foreach ( (array) $module_files as $module_file ) {

	if ( $module_file ) {

		require_once $module_file;

	}

}
