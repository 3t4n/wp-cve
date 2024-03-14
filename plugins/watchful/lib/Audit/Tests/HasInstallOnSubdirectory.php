<?php
/**
 * Watchful install on subdirectory test.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit\Tests;

use Watchful\Audit\Audit;
use Watchful\Audit\Files\RecursiveListing;

/**
 * Watchful install on subdirectory test class.
 */
class HasInstallOnSubdirectory extends Audit {

	/**
	 * The file system structure.
	 *
	 * @var stdClass
	 */
	private $structure;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		parent::__construct();
		$recursive_listing = new RecursiveListing();
		$this->structure   = $recursive_listing->get_structure( ABSPATH );
	}

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$elements = $this->structure->files;
		$paths    = array();

		$escaped_base_path = preg_replace( array( '#\/#', '#\.#' ), array( '\/', '\.' ), ABSPATH );
		$pattern           = '#^' . $escaped_base_path . '([a-z0-9_\-\.\s]*\/){1,2}wp-config\.php$#i';

		foreach ( $elements as $element ) {
			if ( preg_match( $pattern, $element ) && $this->isAWordpressConfigFile( $element ) ) {
				$relative_path = str_replace( ABSPATH, '', $element );
				$paths[]       = preg_replace( '#wp-config.php$#', '', $relative_path );
			}
		}

		if ( count( $paths ) ) {
			return $this->response->send_ko( $paths );
		}

		return $this->response->send_ok();
	}

	/**
	 * Check if the file is a WordPress config file
	 *
	 * @param string $file_path The file path.
	 *
	 * @return boolean
	 */
	private function isAWordpressConfigFile( $file_path ) {
		$content = implode( '', file( $file_path, FILE_IGNORE_NEW_LINES ) );
		return stristr( $content, "define('ABSPATH'" );
	}
}
