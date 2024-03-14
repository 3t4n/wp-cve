<?php
/**
 * Make sure the Composer\Autoload namespace is prefixed.
 * Needs to be run after php-scoper.
 *
 * `php ./patch-scoper-autoloader-namespace.php MY_PREFIX`
 */

// if ( empty( $argv[1] ) ) {
// 	return;
// }
// $prefix      = $argv[1];

$prefix      = 'LassoLiteVendor';
$scoper_path = './vendor-prefix/vendor/composer';
$vendor_path = './vendor-prefix/vendor';
$sentry_path = $vendor_path . '/sentry/sentry/src/';

prefix_namespace_in_autoloader_file( $scoper_path . '/autoload_static.php', $prefix );
prefix_namespace_in_autoloader_file( $scoper_path . '/autoload_real.php', $prefix );
prefix_namespace_in_autoloader_file( $scoper_path . '/ClassLoader.php', $prefix );

fix_symfony_options_resolver( $vendor_path, $prefix );
fix_composer_namespace( $scoper_path . '/autoload_static.php', $prefix );
fix_composer_namespace( $scoper_path . '/autoload_classmap.php', $prefix );
fix_composer_load_file( $scoper_path . '/autoload_real.php', $prefix );
fix_sentry_stringable_not_found( $sentry_path );
// fix_composer_namespace_general_issues();
update_identified_in_autoload_files( $prefix, $scoper_path );

function prefix_namespace_in_autoloader_file( $file, $prefix ) {
	$path     = $file;
	$contents = file_get_contents( $path );
	$contents = str_replace( '\\Composer\\Autoload', '\\' . $prefix . '\\Composer\\Autoload', $contents );
	$contents = str_replace( 'namespace Composer\\Autoload', 'namespace ' . $prefix . '\\Composer\\Autoload', $contents );
	$contents = str_replace( '\'Composer\\Autoload\\ClassLoader\'', '\'' . $prefix . '\\Composer\\Autoload\\ClassLoader\'', $contents );
	$contents = str_replace( '= Composer\\Autoload', '= ' . $prefix . '\\Composer\\Autoload', $contents );
	file_put_contents( $path, $contents );
}

function fix_symfony_options_resolver($vendor_path,  $prefix) {
	$polyfills = [
		'/symfony/polyfill-php73/bootstrap.php',
		'/symfony/polyfill-php80/bootstrap.php',
		'/symfony/polyfill-uuid/bootstrap.php',
		'/symfony/polyfill-uuid/bootstrap80.php',
	];

	foreach($polyfills as $polyfill) {
		$path = $vendor_path . $polyfill;
		$contents = file_get_contents( $path );
		$contents = str_replace( 'namespace', '// namespace', $contents );
		$contents = str_replace( 'LassoLiteVendor\\\\APCuIterator', 'APCuIterator', $contents );
		$contents = str_replace( 'LassoLiteVendor\\\\array_is_list', 'array_is_list', $contents );
		$contents = str_replace( 'LassoLiteVendor\\\\enum_exists', 'enum_exists', $contents );
		file_put_contents( $path, $contents );
	}
}

function fix_composer_namespace($file,  $prefix) {
	$prefix = str_replace( '\\', '\\\\', $prefix );;
	$path     = $file;
	$contents = file_get_contents( $path );
	$contents = str_replace( 'Composer\\\\InstalledVersions', $prefix . '\\\\Composer\\\\InstalledVersions', $contents );
	file_put_contents( $path, $contents );
}

function fix_composer_load_file($file,  $prefix) {
	$path     = $file;
	$contents = file_get_contents( $path );

	$regex = "/(if\s\(empty\()(.*)({\s*)(.*)(\s*)(.*)(\s*)(})/i";
	preg_match_all( $regex, $contents, $matches );
	$contents        = preg_replace( $regex, '$4$5$6', $contents );

	$contents = str_replace( 'require $file', 'require_once $file', $contents );

	$ignore_iconv = "
		foreach (\$includeFiles as \$fileIdentifier => \$file) { \n
			if ( strpos( \$file, 'Iconv' ) !== false ) { 
				continue;
			} \n
	";
	$contents = str_replace( 'foreach ($includeFiles as $fileIdentifier => $file) {', $ignore_iconv, $contents );

	file_put_contents( $path, $contents );
}

function fix_composer_namespace_general_issues() {
	global $vendor_path;

	$path = $vendor_path . '/guzzlehttp/psr7/src/functions.php';
	$contents = file_get_contents( $path );
	$contents = str_replace( "namespace LassoLiteVendor\\GuzzleHttp\\Psr7;",
		"namespace LassoLiteVendor\\GuzzleHttp\\Psr7; \nnamespace GuzzleHttp\\Psr7;"
		, $contents );
	file_put_contents( $path, $contents );
}

function update_identified_in_autoload_files( $prefix, $scoper_path ) {
	$autoload_files_path = dirname(__FILE__) . '/' . $scoper_path . '/autoload_files.php';
	$autoload_static_path = dirname(__FILE__) . '/' . $scoper_path . '/autoload_static.php';
	$autoload_files = include $autoload_files_path;

	$autoload_files_content  = file_get_contents( $autoload_files_path );
	$autoload_static_content = file_get_contents( $autoload_static_path );
	foreach ($autoload_files as $identifier => $load_file_path) {
		$new_identifier = $prefix . '_' . substr( $identifier, (strpos( $identifier, '_' ) !== false ? strpos( $identifier, '_' ) + 1 : 0));
		$autoload_files_content = str_replace( $identifier, $new_identifier, $autoload_files_content );
		$autoload_static_content = str_replace( $identifier, $new_identifier, $autoload_static_content );
	}

	file_put_contents( $autoload_files_path, $autoload_files_content );
	file_put_contents( $autoload_static_path, $autoload_static_content );
}

function fix_sentry_stringable_not_found( $sentry_path ) {
	$file_folders = scandir( $sentry_path );

	unset( $file_folders[array_search( '.', $file_folders, true )] );
	unset( $file_folders[array_search( '..', $file_folders, true )] );

	// prevent empty ordered elements
	if ( count( $file_folders ) < 1 )
		return;

	foreach( $file_folders as $file_folder ) {
		if( is_dir ( $sentry_path . '/' . $file_folder ) ) {
			fix_sentry_stringable_not_found( $sentry_path . '/' . $file_folder );
		} else {
			$path     = $sentry_path . '/' . $file_folder;
			$contents = file_get_contents( $path );
			$contents = str_replace( ' implements \\Stringable', '', $contents );
			file_put_contents( $path, $contents );
		}
	}
}
