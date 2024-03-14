<?php
/**
 * Name:    Dev4Press\v43\Core\Plugins\Wizard
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

namespace Dev4Press\v43\Core\Plugins;

use Dev4Press\v43\Core\Quick\Sanitize;
use Dev4Press\v43\Core\UI\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Wizard {
	public $panel = false;
	public $panels = array();
	public $types = array();
	public $allowed = array();
	public $default = array();
	public $storage = array();

	public function __construct() {
		$this->init_panels();
		$this->init_data();
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function setup_panel( $panel ) {
		$this->panel = $panel;

		if ( ! isset( $this->panels[ $panel ] ) || $panel === false || is_null( $panel ) ) {
			$this->panel = 'intro';
		}
	}

	public function current_panel() {
		return $this->panel;
	}

	public function panels_index() : array {
		return array_keys( $this->panels );
	}

	public function next_panel() {
		$panel = $this->current_panel();
		$all   = $this->panels_index();

		$index = array_search( $panel, $all );
		$next  = $index + 1;

		if ( $next == count( $all ) ) {
			$next = 0;
		}

		return $all[ $next ];
	}

	public function is_last_panel() : bool {
		$panel = $this->current_panel();
		$all   = $this->panels_index();

		$index = array_search( $panel, $all );

		return $index + 1 == count( $all );
	}

	public function get_form_action() : string {
		return $this->a()->panel_url( 'wizard', $this->current_panel() );
	}

	public function get_form_nonce_key( string $panel ) : string {
		return $this->a()->plugin_prefix . '-wizard-nonce-' . $panel;
	}

	public function get_form_nonce() {
		return wp_create_nonce( $this->get_form_nonce_key( $this->current_panel() ) );
	}

	public function panel_postback() {
		$post = $_POST[ $this->a()->plugin_prefix ]['wizard'] ?? array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$goto = $this->a()->panel_url();

		if ( ! empty( $post ) ) {
			$this->setup_panel( Sanitize::slug( $post['_page'] ) );

			if ( wp_verify_nonce( $post['_nonce'], $this->get_form_nonce_key( $this->current_panel() ) ) ) {
				$data = isset( $post[ $this->current_panel() ] ) ? (array) $post[ $this->current_panel() ] : array();

				$this->postback_default( $this->current_panel(), $data );
				$this->postback_custom( $this->current_panel(), $data );

				if ( $this->current_panel() != 'finish' ) {
					$goto = $this->a()->panel_url( 'wizard', $this->next_panel() );
				}
			} else {
				$goto = $this->a()->panel_url( 'wizard', $this->current_panel() );
			}
		}

		wp_redirect( $goto );
		exit;
	}

	public function render_hidden_elements() {
		$_name = $this->a()->plugin_prefix . '[wizard]';

		?>

        <input type="hidden" name="<?php echo esc_attr( $_name ); ?>[_nonce]" value="<?php echo esc_attr( $this->get_form_nonce() ); ?>"/>
        <input type="hidden" name="<?php echo esc_attr( $_name ); ?>[_page]" value="<?php echo esc_attr( $this->current_panel() ); ?>"/>
        <input type="hidden" name="<?php echo esc_attr( $this->a()->plugin_prefix ); ?>_handler" value="postback"/>
        <input type="hidden" name="option_page" value="<?php echo esc_attr( $this->a()->plugin ); ?>-wizard"/>

		<?php
	}

	public function render_checkboxes_list( string $panel, string $name, array $value = array(), $list = array() ) {
		Elements::instance()->checkboxes( $list, array(
			'selected' => $value,
			'name'     => $this->a()->plugin_prefix . '[wizard][' . $panel . '][' . $name . ']',
			'id'       => $this->a()->plugin_prefix . '-wizard-' . $panel . '-' . $name,
		) );
	}

	public function render_yes_no( string $panel, string $name, string $value = 'yes', array $labels = array() ) {
		$_name = $this->a()->plugin_prefix . '[wizard][' . $panel . '][' . $name . ']';
		$_id   = $this->a()->plugin_prefix . '-wizard-' . $panel . '-' . $name;

		$_yes = $labels['yes'] ?? __( 'Yes', 'd4plib' );
		$_no  = $labels['no'] ?? __( 'No', 'd4plib' );

		?>

        <span>
			<input type="radio" name="<?php echo esc_attr( $_name ); ?>" value="yes" id="<?php echo esc_attr( $_id ); ?>-yes"<?php echo $value == 'yes' ? ' checked' : ''; ?>/>
			<label for="<?php echo esc_attr( $_id ); ?>-yes"><?php echo esc_html( $_yes ); ?></label>
		</span>
        <span>
			<input type="radio" name="<?php echo esc_attr( $_name ); ?>" value="no" id="<?php echo esc_attr( $_id ); ?>-no"<?php echo $value == 'no' ? ' checked' : ''; ?>/>
			<label for="<?php echo esc_attr( $_id ); ?>-no"><?php echo esc_html( $_no ); ?></label>
		</span>

		<?php
	}

	public function render_select( string $panel, string $name, string $value = '', $list = array() ) {
		Elements::instance()->select( $list, array(
			'selected' => $value,
			'name'     => $this->a()->plugin_prefix . '[wizard][' . $panel . '][' . $name . ']',
			'id'       => $this->a()->plugin_prefix . '-wizard-' . $panel . '-' . $name,
		) );
	}

	protected function postback_default( string $panel, $data ) : bool {
		$map    = $this->default[ $panel ] ?? array();
		$groups = array();

		foreach ( $map as $key => $settings ) {
			$type = $this->types[ $panel ][ $key ] ?? 'yesno';

			if ( $type == 'yesno' ) {
				$value = $data[ $key ] ?? 'no';
				$value = in_array( $value, array( 'yes', 'no' ) ) ? $value : 'no';

				$this->storage[ $panel ][ $key ] = $value == 'yes';

				foreach ( $settings as $s ) {
					$group = $s[0];
					$keys  = (array) $s[1];
					$set   = $s[2][ $value ];

					if ( ! in_array( $group, $groups ) ) {
						$groups[] = $group;
					}

					foreach ( $keys as $k ) {
						$this->a()->settings()->set( $k, $set, $group );
					}
				}
			} else if ( $type == 'select' ) {
				$value = $data[ $key ] ?? '';
				$value = wp_unslash( $value );
				$value = sanitize_text_field( $value );
				$value = in_array( $value, $this->$this->allowed[ $panel ][ $key ] ) ? $value : '';

				$this->storage[ $panel ][ $key ] = $value;

				foreach ( $settings as $s ) {
					$group = $s[0];
					$keys  = (array) $s[1];

					if ( ! in_array( $group, $groups ) ) {
						$groups[] = $group;
					}

					foreach ( $keys as $k ) {
						$this->a()->settings()->set( $k, $value, $group );
					}
				}
			} else if ( $type == 'checkboxes' ) {
				$value = isset( $data[ $key ] ) ? (array) $data[ $key ] : array();

				if ( ! empty( $value ) ) {
					$value = wp_unslash( $value );
					$value = array_map( 'strtolower', $value );
					$value = array_map( 'sanitize_key', $value );
					$value = array_intersect( $value, $this->allowed[ $panel ][ $key ] );
				}

				$this->storage[ $panel ][ $key ] = $value;

				foreach ( $settings as $s ) {
					$group = $s[0];
					$keys  = (array) $s[1];

					if ( ! in_array( $group, $groups ) ) {
						$groups[] = $group;
					}

					foreach ( $keys as $k ) {
						$this->a()->settings()->set( $k, $value, $group );
					}
				}
			}
		}

		foreach ( $groups as $group ) {
			$this->a()->settings()->save( $group );
		}

		return true;
	}

	protected function postback_custom( string $panel, $data ) {

	}

	/** @return \Dev4Press\v43\Core\Admin\Plugin */
	abstract public function a();

	abstract protected function init_panels();

	abstract protected function init_data();
}
