<?php
/**
 * Name:    Dev4Press\v43\Core\Options\Element
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Element {
	public $type;
	public $name;
	public $title;
	public $notice;
	public $input;
	public $value;

	public $source;
	public $data;

	public $args = array();
	public $switch = array();
	public $more = array();
	public $buttons = array();
	public $more_method = 'list';

	public function __construct( string $type, string $name, string $title = '', string $notice = '', string $input = 'text', $value = '' ) {
		$this->type   = $type;
		$this->name   = $name;
		$this->title  = $title;
		$this->notice = $notice;
		$this->input  = $input;
		$this->value  = $value;
	}

	public static function i( string $type, string $name, string $title = '', string $notice = '', string $input = 'text', $value = '' ) : Element {
		return new Element( $type, $name, $title, $notice, $input, $value );
	}

	public static function f( string $name, string $title = '', string $notice = '', string $input = '', $value = '' ) : Element {
		return new Element( 'features', $name, $title, $notice, $input, $value );
	}

	public static function s( string $name, string $title = '', string $notice = '', string $input = '', $value = '' ) : Element {
		return new Element( 'settings', $name, $title, $notice, $input, $value );
	}

	public static function l( string $type, string $name, string $title = '', string $notice = '', string $input = 'text', $value = '', string $source = '', $data = '', $args = array() ) : Element {
		return self::i( $type, $name, $title, $notice, $input, $value )->data( $source, $data )->args( $args );
	}

	public static function info( string $title = '', string $notice = '', array $more = array(), string $method = 'list' ) : Element {
		return self::i( '', '', $title, $notice, Type::INFO )->more( $more, $method );
	}

	public function data( string $source = '', $data = '' ) : Element {
		$this->source = $source;
		$this->data   = $data;

		return $this;
	}

	public function args( array $args = array() ) : Element {
		$this->args = $args;

		return $this;
	}

	public function more( array $more = array(), string $method = 'list' ) : Element {
		$this->more        = $more;
		$this->more_method = $method;

		return $this;
	}

	public function buttons( array $buttons = array() ) : Element {
		$this->buttons = $buttons;

		return $this;
	}

	public function switch( array $args = array() ) : Element {
		$default = array(
			'type'  => 'option',
			'role'  => '',
			'value' => '',
			'ref'   => '',
		);

		$this->switch = wp_parse_args( $args, $default );

		return $this;
	}
}
