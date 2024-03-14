<?php
/**
 * Name:    Dev4Press\v43\Core\Helpers\HTAccess
 * Version: v4.3
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4Press Library
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

// phpcs:ignoreFile WordPress.WP.AlternativeFunctions

namespace Dev4Press\v43\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTAccess {
	public $begin = 'BEGIN';
	public $end = 'END';

	public $path = '';

	public function __construct( $path = '' ) {
		$this->path = $path == '' ? ABSPATH . '.htaccess' : $path;
	}

	public function is_writable() : bool {
		return is_writable( $this->path );
	}

	public function file_exists() : bool {
		return file_exists( $this->path );
	}

	public function load() {
		if ( $this->file_exists() ) {
			return explode( PHP_EOL, implode( '', file( $this->path ) ) );
		} else {
			return array();
		}
	}

	public function remove( $marker, $cleanup = false, $backup = false ) : bool {
		return $this->insert( $marker, array(), 'end', $cleanup, $backup );
	}

	public function insert( $marker, $insertion = array(), $location = 'end', $cleanup = false, $backup = false ) : bool {
		if ( ! $this->file_exists() || $this->is_writable() ) {
			if ( ! $this->file_exists() ) {
				$marker_data = '';
			} else {
				$marker_data = $this->load();
			}

			if ( $backup ) {
				$backup_path = $this->path . '.backup';

				if ( file_exists( $backup_path ) ) {
					wp_delete_file( $backup_path );
				}

				copy( $this->path, $backup_path );
			}

			$f = fopen( $this->path, 'w' );

			if ( $f === false ) {
				return false;
			}

			$result = true;
			if ( flock( $f, LOCK_EX ) ) {
				if ( $location == 'start' ) {
					$this->write( $f, $marker, $insertion );

					$insertion = array();
				}

				if ( $marker_data ) {
					$state = true;

					foreach ( $marker_data as $marker_line ) {
						if ( strpos( $marker_line, '# ' . $this->begin . ' ' . $marker ) !== false ) {
							$state = false;
						}

						if ( $state ) {
							fwrite( $f, $marker_line . PHP_EOL );
						}

						if ( strpos( $marker_line, '# ' . $this->end . ' ' . $marker ) !== false ) {
							$state = true;
						}
					}
				}

				if ( $location == 'end' ) {
					$this->write( $f, $marker, $insertion );
				}

				fflush( $f );
				flock( $f, LOCK_UN );
			} else {
				$result = false;
			}

			fclose( $f );

			if ( $cleanup ) {
				$this->cleanup();
			}

			return $result;
		} else {
			return false;
		}
	}

	public function write( $f, $marker, $insertion = array() ) {
		if ( is_array( $insertion ) && ! empty( $insertion ) ) {
			fwrite( $f, PHP_EOL . '# BEGIN ' . $marker . PHP_EOL );

			foreach ( $insertion as $insert_line ) {
				fwrite( $f, $insert_line . PHP_EOL );
			}

			fwrite( $f, '# END ' . $marker . PHP_EOL );
		}
	}

	public function cleanup() : bool {
		if ( $this->file_exists() && $this->is_writable() ) {
			$marker_data = $this->load();

			$f = fopen( $this->path, 'w' );

			if ( $f === false ) {
				return false;
			}

			if ( flock( $f, LOCK_EX ) ) {
				$modded_data = array();

				$line_start  = 0;
				$line_end    = 0;
				$marker_size = count( $marker_data );

				for ( $i = 0; $i < $marker_size; $i ++ ) {
					if ( ! empty( $marker_data[ $i ] ) ) {
						$line_start = $i;
						break;
					}
				}

				for ( $i = $marker_size - 1; $i > 0; $i -- ) {
					if ( ! empty( $marker_data[ $i ] ) ) {
						$line_end = $i;
						break;
					}
				}

				$blocked = false;
				for ( $i = $line_start; $i < $line_end + 1; $i ++ ) {
					$add_line = true;
					$end_line = false;

					$marker_line = $marker_data[ $i ];

					if ( $blocked ) {
						if ( ! empty( $marker_line ) ) {
							$blocked = false;
						} else {
							$add_line = false;
						}
					}

					if ( substr( $marker_line, 0, 5 ) == '# END' ) {
						$end_line = true;
						$blocked  = true;
					}

					if ( $add_line ) {
						$modded_data[] = $marker_line;

						if ( $end_line ) {
							$modded_data[] = '';
						}
					}
				}

				foreach ( $modded_data as $marker_line ) {
					fwrite( $f, $marker_line . PHP_EOL );
				}

				fflush( $f );
				flock( $f, LOCK_UN );
			} else {
				return false;
			}

			fclose( $f );

			return true;
		}

		return false;
	}

	public function check() : array {
		global $is_apache;

		$mods = $is_apache && function_exists( 'apache_get_modules' ) ? apache_get_modules() : array();

		$check = array(
			'is_apache'          => $is_apache,
			'file'               => '.htaccess',
			'htaccess'           => $this->path,
			'found'              => $is_apache && $this->file_exists(),
			'writable'           => $is_apache && $this->is_writable(),
			'automatic'          => false,
			'apache_get_modules' => ! empty( $mods ),
			'mod_rewrite'        => in_array( 'mod_rewrite', $mods ),
			'mod_alias'          => in_array( 'mod_alias', $mods ),
			'mod_setenvif'       => in_array( 'mod_setenvif', $mods ),
			'mod_headers'        => in_array( 'mod_headers', $mods ),
		);

		if ( $is_apache && ! $check['found'] ) {
			$check['writable'] = is_writable( ABSPATH );
		}

		if ( $is_apache && $check['writable'] && $check['apache'] ) {
			$check['automatic'] = true;
		}

		return $check;
	}
}
