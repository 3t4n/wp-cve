<?php
/**
 * Name:    Dev4Press\v43\Core\UI\Elements
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

namespace Dev4Press\v43\Core\UI;

use Dev4Press\v43\Core\Quick\Arr;
use Dev4Press\v43\Core\Quick\KSES;
use Dev4Press\v43\Core\Quick\Sanitize;
use Dev4Press\v43\WordPress\Walker\CheckboxRadio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elements {
	public function __construct() {
	}

	public static function instance() : Elements {
		static $_instance = null;

		if ( ! $_instance ) {
			$_instance = new Elements();
		}

		return $_instance;
	}

	public function buttons( $buttons, bool $echo = true ) : string {
		$render = '<div class="d4p-buttons-wrapper">';

		foreach ( $buttons as $button ) {
			if ( $button['type'] == 'a' ) {
				$render .= '<a rel="' . esc_url( $button['rel'] ?? '' ) . '" target="' . esc_url( $button['target'] ?? '' ) . '" href="' . esc_url( $button['link'] ) . '" class="' . Sanitize::html_classes( $button['class'] ?? '' ) . '">' . esc_html( $button['title'] ) . '</a>';
			} else {
				$render .= '<button name="' . esc_attr( $button['name'] ?? '' ) . '" id="' . esc_attr( $button['id'] ?? '' ) . '" type="button" class="' . Sanitize::html_classes( $button['class'] ?? '' ) . '">' . esc_html( $button['title'] ) . '</button>';
			}
		}

		$render .= '</div>';

		if ( $echo ) {
			echo KSES::buttons( $render ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $render;
	}

	public function input( $value = '', $args = array(), $attr = array() ) : string {
		$args       = $this->input_prepare_args( $args );
		$attributes = $this->input_prepare_attributes( $args, $attr, $value );

		$render = '<input ' . join( ' ', $attributes ) . ' />';

		if ( $args['echo'] ) {
			echo KSES::input( $render );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $render;
	}

	public function select( $values, $args = array(), $attr = array() ) : string {
		$args        = $this->select_prepare_args( $args );
		$attributes  = $this->select_prepare_attributes( $args, $attr );
		$selected    = is_null( $args['selected'] ) || $args['selected'] === true ? array_keys( $values ) : (array) $args['selected'];
		$associative = ! Arr::is_associative( $values );

		$render = '<select ' . join( ' ', $attributes ) . '>';
		if ( ! empty( $args['empty'] ) ) {
			$render .= '<option value="">' . esc_html( $args['empty'] ) . '</option>';
		}

		foreach ( $values as $value => $display ) {
			$real_value = $associative ? $display : $value;
			$strict     = $real_value === 0;

			$sel    = in_array( $real_value, $selected, $strict ) ? ' selected="selected"' : '';
			$render .= '<option value="' . esc_attr( $value ) . '"' . $sel . '>' . esc_html( $display ) . '</option>';
		}
		$render .= '</select>';

		if ( $args['echo'] ) {
			echo KSES::select( $render );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $render;
	}

	public function select_grouped( $values, $args = array(), $attr = array() ) : string {
		foreach ( $values as $group ) {
			if ( ! isset( $group['values'] ) && ! isset( $group['title'] ) ) {
				return $this->select( $values, $args, $attr );
			} else {
				break;
			}
		}

		$args       = $this->select_prepare_args( $args );
		$attributes = $this->select_prepare_attributes( $args, $attr );
		$selected   = $args['selected'];

		$render = '<select ' . join( ' ', $attributes ) . '>';
		if ( ! empty( $args['empty'] ) ) {
			$render .= '<option value="">' . esc_html( $args['empty'] ) . '</option>';
		}

		foreach ( $values as $group ) {
			$render .= '<optgroup label="' . $group['title'] . '">';
			foreach ( $group['values'] as $value => $display ) {
				$strict = $value === 0;
				$sel    = '';

				if ( is_null( $selected ) || $selected === true || ( is_array( $selected ) && in_array( $value, $selected, $strict ) ) ) {
					$sel = ' selected="selected"';
				}

				$render .= '<option value="' . esc_attr( $value ) . '"' . $sel . '>' . esc_html( $display ) . '</option>';
			}
			$render .= '</optgroup>';
		}
		$render .= '</select>';

		if ( $args['echo'] ) {
			echo KSES::select( $render );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $render;
	}

	public function checkboxes( $values, $args = array() ) : string {
		$args        = $this->checkboxes_prepare_args( $args );
		$id          = $this->id_from_name( $args['name'], $args['id'] );
		$name        = $args['multi'] ? $args['name'] . '[]' : $args['name'];
		$selected    = $args['selected'];
		$associative = Arr::is_associative( $values );

		$wrapper_class = 'd4p-setting-checkboxes';

		if ( $args['class'] != '' ) {
			$wrapper_class .= ' ' . $args['class'];
		}

		$render = '<div class="' . Sanitize::html_classes( $wrapper_class ) . '">';
		$render .= $this->checkboxes_render_check_uncheck( $args['multi'] );
		$render .= '<div class="d4p-inside-wrapper">';
		foreach ( $values as $key => $title ) {
			$real_value = $associative ? $key : $title;
			$attributes = $this->checkbox_prepare_attributes( $args, $selected, $name, $id, $real_value, $key );

			$render .= sprintf( '<label><input %s />%s</label>', join( ' ', $attributes ), esc_html( $title ) );
		}
		$render .= '</div>';
		$render .= '</div>';

		if ( $args['echo'] ) {
			echo KSES::checkboxes( $render );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $render;
	}

	public function checkboxes_grouped( $values, $args = array() ) : string {
		foreach ( $values as $group ) {
			if ( ! isset( $group['values'] ) && ! isset( $group['title'] ) ) {
				return $this->checkboxes( $values, $args );
			} else {
				break;
			}
		}

		$args     = $this->checkboxes_prepare_args( $args );
		$id       = $this->id_from_name( $args['name'], $args['id'] );
		$name     = $args['multi'] ? $args['name'] . '[]' : $args['name'];
		$selected = $args['selected'];

		$wrapper_class = 'd4p-setting-checkboxes d4p-setting-checkboxes-grouped';

		if ( $args['class'] != '' ) {
			$wrapper_class .= ' ' . $args['class'];
		}

		$render = '<div class="' . Sanitize::html_classes( $wrapper_class ) . '">';
		$render .= $this->checkboxes_render_check_uncheck( $args['multi'] );
		$render .= '<div class="d4p-inside-wrapper">';
		foreach ( $values as $group ) {
			$render .= '<div class="d4p-group-title">' . $group['title'] . '</div>';
			foreach ( $group['values'] as $key => $title ) {
				$attributes = $this->checkbox_prepare_attributes( $args, $selected, $name, $id, $key, $key );

				$render .= sprintf( '<label><input %s />%s</label>', join( ' ', $attributes ), esc_html( $title ) );
			}
		}
		$render .= '</div>';
		$render .= '</div>';

		if ( $args['echo'] ) {
			echo KSES::checkboxes( $render );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $render;
	}

	public function checkboxes_with_hierarchy( $values, $args = array() ) : string {
		$args = $this->checkboxes_prepare_args( $args );

		$walker = new CheckboxRadio();

		$render = '<div class="d4p-setting-checkboxes-hierarchy">';
		$render .= $this->checkboxes_render_check_uncheck( $args['multi'] );
		$render .= '<div class="d4p-inside-wrapper">';
		$render .= '<ul class="d4p-wrapper-hierarchy">';
		$render .= $walker->walk(
			$values,
			0,
			array(
				'input'    => $args['multi'] ? 'checkbox' : 'radio',
				'id'       => $this->id_from_name( $args['name'], $args['id'] ),
				'name'     => $args['name'],
				'selected' => (array) $args['selected'],
			)
		);
		$render .= '</ul>';
		$render .= '</div>';
		$render .= '</div>';

		if ( $args['echo'] ) {
			echo KSES::checkboxes( $render ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $render;
	}

	private function id_from_name( $name, $id = '' ) : string {
		if ( $id == '' ) {
			$id = str_replace( ']', '', $name );
			$id = str_replace( '[', '_', $id );
		} else if ( $id == '_' ) {
			$id = '';
		}

		return (string) $id;
	}

	private function checkboxes_prepare_args( $args ) : array {
		$defaults = array(
			'selected' => '',
			'name'     => '',
			'id'       => '',
			'class'    => '',
			'columns'  => 1,
			'multi'    => true,
			'echo'     => true,
			'readonly' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		if ( $args['columns'] > 1 ) {
			switch ( $args['columns'] ) {
				case 2:
					$args['class'] .= ' d4p-columns-two';
					break;
				case 3:
					$args['class'] .= ' d4p-columns-three';
					break;
				case 4:
					$args['class'] .= ' d4p-columns-four';
					break;
				case 5:
					$args['class'] .= ' d4p-columns-five';
					break;
			}
		}

		return $args;
	}

	private function checkboxes_render_check_uncheck( $multi ) : string {
		$render = '';
		if ( $multi ) {
			$render .= '<div class="d4p-check-uncheck">';

			$render .= '<a href="#checkall" class="d4p-check-all"><i class="d4p-icon d4p-ui-check-square"></i> ' . esc_html__( 'Check All', 'd4plib' ) . '</a>';
			$render .= '<a href="#uncheckall" class="d4p-uncheck-all"><i class="d4p-icon d4p-ui-box"></i> ' . esc_html__( 'Uncheck All', 'd4plib' ) . '</a>';

			$render .= '</div>';
		}

		return $render;
	}

	private function input_prepare_args( $args ) : array {
		$defaults = array(
			'checked'     => false,
			'name'        => '',
			'id'          => '',
			'title'       => '',
			'placeholder' => '',
			'type'        => 'text',
			'class'       => '',
			'style'       => '',
			'echo'        => true,
			'readonly'    => false,
		);

		return wp_parse_args( $args, $defaults );
	}

	private function select_prepare_args( $args ) : array {
		$defaults = array(
			'selected' => '',
			'name'     => '',
			'id'       => '',
			'title'    => '',
			'empty'    => '',
			'class'    => '',
			'style'    => '',
			'multi'    => false,
			'echo'     => true,
			'readonly' => false,
		);

		return wp_parse_args( $args, $defaults );
	}

	private function select_prepare_attributes( $args, $attr = array() ) : array {
		$attributes = array();

		$name = $args['multi'] ? $args['name'] . '[]' : $args['name'];
		$id   = $this->id_from_name( $args['name'], $args['id'] );

		if ( ! empty( $args['class'] ) ) {
			$attributes[] = 'class="' . esc_attr( $args['class'] ) . '"';
		}

		if ( ! empty( $args['style'] ) ) {
			$attributes[] = 'style="' . esc_attr( $args['style'] ) . '"';
		}

		if ( ! empty( $args['title'] ) ) {
			$attributes[] = 'title="' . esc_attr( $args['title'] ) . '"';
		}

		if ( $args['multi'] ) {
			$attributes[] = 'multiple';
		}

		if ( $args['readonly'] ) {
			$attributes[] = 'readonly';
		}

		if ( ! empty( $name ) ) {
			$attributes[] = 'name="' . esc_attr( $name ) . '"';
		}

		if ( $id != '' ) {
			$attributes[] = 'id="' . esc_attr( $id ) . '"';
		}

		if ( ! empty( $attr ) ) {
			foreach ( $attr as $key => $value ) {
				$attributes[] = $key . '="' . esc_attr( $value ) . '"';
			}
		}

		return $attributes;
	}

	private function input_prepare_attributes( $args, $attr = array(), $value = '' ) : array {
		$args['type'] = $args['type'] ?? 'text';

		$attributes = array(
			'type="' . esc_attr( $args['type'] ) . '"',
			'value="' . esc_attr( $value ) . '"',
		);

		$name = $args['type'] == 'radio' ? $args['name'] . '[]' : $args['name'];
		$id   = $this->id_from_name( $args['name'], $args['id'] );

		if ( ! empty( $args['class'] ) ) {
			$attributes[] = 'class="' . esc_attr( $args['class'] ) . '"';
		}

		if ( ! empty( $args['style'] ) ) {
			$attributes[] = 'style="' . esc_attr( $args['style'] ) . '"';
		}

		if ( ! empty( $args['title'] ) ) {
			$attributes[] = 'title="' . esc_attr( $args['title'] ) . '"';
		}

		if ( ! empty( $args['placeholder'] ) ) {
			$attributes[] = 'placeholder="' . esc_attr( $args['placeholder'] ) . '"';
		}

		if ( $args['readonly'] ) {
			$attributes[] = 'readonly';
		}

		if ( $args['checked'] && in_array( $args['type'], array( 'radio', 'checkbox' ) ) ) {
			$attributes[] = 'checked';
		}

		if ( ! empty( $name ) ) {
			$attributes[] = 'name="' . esc_attr( $name ) . '"';
		}

		if ( $id != '' ) {
			$attributes[] = 'id="' . esc_attr( $id ) . '"';
		}

		if ( ! empty( $attr ) ) {
			foreach ( $attr as $key => $value ) {
				$attributes[] = $key . '="' . esc_attr( $value ) . '"';
			}
		}

		return $attributes;
	}

	private function checkbox_prepare_attributes( $args, $selected, $name, $id, $real_value, $key ) : array {
		$attributes = array(
			'type="' . ( $args['multi'] ? 'checkbox' : 'radio' ) . '"',
			'value="' . $real_value . '"',
			'class="widefat"',
		);

		if ( $id != '' ) {
			$attributes[] = 'id="' . esc_attr( $id . '-' . $key ) . '"';
		}

		if ( $name != '' ) {
			$attributes[] = 'name="' . esc_attr( $name ) . '"';
		}

		if ( is_null( $selected ) || $selected === true || ( is_array( $selected ) && in_array( $real_value, $selected ) ) ) {
			$attributes[] = 'checked="checked"';
		}

		if ( $args['readonly'] ) {
			$attributes[] = 'readonly';
		}

		return $attributes;
	}
}
