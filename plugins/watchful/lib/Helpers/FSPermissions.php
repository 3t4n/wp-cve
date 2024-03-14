<?php
/**
 * Helper for managing file system permissions.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Helpers;

use Watchful\Exception;

/**
 * Class File System Permissions helpers.
 */
class FSPermissions {

	/**
	 * File system permissions.
	 *
	 * @var string
	 */
	private $permissions = null;

	/**
	 * FSPermissions constructor.
	 *
	 * @param string $permissions Permission in octal format (0644).
	 *
	 * @throws \Exception If permissions are invalid.
	 */
	public function __construct( $permissions ) {
		if ( strlen( $permissions ) < 4 ) {
			$permissions = 0 . $permissions;
		}

		if ( 4 !== strlen( $permissions ) ) {
			throw new \Exception( 'FSPermissions - invalid permissions format : ' . $permissions );
		}

		$this->permissions = $permissions;
	}

	/**
	 * Get permission in Unix 3 digit format (644)
	 *
	 * @return string
	 */
	public function get_unix() {
		return substr( $this->permissions, 1, 3 );
	}

	/**
	 * Get permission in Unix 4 digit format (0644)
	 *
	 * @return string
	 */
	public function get_unix_full() {
		return $this->permissions;
	}

	/**
	 * Check for user,group & others if rights are bigger that max allowed rights
	 * e.g.:
	 *       - 0644 max=644   --> false
	 *       - 0644 max=700   --> true
	 *       - 1644 max=0664  --> true
	 *       - 1644 max=664   --> false
	 *       - 0644 max=0644  --> false
	 *       - 1644 max=1644  --> false
	 *       - 4644 max=1644  --> true
	 *
	 * @param int $max_allow_permissions Permission in octal format (0644).
	 *
	 * @return bool
	 */
	public function is_higher( $max_allow_permissions ) {
		$perms     = str_split( $this->permissions );
		$max_perms = str_split( $max_allow_permissions );

		if ( 3 === count( $max_perms ) ) {
			array_shift( $perms );
		}

		foreach ( $perms as $key => $perm ) {
			if ( $perm > $max_perms[ $key ] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Create new FSPermissions object with rights of provided PATH
	 *
	 * @param string $path The path on which to set the permissions.
	 *
	 * @return bool|FSPermissions
	 *
	 * @throws \Exception If the path is not readable.
	 */
	public static function from_path( $path ) {
		if ( ! is_readable( $path ) ) {
			throw new \Exception( 'FSPermissions - unreadable file : ' . $path );
		}

		return new self( substr( sprintf( '%o', fileperms( $path ) ), -4 ) );
	}

}
