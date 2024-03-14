<?php
/**
 * Name:    Dev4Press\v43\Core\Features\Load
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

namespace Dev4Press\v43\Core\Features;

use Dev4Press\v43\Core\Scope;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Load {
	protected $_network_enabled = false;
	protected $_scope_enabled = true;

	protected $_load;
	protected $_load_blog;
	protected $_list;
	protected $_active = array();
	protected $_scopes = array( 'global', 'admin', 'front' );
	protected $_default_scope = 'global';

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	protected function allow_load( string $feature, bool $early = false, string $scope = '' ) : bool {
		if ( ! $this->is_hidden( $feature ) ) {
			if ( $this->is_always_on( $feature ) || $this->is_enabled( $feature ) ) {
				if ( $early === $this->is_early( $feature ) ) {
					$actual = $this->get_scope( $feature );

					if ( empty( $scope ) || $actual == 'global' || $actual == $scope ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	public function network_mode() : bool {
		return $this->is_network_enabled() && is_multisite();
	}

	public function list() : array {
		return array_keys( $this->_list );
	}

	public function load_main( bool $early = false ) {
		$scope = Scope::instance()->is_frontend() ? 'front' : 'admin';

		foreach ( $this->list() as $feature ) {
			if ( $this->allow_load( $feature, $early, $scope ) ) {
				$this->_active[] = $feature;

				if ( class_exists( $this->_list[ $feature ]['main'] ) ) {
					$this->_list[ $feature ]['main']::instance();
				}
			}
		}
	}

	public function load_admin() {
		foreach ( $this->list() as $feature ) {
			if ( class_exists( $this->_list[ $feature ]['admin'] ) ) {
				$this->_list[ $feature ]['admin']::instance();
			}
		}
	}

	public function attribute( string $attr, string $feature, $default = null ) {
		if ( $attr == 'get_scope' ) {
			$attr = 'scope';
		}

		$default = $default ?? ( in_array(
			$attr,
			array(
				'is_active',
				'is_enabled',
				'is_always_on',
				'is_early',
				'is_hidden',
				'has_settings',
				'has_menu',
				'has_meta_tab',
				'allow_blog_override',
			)
		) ? false : '' );

		if ( $this->is_valid( $feature ) ) {
			if ( $attr == 'is_active' ) {
				return $this->is_active( $feature );
			} else if ( $attr == 'is_enabled' ) {
				return $this->is_enabled( $feature );
			}

			$value = $this->_list[ $feature ]['attributes'][ $attr ] ?? $default;

			if ( $attr == 'scope' && ! in_array( $value, $this->_scopes ) ) {
				$value = $this->_default_scope;
			}

			return $value;
		}

		return $default;
	}

	public function are_enabled( array $features ) : bool {
		$on = true;

		foreach ( $features as $feature ) {
			if ( ! $this->is_enabled( $feature ) ) {
				$on = false;
				break;
			}
		}

		return $on;
	}

	public function is_network_enabled() : bool {
		return (bool) $this->_network_enabled;
	}

	public function is_scope_enabled() : bool {
		return (bool) $this->_scope_enabled;
	}

	public function is_valid( string $feature ) : bool {
		return isset( $this->_list[ $feature ] );
	}

	public function is_enabled( string $feature ) : bool {
		return ! $this->is_hidden( $feature ) && ( ( isset( $this->_load[ $feature ] ) && $this->_load[ $feature ] === true ) || $this->is_always_on( $feature ) );
	}

	public function is_enabled_on_blog( string $feature ) : bool {
		return ! $this->is_hidden( $feature ) && ( isset( $this->_load_blog[ $feature ] ) && $this->_load_blog[ $feature ] === true );
	}

	public function is_active( string $feature ) : bool {
		return $this->is_always_on( $feature ) || in_array( $feature, $this->_active );
	}

	public function is_beta( string $feature ) : bool {
		return (bool) $this->attribute( 'is_beta', $feature );
	}

	public function is_hidden( string $feature ) : bool {
		return (bool) $this->attribute( 'is_hidden', $feature );
	}

	public function is_always_on( string $feature ) : bool {
		return (bool) $this->attribute( 'is_always_on', $feature );
	}

	public function is_early( string $feature ) : bool {
		return (bool) $this->attribute( 'is_early', $feature );
	}

	public function has_settings( string $feature ) : bool {
		return (bool) $this->attribute( 'has_settings', $feature );
	}

	public function has_menu( string $feature ) : bool {
		return (bool) $this->attribute( 'has_menu', $feature );
	}

	public function has_meta_tab( string $feature ) : bool {
		return (bool) $this->attribute( 'has_meta_tab', $feature );
	}

	public function allow_blog_override( string $feature ) : bool {
		return (bool) $this->attribute( 'allow_blog_override', $feature );
	}

	public function get_scope( string $feature ) : string {
		return $this->attribute( 'scope', $feature );
	}

	public function panels( array $panels ) : array {
		foreach ( $this->_list as $feature => $obj ) {
			$f = array(
				'title'     => $obj['label'],
				'icon'      => $obj['icon'],
				'info'      => $obj['description'],
				'scope'     => $this->get_scope( $feature ),
				'settings'  => $this->has_settings( $feature ),
				'panel'     => $this->has_menu( $feature ),
				'beta'      => $this->is_beta( $feature ),
				'hidden'    => $this->is_hidden( $feature ),
				'active'    => $this->is_enabled( $feature ),
				'always_on' => $this->is_always_on( $feature ),
			);

			$include = true;
			if ( $this->network_mode() && ! is_network_admin() ) {
				if ( $this->allow_blog_override( $feature ) ) {
					$f['always_on'] = false;
					$f['active']    = $this->is_enabled_on_blog( $feature );
				} else {
					$include = false;
				}
			}

			if ( $include ) {
				$panels[ $feature ] = $f;
			}
		}

		return $panels;
	}

	public function activation( string $feature, bool $status ) {
		if ( $this->is_valid( $feature ) ) {
			$this->s()->set( $feature, $status, 'load', true );
		}
	}

	public function get_counts() : array {
		$features = array(
			'total'  => count( $this->_list ),
			'active' => 0,
			'always' => 0,
			'hidden' => 0,
			'beta'   => 0,
		);

		foreach ( array_keys( $this->_list ) as $feature ) {
			if ( $this->is_enabled( $feature ) ) {
				$features['active'] ++;
			}

			if ( $this->is_always_on( $feature ) ) {
				$features['always'] ++;
			}

			if ( $this->is_hidden( $feature ) ) {
				$features['hidden'] ++;
			}

			if ( $this->is_beta( $feature ) ) {
				$features['beta'] ++;
			}
		}

		return $features;
	}

	public function get_settings( string $feature ) {
		if ( $this->network_mode() && $this->allow_blog_override( $feature ) ) {
			if ( isset( $this->_load_blog[ $feature ] ) && $this->_load_blog[ $feature ] === true ) {
				return $this->b()->feature_get( $feature );
			}
		}

		return $this->s()->feature_get( $feature );
	}

	public function get_for_edit( string $feature, string $name, $default = null ) {
		$real = $feature . '__' . $name;

		if ( $this->network_mode() && ! is_network_admin() ) {
			return $this->b()->get( $real, 'features', $default );
		}

		return $this->s()->get( $real, 'features', $default );
	}

	abstract public function s();

	abstract public function b();
}
