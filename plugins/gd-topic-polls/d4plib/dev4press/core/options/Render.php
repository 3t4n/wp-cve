<?php
/**
 * Name:    Dev4Press\v43\Core\Options\Render
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

use Dev4Press\v43\Core\Quick\Arr;
use Dev4Press\v43\Core\Quick\KSES;
use Dev4Press\v43\Core\Quick\Sanitize;
use Dev4Press\v43\Core\UI\Elements;
use Dev4Press\v43\WordPress\Walker\CheckboxRadio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Render {
	public $base = 'd4pvalue';
	public $prefix = 'd4p';
	public $kb = 'https://support.dev4press.com/kb/%type%/%url%/';

	public $panel;
	public $groups;

	public function __construct( $base, $prefix = 'd4p' ) {
		$this->base   = $base;
		$this->prefix = $prefix;
	}

	public static function instance( $base = 'd4pvalue', $prefix = 'd4p' ) : Render {
		static $render = array();

		if ( ! isset( $render[ $base ] ) ) {
			$render[ $base ] = new Render( $base, $prefix );
		}

		return $render[ $base ];
	}

	public function prepare( $panel, $groups ) : Render {
		$this->panel  = $panel;
		$this->groups = $groups;

		return $this;
	}

	public function call( $call_function, $setting, $name_base, $id_base ) {
		call_user_func( $call_function, $setting, $setting->value, $name_base, $id_base );
	}

	public function render() {
		foreach ( $this->groups as $group => $obj ) {
			if ( isset( $obj['type'] ) && $obj['type'] == 'separator' ) {
				echo '<div class="d4p-group-separator">';
				echo '<h3><span>' . esc_html( $obj['label'] ) . '</span></h3>';
				echo '</div>';
			} else {
				$args = $obj['args'] ?? array();

				$classes = array( 'd4p-group', 'd4p-group-with-settings', 'd4p-group-' . $group );

				if ( isset( $args['hidden'] ) && $args['hidden'] ) {
					$classes[] = 'd4p-hidden-group';
				}

				if ( isset( $args['class'] ) && $args['class'] != '' ) {
					$classes[] = $args['class'];
				}

				$toggle = '';

				if ( ! empty( $obj['toggle'] ) ) {
					$t = $obj['toggle'];

					$classes[] = 'd4p-group-with-toggle';
					$classes[] = $t['value'] ? 'd4p-group-toggle-on' : 'd4p-group-toggle-off';
					$active    = $t['on'] ?? __( 'Active', 'd4plib' );
					$inactive  = $t['off'] ?? __( 'Inactive', 'd4plib' );
					$icon      = $t['value'] ? 'd4p-ui-toggle-on' : 'd4p-ui-toggle-off';
					$title     = $t['value'] ? $active : $inactive;

					$checked   = $t['value'] ? ' checked="checked"' : '';
					$name_base = $this->base . '[' . $t['group'] . '][' . $t['option'] . ']';

					$toggle = '<span class="d4p-toggle-control-wrapper">';
					$toggle .= '<button aria-pressed="' . ( $t['value'] ? 'true' : 'false' ) . '" title="" class="d4p-group-toggle-switch ' . esc_attr( $t['class'] ?? '' ) . '" data-group="' . esc_attr( $t['group'] ) . '" data-option="' . esc_attr( $t['option'] ) . '" data-on="' . esc_attr( $active ) . '" data-off="' . esc_attr( $inactive ) . '" type="button"><i aria-hidden="true" class="d4p-icon ' . esc_attr( $icon ) . '"></i><span class="d4p-accessibility-show-for-sr">' . esc_html( $title ) . '</span></button>';
					$toggle .= '<input ' . $checked . ' value="on" name="' . esc_attr( $name_base ) . '" type="checkbox"/>';
					$toggle .= '</span>';
				}

				$kb = isset( $obj['kb'] ) ? str_replace( '%url%', $obj['kb']['url'], $this->kb ) : '';

				if ( ! empty( $kb ) ) {
					$type  = $obj['kb']['type'] ?? 'article';
					$kb    = str_replace( '%type%', $type, $kb );
					$label = $obj['kb']['label'] ?? 'KB';

					$kb = '<a class="d4p-kb-group" href="' . esc_url( $kb ) . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a>';
				}

				echo '<div class="' . Sanitize::html_classes( $classes ) . '" id="d4p-group-' . esc_attr( $group ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<h3>' . $toggle . esc_html( $obj['name'] ) . $kb . '</h3>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<div class="d4p-group-inner">';

				if ( isset( $obj['off'] ) ) {
					echo '<div class="d4p-settings-off">';
					echo '<p>' . esc_html( $obj['off']['notice'] ) . '</p>';
					echo '</div>';
				}

				if ( isset( $obj['notice'] ) ) {
					$type = $obj['notice']['type'] ?? 'info';

					echo '<div class="d4p-group-notice d4p-notice-' . esc_attr( $type ) . '">';
					echo KSES::standard( $obj['notice']['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '</div>';
				}

				if ( isset( $obj['settings'] ) ) {
					$obj['sections'] = array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => $obj['settings'],
						),
					);
					unset( $obj['settings'] );
				}

				foreach ( $obj['sections'] as $section ) {
					$this->render_section( $section, $group );
				}

				echo '</div>';
				echo '</div>';
			}
		}
	}

	protected function get_id( $name, $id = '' ) {
		if ( ! empty( $id ) ) {
			return $id;
		}

		return str_replace( '[', '_', str_replace( ']', '', $name ) );
	}

	protected function render_section( $section, $group ) {
		$class = 'd4p-settings-section';

		if ( ! empty( $section['name'] ) ) {
			$class .= ' d4p-section-' . $section['name'];
		}

		if ( ! empty( $section['class'] ) ) {
			$class .= ' ' . $section['class'];
		}

		if ( ! empty( $section['switch'] ) ) {
			$_switch = $section['switch'];

			if ( $_switch['role'] == 'value' ) {
				$class .= ' d4p-switch-section-' . $_switch['name'];
				$class .= ' d4p-switch-section-value-' . $_switch['value'];

				if ( $_switch['value'] != $_switch['ref'] ) {
					$class .= ' d4p-switch-section-is-hidden';
				}
			}
		}

		echo '<div class="' . Sanitize::html_classes( $class ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( ! empty( $section['label'] ) ) {
			echo '<h4><span>' . esc_html( $section['label'] ) . '</span></h4>';
		}

		echo '<table class="form-table d4p-settings-table">';
		echo '<tbody>';

		foreach ( $section['settings'] as $setting ) {
			$this->render_option( $setting, $group );
		}

		echo '</tbody>';
		echo '</table>';

		echo '</div>';
	}

	protected function render_option( Element $setting, $group ) {
		if ( isset( $setting->args['skip_render'] ) && $setting->args['skip_render'] === true ) {
			return;
		}

		$name_base     = $this->base . '[' . $setting->type . '][' . $setting->name . ']';
		$id_base       = $this->get_id( $name_base );
		$call_function = apply_filters(
			$this->prefix . '_render_option_call_back_for_' . $setting->input,
			array(
				$this,
				'draw_' . $setting->input,
			),
			$this
		);

		$name = ! empty( $setting->name ) ? $setting->name : 'element-' . $setting->input;

		$wrapper_class = 'd4p-settings-item-row-' . $name;

		if ( isset( $setting->args['wrapper_class'] ) && ! empty( $setting->args['wrapper_class'] ) ) {
			$wrapper_class .= ' ' . $setting->args['wrapper_class'];
		}

		$data  = array();
		$class = 'd4p-setting-wrapper d4p-setting-' . $setting->input;

		if ( isset( $setting->args['class'] ) && ! empty( $setting->args['class'] ) ) {
			$wrapper_class .= ' ' . $setting->args['class'];
		}

		if ( ! empty( $setting->switch ) ) {
			if ( $setting->switch['role'] == 'control' ) {
				$wrapper_class .= ' d4p-switch-control-option';
				$data[]        = 'data-switch="' . $setting->switch['name'] . '"';
				$data[]        = 'data-switch-type="' . $setting->switch['type'] . '"';
			}

			$wrapper_class .= ' d4p-switch-' . $setting->switch['role'] . '-' . $setting->switch['name'];

			if ( $setting->switch['type'] == 'option' && $setting->switch['role'] == 'value' ) {
				$wrapper_class .= ' d4p-switch-option-value-' . $setting->switch['value'];

				if ( $setting->switch['value'] != $setting->switch['ref'] ) {
					$wrapper_class .= ' d4p-switch-option-is-hidden';
				}
			}
		}

		if ( $setting->input == 'hidden' ) {
			do_action( 'd4p_settings_group_hidden_top', $setting, $group );

			$this->call( $call_function, $setting, $name_base, $id_base );

			do_action( 'd4p_settings_group_hidden_bottom', $setting, $group );
		} else {
			if ( isset( $setting->args['data'] ) && is_array( $setting->args['data'] ) && ! empty( $setting->args['data'] ) ) {
				foreach ( $setting->args['data'] as $key => $value ) {
					$data[] = 'data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
				}
			}

			if ( ! empty( $data ) ) {
				$data = ' ' . join( ' ', $data );
			} else {
				$data = '';
			}

			if ( $setting->input == 'custom' ) {
				echo '<tr' . $data . ' valign="top" class="d4p-settings-option-custom ' . Sanitize::html_classes( $wrapper_class ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<td colspan="2">';

				echo '<div class="' . Sanitize::html_classes( $class ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo KSES::standard( $setting->notice ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';

				echo '</td>';
				echo '</tr>';
			} else {
				$wrapper_class = 'd4p-settings-option-' . ( $setting->input == 'info' ? 'info' : 'item' ) . ' ' . $wrapper_class;

				echo '<tr' . $data . ' class="' . Sanitize::html_classes( $wrapper_class ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				if ( isset( $setting->args['readonly'] ) && $setting->args['readonly'] ) {
					$class .= 'd4p-setting-disabled';
				}

				echo '<th scope="row">';
				if ( empty( $setting->name ) ) {
					echo '<span>' . esc_html( $setting->title ) . '</span>';
				} else {
					echo '<span id="' . esc_attr( $id_base ) . '__label">' . esc_html( $setting->title ) . '</span>';
				}
				echo '</th><td>';
				echo '<div class="' . Sanitize::html_classes( $class ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				do_action( 'd4p_settings_group_top', $setting, $group );

				$this->call( $call_function, $setting, $name_base, $id_base );

				$this->_render_description( $setting );
				$this->_render_more( $setting );
				$this->_render_buttons( $setting );

				do_action( 'd4p_settings_group_bottom', $setting, $group );

				echo '</div>';
				echo '</td>';
				echo '</tr>';
			}
		}
	}

	protected function _render_description( Element $setting ) {
		if ( ! empty( $setting->notice ) && $setting->input != 'info' ) {
			echo '<div class="d4p-description">' . KSES::standard( $setting->notice ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	protected function _render_buttons( Element $setting ) {
		if ( ! empty( $setting->buttons ) ) {
			Elements::instance()->buttons( $setting->buttons );
		}
	}

	protected function _render_more( Element $setting ) {
		if ( ! empty( $setting->more ) ) {
			echo '<div class="d4p-more-wrapper">';
			echo '<div class="d4p-more-title">';
			echo '<i aria-hidden="true" class="d4p-icon d4p-ui-chevron-square-down d4p-icon-fw"></i> <button type="button">' . esc_html__( 'Toggle Additional Information', 'd4plib' ) . '</button>';
			echo '</div>';
			echo '<div class="d4p-more-content">';

			if ( $setting->more_method == 'list' ) {
				echo KSES::standard( '<ul><li>' . join( '</li><li>', $setting->more ) . '</li></ul>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else if ( $setting->more_method == 'paragraphs' ) {
				echo KSES::standard( '<p>' . join( '</p><p>', $setting->more ) . '</p>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo KSES::standard( join( '', $setting->more ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '</div>';
			echo '</div>';
		}
	}

	protected function _render_check_uncheck_all() {
		echo '<div class="d4p-check-uncheck">';

		echo '<a href="#checkall" class="d4p-check-all"><i class="d4p-icon d4p-ui-check-square"></i> ' . esc_html__( 'Check All', 'd4plib' ) . '</a>';
		echo '<a href="#uncheckall" class="d4p-uncheck-all"><i class="d4p-icon d4p-ui-box"></i> ' . esc_html__( 'Uncheck All', 'd4plib' ) . '</a>';

		echo '</div>';
	}

	protected function _pair_element( $name, $id, $i, $value, $element, $hide = false ) {
		echo '<div class="pair-element-' . esc_attr( $i ) . '" style="display: ' . ( $hide ? 'none' : 'block' ) . '">';
		echo '<label for="' . esc_attr( $id ) . '_key">' . KSES::strong( $element->args['label_key'] ) . ':</label>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input type="text" name="' . esc_attr( $name ) . '[key]" id="' . esc_attr( $id ) . '_key" value="' . esc_attr( $value['key'] ) . '" class="widefat" />';

		echo '<label for="' . esc_attr( $id ) . '_value">' . KSES::strong( $element->args['label_value'] ) . ':</label>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input type="text" name="' . esc_attr( $name ) . '[value]" id="' . esc_attr( $id ) . '_value" value="' . esc_attr( $value['value'] ) . '" class="widefat" />';

		echo '<a role="button" class="button-secondary" href="#">' . KSES::strong( $element->args['label_button_remove'] ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}

	protected function _text_element( $name, $id, $i, $value, $element, $hide = false ) {
		echo '<li class="exp-text-element exp-text-element-' . esc_attr( $i ) . '" ' . ( $hide ? 'style="display: none"' : '' ) . '>';

		$button = isset( $element->args['label_button_remove'] ) ? esc_html( $element->args['label_button_remove'] ) : '<i class="d4p-icon d4p-ui-clear d4p-icon-fw"></i>';
		$type   = isset( $element->args['type'] ) && ! empty( $element->args['type'] ) ? $element->args['type'] : 'text';

		echo '<input aria-labelledby="' . esc_attr( $id ) . '__label" type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '[value]" id="' . esc_attr( $id ) . '_value" value="' . esc_attr( $value ) . '" class="widefat" />';
		echo '<button aria-label="' . esc_html( $element->args['label_remove_aria'] ?? esc_html__( 'Remove', 'd4plib' ) ) . '" role="button" class="button-secondary" type="button">' . $button . '</button>';  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo '</li>';
	}

	protected function _datetime_element( Element $element, $value, $name_base, $id_base, $type = 'text', $class = '' ) {
		$readonly  = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';
		$min       = isset( $element->args['min'] ) ? ' min="' . $element->args['min'] . '"' : '';
		$max       = isset( $element->args['max'] ) ? ' max="' . $element->args['max'] . '"' : '';
		$flatpickr = isset( $element->args['flatpickr'] ) && $element->args['flatpickr'];
		$type      = $flatpickr ? 'text' : $type;
		$class     = 'widefat' . ( $flatpickr ? ' ' . $class : '' );

		echo sprintf(
			'<input aria-labelledby="%s__label" type="%s" name="%s" id="%s" value="%s" class="%s"%s%s%s />',
			esc_attr( $id_base ),
			esc_attr( $type ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $value ),
			esc_attr( $class ),
			esc_attr( $readonly ),
			esc_attr( $min ),
			esc_attr( $max )
		);
	}

	protected function draw_date( Element $element, $value, $name_base, $id_base ) {
		$this->_datetime_element( $element, $value, $name_base, $id_base, 'date', 'd4p-input-field-date' );
	}

	protected function draw_time( Element $element, $value, $name_base, $id_base ) {
		$this->_datetime_element( $element, $value, $name_base, $id_base, 'time', 'd4p-input-field-time' );
	}

	protected function draw_month( Element $element, $value, $name_base, $id_base ) {
		$this->_datetime_element( $element, $value, $name_base, $id_base, 'month', 'd4p-input-field-month' );
	}

	protected function draw_datetime( Element $element, $value, $name_base, $id_base ) {
		$this->_datetime_element( $element, $value, $name_base, $id_base, 'datetime-local', 'd4p-input-field-datetime' );
	}

	protected function draw_text( Element $element, $value, $name_base, $id_base, $type = 'text', $class = 'widefat' ) {
		$readonly    = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';
		$placeholder = isset( $element->args['placeholder'] ) && ! empty( $element->args['placeholder'] ) ? $element->args['placeholder'] : '';
		$type        = isset( $element->args['type'] ) && ! empty( $element->args['type'] ) ? $element->args['type'] : $type;

		if ( isset( $element->args['pattern'] ) ) {
			$pattern = $element->args['pattern'];

			echo sprintf(
				'<input aria-labelledby="%s__label"%s placeholder="%s" pattern="%s" type="%s" name="%s" id="%s" value="%s" class="%s" />',
				esc_attr( $id_base ),
				esc_attr( $readonly ),
				esc_attr( $placeholder ),
				esc_attr( $pattern ),
				esc_attr( $type ),
				esc_attr( $name_base ),
				esc_attr( $id_base ),
				esc_attr( $value ),
				esc_attr( $class )
			);
		} else {
			echo sprintf(
				'<input aria-labelledby="%s__label"%s placeholder="%s" type="%s" name="%s" id="%s" value="%s" class="%s" />',
				esc_attr( $id_base ),
				esc_attr( $readonly ),
				esc_attr( $placeholder ),
				esc_attr( $type ),
				esc_attr( $name_base ),
				esc_attr( $id_base ),
				esc_attr( $value ),
				esc_attr( $class )
			);
		}

	}

	protected function draw_html( Element $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		echo sprintf(
			'<textarea aria-labelledby="%s__label"%s name="%s" id="%s" class="widefat">%s</textarea>',
			esc_attr( $id_base ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_textarea( $value )
		);
	}

	protected function draw_number( Element $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		$min  = isset( $element->args['min'] ) ? ' min="' . floatval( $element->args['min'] ) . '"' : '';
		$max  = isset( $element->args['max'] ) ? ' max="' . floatval( $element->args['max'] ) . '"' : '';
		$step = isset( $element->args['step'] ) ? ' step="' . floatval( $element->args['step'] ) . '"' : '';

		echo sprintf(
			'<input aria-labelledby="%s__label"%s type="number" name="%s" id="%s" value="%s" class="widefat"%s%s%s />',
			esc_attr( $id_base ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $value ),
			esc_attr( $min ),
			esc_attr( $max ),
			esc_attr( $step )
		);

		if ( isset( $element->args['label_unit'] ) ) {
			echo '<span class="d4p-field-unit">' . esc_html( $element->args['label_unit'] ) . '</span>';
		}
	}

	protected function draw_integer( Element $element, $value, $name_base, $id_base ) {
		if ( ! isset( $element->args['step'] ) ) {
			$element->args['step'] = 1;
		}

		$this->draw_number( $element, $value, $name_base, $id_base );
	}

	protected function draw_checkboxes_hierarchy( Element $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
			case '':
			case 'array':
				$data = $element->data;
				break;
		}

		$value = is_null( $value ) || $value === true ? array_keys( $data ) : (array) $value;

		if ( $multiple ) {
			$this->_render_check_uncheck_all();
		}

		$walker = new CheckboxRadio();
		$input  = $multiple ? 'checkbox' : 'radio';

		echo '<div class="d4p-content-wrapper">';
		echo '<ul class="d4p-wrapper-hierarchy">';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $walker->walk(
			$data,
			0,
			array(
				'input'    => $input, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'id'       => $id_base, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'name'     => $name_base, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'selected' => $value, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			)
		);
		echo '</ul>';
		echo '</div>';
	}

	protected function draw_checkboxes_group( Element $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
				$data = $element->data;
				break;
		}

		Elements::instance()->checkboxes_grouped(
			$data,
			array(
				'selected' => $value,
				'name'     => $name_base,
				'id'       => $id_base,
				'multi'    => $multiple,
				'readonly' => isset( $element->args['readonly'] ) && $element->args['readonly'],
				'columns'  => $element->args['columns'] ?? 1,
			)
		);
	}

	protected function draw_checkboxes( Element $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
				$data = $element->data;
				break;
		}

		Elements::instance()->checkboxes(
			$data,
			array(
				'selected' => $value,
				'name'     => $name_base,
				'id'       => $id_base,
				'multi'    => $multiple,
				'readonly' => isset( $element->args['readonly'] ) && $element->args['readonly'],
				'columns'  => $element->args['columns'] ?? 1,
			)
		);
	}

	protected function draw_group_multi( Element $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
				$data = $element->data;
				break;
		}

		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		Elements::instance()->select_grouped(
			$data,
			array(
				'selected' => $value,
				'readonly' => esc_attr( $readonly ),
				'name'     => $name_base,
				'id'       => $id_base,
				'class'    => 'widefat',
				'multi'    => $multiple,
			)
		);
	}

	protected function draw_select_multi( Element $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
				$data = $element->data;
				break;
		}

		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		Elements::instance()->select(
			$data,
			array(
				'selected' => $value,
				'readonly' => esc_attr( $readonly ),
				'name'     => $name_base,
				'id'       => $id_base,
				'class'    => 'widefat',
				'multi'    => $multiple,
			),
			array( 'aria-labelledby' => $id_base . '__label' )
		);
	}

	protected function draw_expandable_text( Element $element, $value, $name_base, $id_base = '' ) {
		echo '<ol>';

		$this->_text_element( $name_base . '[0]', $id_base . '_0', 0, '', $element, true );

		$i = 1;

		if ( array( $value ) && ! empty( $value ) ) {
			foreach ( $value as $val ) {
				$this->_text_element( $name_base . '[' . $i . ']', $id_base . '_' . $i, $i, $val, $element );
				$i ++;
			}
		}

		echo '</ol>';

		$label = $element->args['label_button_add'] ?? __( 'Add New Value', 'd4plib' );

		echo '<a role="button" class="button-primary" href="#">' . esc_html( $label ) . '</a>';
		echo '<input type="hidden" value="' . esc_attr( $i ) . '" class="d4p-next-id" />';
	}

	protected function draw_dropdown_categories( Element $element, $value, $name_base, $id_base = '' ) {
		$label_none   = $element->args['label_none'] ?? ' ';
		$taxonomy     = $element->args['taxonomy'] ?? 'category';
		$hierarchical = $element->args['hierarchical'] ?? true;
		$child        = $element->args['child_of'] ?? 0;
		$depth        = $element->args['depth'] ?? 0;
		$hide_empty   = $element->args['hide_empty'] ?? false;

		$list = wp_dropdown_categories(
			array(
				'echo'              => false,
				'show_option_none'  => $label_none,
				'option_none_value' => 0,
				'hide_empty'        => $hide_empty,
				'hierarchical'      => $hierarchical,
				'child_of'          => $child,
				'depth'             => $depth,
				'name'              => $name_base,
				'class'             => 'widefat',
				'id'                => $id_base,
				'taxonomy'          => $taxonomy,
			)
		);

		if ( empty( $list ) ) {
			Elements::instance()->select(
				array( '0' => __( 'No items to show', 'd4plib' ) ),
				array(
					'selected' => 0,
					'name'     => $name_base,
					'id'       => $id_base,
					'class'    => 'widefat',
				),
				array( 'aria-labelledby' => $id_base . '__label' )
			);
		} else {
			echo $list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	protected function draw_dropdown_pages( Element $element, $value, $name_base, $id_base = '' ) {
		$label_none = $element->args['label_none'] ?? ' ';
		$post_type  = $element->args['post_type'] ?? 'page';
		$child      = $element->args['child_of'] ?? 0;
		$depth      = $element->args['depth'] ?? 0;

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$list = wp_dropdown_pages(
			array(
				'echo'              => false,
				'child_of'          => $child, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'depth'             => $depth, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'show_option_none'  => $label_none, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'option_none_value' => 0,
				'selected'          => $value, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'name'              => $name_base, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'class'             => 'widefat',
				'id'                => $id_base, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'post_type'         => $post_type, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			)
		);

		if ( empty( $list ) ) {
			Elements::instance()->select(
				array( '0' => __( 'No items to show', 'd4plib' ) ),
				array(
					'selected' => 0,
					'name'     => $name_base,
					'id'       => $id_base,
					'class'    => 'widefat',
				),
				array( 'aria-labelledby' => $id_base . '__label' )
			);
		} else {
			echo $list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	protected function draw_info( Element $element, $value, $name_base, $id_base = '' ) {
		echo KSES::standard( $element->notice ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	protected function draw_images( Element $element, $value, $name_base, $id_base = '' ) {
		$value = (array) $value;

		echo '<a role="button" href="#" class="button d4plib-button-inner d4plib-images-add"><i aria-hidden="true" class="d4p-icon d4p-ui-photo"></i> ' . esc_html__( 'Add Image', 'd4plib' ) . '</a>';

		echo '<div class="d4plib-selected-image" data-name="' . esc_html( $name_base ) . '">';

		echo '<div style="display: ' . ( empty( $value ) ? 'block' : 'none' ) . '" class="d4plib-images-none"><span class="d4plib-image-name">' . esc_html__( 'No images selected.', 'd4plib' ) . '</span></div>';

		foreach ( $value as $id ) {
			$image = get_post( $id );
			$title = '(' . $image->ID . ') ' . $image->post_title;
			$media = wp_get_attachment_image_src( $id, 'full' );
			$url   = $media[0];

			echo "<div class='d4plib-images-image'>";
			echo "<input type='hidden' value='" . esc_attr( $id ) . "' name='" . esc_attr( $name_base ) . "[]' />";
			echo "<a class='button d4plib-button-action d4plib-images-remove' aria-label='" . esc_attr__( 'Remove', 'd4plib' ) . "'><i aria-hidden='true' class='d4p-icon d4p-ui-cancel'></i></a>";
			echo "<a class='button d4plib-button-action d4plib-images-preview' aria-label='" . esc_attr__( 'Preview', 'd4plib' ) . "'><i aria-hidden='true' class='d4p-icon d4p-ui-search'></i></a>";
			echo "<span class='d4plib-image-name'>" . esc_html( $title ) . '</span>';
			echo "<img src='" . esc_url( $url ) . "' alt='' />";
			echo '</div>';
		}

		echo '</div>';
	}

	protected function draw_image( Element $element, $value, $name_base, $id_base = '' ) {
		echo sprintf(
			'<input class="d4plib-image" type="hidden" name="%s" id="%s" value="%s" />',
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $value )
		);

		echo '<a role="button" href="#" class="button d4plib-button-inner d4plib-image-add"><i aria-hidden="true" class="d4p-icon d4p-ui-photo"></i> ' . esc_html__( 'Select Image', 'd4plib' ) . '</a>';
		echo '<a role="button" style="display: ' . ( $value > 0 ? 'inline-block' : 'none' ) . '" href="#" class="button d4plib-button-inner d4plib-image-preview"><i aria-hidden="true" class="d4p-icon d4p-ui-search"></i> ' . esc_html__( 'Show Image', 'd4plib' ) . '</a>';
		echo '<a role="button" style="display: ' . ( $value > 0 ? 'inline-block' : 'none' ) . '" href="#" class="button d4plib-button-inner d4plib-image-remove"><i aria-hidden="true" class="d4p-icon d4p-ui-cancel"></i> ' . esc_html__( 'Clear Image', 'd4plib' ) . '</a>';

		echo '<div class="d4plib-selected-image">';
		$title = __( 'Image not selected.', 'd4plib' );
		$url   = '';

		if ( $value > 0 ) {
			$image = get_post( $value );
			$title = '(' . $image->ID . ') ' . $image->post_title;
			$media = wp_get_attachment_image_src( $value, 'full' );
			$url   = $media[0];
		}

		echo '<span class="d4plib-image-name">' . esc_html( $title ) . '</span>';
		echo '<img src="' . esc_url( $url ) . '" alt="" />';
		echo '</div>';
	}

	protected function draw_hidden( Element $element, $value, $name_base, $id_base = '' ) {
		echo sprintf(
			'<input type="hidden" name="%s" id="%s" value="%s" />',
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $value )
		);
	}

	protected function draw_bool( Element $element, $value, $name_base, $id_base = '' ) {
		$selected = $value == 1 || $value === true ? ' checked="checked"' : '';
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly disabled ' : '';
		$label    = isset( $element->args['label'] ) && $element->args['label'] != '' ? $element->args['label'] : __( 'Enabled', 'd4plib' );
		$value    = isset( $element->args['value'] ) && $element->args['value'] != '' ? $element->args['value'] : 'on';

		echo sprintf(
			'<label for="%s"><input%s type="checkbox" name="%s" id="%s"%s class="widefat" value="%s" /><span class="d4p-accessibility-show-for-sr">%s: </span>%s</label>',
			esc_attr( $id_base ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			$selected, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $value ),
			esc_html( $element->title ),
			esc_html( $label )
		);
	}

	protected function draw_range_absint( Element $element, $value, $name_base, $id_base ) {
		$this->draw_range_integer( $element, $value, $name_base, $id_base );
	}

	protected function draw_range_integer( Element $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		$pairs = explode( '=>', $value );

		echo sprintf(
			'<label for="%s_a"><span class="d4p-accessibility-show-for-sr">%s - A: </span></label><input%s type="number" name="%s[a]" id="%s_a" value="%s" class="widefat" />',
			esc_attr( $id_base ),
			esc_attr( $element->title ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $pairs[0] )
		);
		echo ' => ';
		echo sprintf(
			'<label for="%s_b"><span class="d4p-accessibility-show-for-sr">%s - B: </span></label><input%s type="number" name="%s[b]" id="%s_b" value="%s" class="widefat" />',
			esc_attr( $id_base ),
			esc_attr( $element->title ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $pairs[1] )
		);
	}

	protected function draw_x_by_y( Element $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		$pairs = explode( 'x', $value );

		echo sprintf(
			'<label for="%s_x"><span class="d4p-accessibility-show-for-sr">%s - X: </span></label><input%s type="number" name="%s[x]" id="%s_x" value="%s" class="widefat" />',
			esc_attr( $id_base ),
			esc_attr( $element->title ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $pairs[0] )
		);
		echo ' x ';
		echo sprintf(
			'<label for="%s_y"><span class="d4p-accessibility-show-for-sr">%s - Y: </span></label><input%s type="number" name="%s[y]" id="%s_y" value="%s" class="widefat" />',
			esc_attr( $id_base ),
			esc_attr( $element->title ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $pairs[1] )
		);
	}

	protected function draw_listing( Element $element, $value, $name_base, $id_base ) {
		$this->draw_html( $element, join( PHP_EOL, $value ), $name_base, $id_base );
	}

	protected function draw_code( Element $element, $value, $name_base, $id_base ) {
		$mode = isset( $element->args['mode'] ) && $element->args['mode'] ? $element->args['mode'] : 'htmlmixed';

		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		echo sprintf(
			'<textarea aria-labelledby="%s__label"%s name="%s" id="%s" class="widefat d4p-code-editor-element" data-mode="%s">%s</textarea>',
			esc_attr( $id_base ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $mode ),
			esc_textarea( $value )
		);
	}

	protected function draw_textarea( Element $element, $value, $name_base, $id_base ) {
		$this->draw_html( $element, $value, $name_base, $id_base );
	}

	protected function draw_slug( Element $element, $value, $name_base, $id_base ) {
		if ( ! isset( $element->args['pattern'] ) ) {
			$element->args['pattern'] = '[a-z0-9\-]+';
		}

		$this->draw_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_slug_ext( Element $element, $value, $name_base, $id_base ) {
		if ( ! isset( $element->args['pattern'] ) ) {
			$element->args['pattern'] = '[a-z0-9_\.\-]+';
		}

		$this->draw_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_slug_slash( Element $element, $value, $name_base, $id_base ) {
		if ( ! isset( $element->args['pattern'] ) ) {
			$element->args['pattern'] = '[a-z0-9\-\.\/]+';
		}

		$this->draw_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_link( Element $element, $value, $name_base, $id_base ) {
		if ( ! isset( $element->args['placeholder'] ) ) {
			$element->args['placeholder'] = 'https://';
		}

		$this->draw_text( $element, $value, $name_base, $id_base, 'url' );
	}

	protected function draw_email( Element $element, $value, $name_base, $id_base ) {
		$this->draw_text( $element, $value, $name_base, $id_base, 'email' );
	}

	protected function draw_text_html( Element $element, $value, $name_base, $id_base ) {
		$this->draw_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_password( Element $element, $value, $name_base, $id_base ) {
		$readonly     = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';
		$autocomplete = isset( $element->args['autocomplete'] ) ? Sanitize::slug( $element->args['autocomplete'] ) : 'off';

		echo sprintf(
			'<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="password" name="%s" id="%s" value="%s" class="widefat" autocomplete="%s" />',
			esc_attr( $id_base ),
			esc_attr( $element->title ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $value ),
			esc_attr( $autocomplete )
		);
	}

	protected function draw_file( Element $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		echo sprintf(
			'<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="file" name="%s" id="%s" value="%s" class="widefat" />',
			esc_attr( $id_base ),
			esc_attr( $element->title ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $value )
		);
	}

	protected function draw_color( Element $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		echo sprintf(
			'<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="text" name="%s" id="%s" value="%s" class="widefat d4p-color-picker" />',
			esc_attr( $id_base ),
			esc_attr( $element->title ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $value )
		);
	}

	protected function draw_absint( Element $element, $value, $name_base, $id_base ) {
		$this->draw_integer( $element, $value, $name_base, $id_base );
	}

	protected function draw_select( Element $element, $value, $name_base, $id_base ) {
		$this->draw_select_multi( $element, $value, $name_base, $id_base, false );
	}

	protected function draw_group( Element $element, $value, $name_base, $id_base ) {
		$this->draw_group_multi( $element, $value, $name_base, $id_base, false );
	}

	protected function draw_radios_hierarchy( Element $element, $value, $name_base, $id_base ) {
		$this->draw_checkboxes_hierarchy( $element, $value, $name_base, $id_base, false );
	}

	protected function draw_radios( Element $element, $value, $name_base, $id_base ) {
		$this->draw_checkboxes( $element, $value, $name_base, $id_base, false );
	}

	protected function draw_expandable_pairs( Element $element, $value, $name_base, $id_base = '' ) {
		$this->_pair_element(
			$name_base . '[0]',
			$id_base . '_0',
			0,
			array(
				'key'   => '',
				'value' => '',
			),
			$element,
			true
		);

		$i = 1;
		foreach ( $value as $key => $val ) {
			$this->_pair_element(
				$name_base . '[' . $i . ']',
				$id_base . '_' . $i,
				$i,
				array(
					'key'   => $key,
					'value' => $val,
				),
				$element
			);
			$i ++;
		}

		echo '<a role="button" class="button-primary" href="#">' . esc_html( $element->args['label_button_add'] ) . '</a>';
		echo '<input type="hidden" value="' . esc_attr( $i ) . '" class="d4p-next-id" />';
	}

	protected function draw_expandable_raw( Element $element, $value, $name_base, $id_base = '' ) {
		$this->draw_expandable_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_css_size( Element $element, $value, $name_base, $id_base = '' ) {
		$sizes = Arr::get_css_size_units();

		$pairs = array();

		foreach ( array_keys( $sizes ) as $unit ) {
			if ( substr( $value, - strlen( $unit ) ) === $unit ) {
				$pairs[0] = substr( $value, 0, strlen( $value ) - strlen( $unit ) );
				$pairs[1] = $unit;
			}
		}

		if ( empty( $pairs ) ) {
			$pairs[0] = floatval( $value );
			$pairs[1] = 'px';
		}

		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';
		$allowed  = isset( $element->args['allowed'] ) && ! empty( $element->args['allowed'] ) ? (array) $element->args['allowed'] : array();

		$allowed_sizes = array();

		foreach ( $sizes as $size => $label ) {
			if ( empty( $allowed ) || in_array( $size, $allowed ) ) {
				$allowed_sizes[ $size ] = $label;
			}
		}

		echo sprintf(
			'<label for="%s_val"><span class="d4p-accessibility-show-for-sr">' . esc_html__( 'Value', 'd4plib' ) . ': </span></label><input%s type="number" name="%s[val]" id="%s_val" value="%s" class="widefat" step="0.01" />',
			esc_attr( $id_base ),
			esc_attr( $readonly ),
			esc_attr( $name_base ),
			esc_attr( $id_base ),
			esc_attr( $pairs[0] )
		);

		echo sprintf( '<label for="%s_unit"><span class="d4p-accessibility-show-for-sr">' . esc_html__( 'Unit', 'd4plib' ) . ': </span></label>', esc_attr( $id_base ) );

		Elements::instance()->select(
			$allowed_sizes,
			array(
				'selected' => $pairs[1],
				'name'     => $name_base . '[unit]',
				'id'       => $id_base . '_unit',
				'class'    => 'widefat',
			)
		);
	}
}
