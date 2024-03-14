<?php
/**
 * Name:    Dev4Press\v43\Core\Quick\Display
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

namespace Dev4Press\v43\Core\Quick;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class KSES {
	public static function post( string $render ) : string {
		return wp_kses_post( $render );
	}

	public static function standard( string $render ) : string {
		return wp_kses(
			$render,
			array(
				'code'   => array(),
				'br'     => array(),
				'p'      => array(
					'class' => true,
					'style' => true,
				),
				'li'     => array(
					'class' => true,
					'style' => true,
				),
				'ul'     => array(
					'class' => true,
					'style' => true,
				),
				'a'      => array(
					'href'   => array(),
					'title'  => array(),
					'class'  => array(),
					'target' => array(),
					'data-*' => true,
				),
				'em'     => array(
					'class' => true,
					'style' => true,
				),
				'strong' => array(
					'class' => true,
					'style' => true,
				),
				'div'    => array(
					'class' => true,
					'style' => true,
				),
				'span'   => array(
					'class'  => true,
					'style'  => true,
					'title'  => true,
					'data-*' => true,
					'aria-*' => true,
				),
				'i'      => array(
					'class'  => true,
					'aria-*' => true,
				),
			)
		);
	}

	public static function strong( string $render ) : string {
		return wp_kses(
			$render,
			array(
				'strong' => array(
					'class' => true,
					'style' => true,
				),
				'span'   => array(
					'class' => true,
					'style' => true,
				),
				'i'      => array(
					'class'  => true,
					'aria-*' => true,
				),
			)
		);
	}

	public static function buttons( string $render ) : string {
		return wp_kses(
			$render,
			array(
				'div'    => array(
					'class' => true,
				),
				'a'      => array(
					'class'  => true,
					'href'   => true,
					'rel'    => true,
					'id'     => true,
					'name'   => true,
					'target' => true,
				),
				'button' => array(
					'name'  => true,
					'id'    => true,
					'type'  => true,
					'class' => true,
				),
			)
		);
	}

	public static function select( string $render ) : string {
		return wp_kses(
			$render,
			array(
				'select'   => array(
					'class'    => true,
					'style'    => true,
					'title'    => true,
					'multiple' => true,
					'readonly' => true,
					'id'       => true,
					'name'     => true,
					'data-*'   => true,
				),
				'optgroup' => array(
					'label' => true,
				),
				'option'   => array(
					'value'    => true,
					'selected' => true,
				),
			)
		);
	}

	public static function input( string $render ) : string {
		return wp_kses(
			$render,
			array(
				'input' => array(
					'class'       => true,
					'style'       => true,
					'title'       => true,
					'type'        => true,
					'checked'     => true,
					'readonly'    => true,
					'id'          => true,
					'name'        => true,
					'placeholder' => true,
					'value'       => true,
					'data-*'      => true,
				),
			)
		);
	}

	public static function checkboxes( string $render ) : string {
		return wp_kses(
			$render,
			array(
				'a'     => array(
					'class' => true,
					'href'  => true,
				),
				'i'     => array(
					'class'  => true,
					'aria-*' => true,
				),
				'ul'    => array(
					'class' => true,
				),
				'li'    => array(
					'class' => true,
				),
				'div'   => array(
					'class' => true,
				),
				'label' => array(
					'class' => true,
				),
				'input' => array(
					'class'    => true,
					'style'    => true,
					'checked'  => true,
					'readonly' => true,
					'id'       => true,
					'name'     => true,
					'data-*'   => true,
					'type'     => true,
					'value'    => true,
				),
			)
		);
	}
}
