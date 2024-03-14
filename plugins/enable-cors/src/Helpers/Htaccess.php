<?php //phpcs:ignore

namespace Enable\Cors\Helpers;

/*
|--------------------------------------------------------------------------
| If this file is called directly, abort.
|--------------------------------------------------------------------------
*/

use Enable\Cors\Traits\Singleton;
use const Enable\Cors\NAME;

if ( ! defined( 'Enable\Cors\NAME' ) ) {
	exit;
}


final class Htaccess {
	use Singleton;

	private $filesystem;

	private function __construct() {
		if ( null === $this->filesystem ) {
			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$this->filesystem = $wp_filesystem;
		}
	}

	/**
	 * It modifies the .htaccess file to add headers for allowing fonts and css
	 */
	public function modify() {
		$option = new Option();
		$origin = false;
		if ( $option->has_wildcard() ) {
			$origin = 'Header set Access-Control-Allow-Origin "*"';
		} else {
			$domains = $option->get_domains();
			if ( ! empty( $domains ) ) {
				$origin  = 'SetEnvIf Origin "^http(s)?://(.+\.)?(' . implode( '|', $domains ) . ')$" origin_is=$0' . PHP_EOL;
				$origin .= 'Header always set Access-Control-Allow-Origin %{origin_is}e env=origin_is';
			}
		}

		if ( $option->is_allow_credentials() ) {
			$credentials = 'Header set Access-Control-Allow-Credentials "true"';
		} else {
			$credentials = 'Header set Access-Control-Allow-Credentials "false"';
		}
		if ( empty( $origin ) ) {
			return;
		}
		$lines = array(
			'<IfModule mod_headers.c>',
			'<FilesMatch "\.(ttf|ttc|otf|eot|woff|font.css|css|woff2)$">',
			$origin,
			$credentials,
			'</FilesMatch>',
			'<FilesMatch "\.(avifs?|bmp|cur|gif|ico|jpe?g|jxl|a?png|svgz?|webp)$">',
			$origin,
			$credentials,
			'</FilesMatch>',
			'</IfModule>',
		);

		// Ensure get_home_path() is declared.
		$this->write( $lines );
	}

	/**
	 * Inserts an array of strings into a file (.htaccess), placing it between
	 * BEGIN and END markers.
	 *
	 * @param array $lines need to write.
	 *
	 * @return void
	 */
	private function write( array $lines ): void {
		// Ensure get_home_path() is declared.
		if ( ! function_exists( 'get_home_path' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( ! function_exists( 'insert_with_markers' ) || ! function_exists( 'got_mod_rewrite' ) ) {
			require_once ABSPATH . 'wp-admin/includes/misc.php';
		}

		$htaccess_file = get_home_path() . '.htaccess';

		if ( got_mod_rewrite() ) {
			insert_with_markers( $htaccess_file, NAME, $lines );
		}
	}

	/**
	 * It writes an empty array to the .htaccess file.
	 */
	public function restore() {
		$lines = array( '' );
		$this->write( $lines );
	}
}
