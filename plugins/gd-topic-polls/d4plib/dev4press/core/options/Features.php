<?php
/**
 * Name:    Dev4Press\v43\Core\Options\Features
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

namespace Dev4Press\v43\Core\Options;

use Dev4Press\v43\Core\Options\Element as EL;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Features {
	protected $settings;
	protected $feature;

	public function __construct( $feature ) {
		$this->feature = $feature;

		$this->settings[ $feature ] = array();

		$this->init();
	}

	abstract public static function instance( $feature );

	public function get() : array {
		return $this->settings[ $this->feature ];
	}

	public function settings() : array {
		$list = array();

		foreach ( $this->settings[ $this->feature ] as $data ) {
			foreach ( $data['sections'] as $section ) {
				foreach ( $section['settings'] as $o ) {
					if ( ! empty( $o->type ) ) {
						$list[] = $o;
					}
				}
			}
		}

		return $list;
	}

	public function is_hidden() : bool {
		return $this->core()->is_hidden( $this->feature );
	}

	public function is_always_on() : bool {
		if ( $this->core()->network_mode() && ! is_network_admin() ) {
			return false;
		}

		return $this->core()->is_always_on( $this->feature );
	}

	public function is_enabled() : bool {
		if ( $this->core()->network_mode() && ! is_network_admin() ) {
			return $this->core()->is_enabled_on_blog( $this->feature );
		}

		return $this->core()->is_enabled( $this->feature );
	}

	protected function settings_hidden() : array {
		return array(
			$this->feature . '_activation' => array(
				'name'     => __( 'Feature Status', 'd4plib' ),
				'sections' => array(
					array(
						'label'    => '',
						'name'     => '',
						'class'    => '',
						'settings' => array(
							EL::info( __( 'Hidden', 'd4plib' ), __( 'This feature can\'t be activated or configured at this time, because one or more of the feature prerequisites are missing.', 'd4plib' ) ),
						),
					),
				),
			),
		);
	}

	protected function settings_always_on() : array {
		return array(
			$this->feature . '_activation' => array(
				'name'     => __( 'Feature Status', 'd4plib' ),
				'sections' => array(
					array(
						'label'    => '',
						'name'     => '',
						'class'    => '',
						'settings' => array(
							EL::info( __( 'Active', 'd4plib' ), __( 'This feature is always active, and it can\'t be disabled. You can enable or disable individual settings included.', 'd4plib' ) ),
						),
					),
				),
			),
		);
	}

	protected function settings_control() : array {
		if ( $this->core()->network_mode() && ! is_network_admin() ) {
			$network = $this->core()->is_enabled( $this->feature );
			$badge   = '<div class="d4p-feature-status-badge ' . ( $network ? '__is-active' : '__is-inactive' ) . '"><i class="d4p-icon d4p-ui-' . ( $network ? 'check-square' : 'close-square' ) . ' d4p-icon-fw d4p-icon-lg"></i> ' . ( $network ? __( 'Active', 'd4plib' ) : __( 'Inactive', 'd4plib' ) );

			return array(
				$this->feature . '_activation' => array(
					'name'     => __( 'Feature Status', 'd4plib' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'load', $this->feature, __( 'Override', 'd4plib' ), __( 'The activation depends on the Network settings for this feature. Here, you can override network settings for this feature for this blog only.', 'd4plib' ), Type::BOOLEAN, $this->is_enabled() )->args( array( 'label' => __( 'Feature network override is active', 'd4plib' ) ) ),
								EL::info( __( 'Status', 'd4plib' ), $badge ),
							),
						),
					),
				),
			);
		} else {
			return array(
				$this->feature . '_activation' => array(
					'name'     => __( 'Feature Status', 'd4plib' ),
					'sections' => array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => array(
								EL::i( 'load', $this->feature, __( 'Active', 'd4plib' ), __( 'This feature will be loaded only if activated. If you don\'t need this feature, disable it.', 'd4plib' ), Type::BOOLEAN, $this->is_enabled() )->args( array( 'label' => __( 'Feature is active', 'd4plib' ) ) ),
							),
						),
					),
				),
			);
		}
	}

	abstract protected function init();

	/** @return \Dev4Press\v43\Core\Features\Load */
	abstract public function core();
}
