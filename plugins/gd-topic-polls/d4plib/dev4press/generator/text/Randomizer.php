<?php
/**
 * Name:    Dev4Press\v43\Generator\Text\Randomizer
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

namespace Dev4Press\v43\Generator\Text;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Randomizer extends Generator {
	protected $word_mean = 6.16;
	protected $word_dev = 3.32;

	private $vowels = array(
		'a',
		'e',
		'i',
		'o',
		'u',
	);

	private $consonants = array(
		'b',
		'c',
		'd',
		'f',
		'g',
		'h',
		'j',
		'k',
		'l',
		'm',
		'n',
		'p',
		'r',
		's',
		't',
		'v',
		'w',
		'x',
		'y',
		'z',
	);

	public function words( $count = 1, $tags = false, $array = false ) {
		$words = array();

		for ( $i = 0; $i < $count; $i ++ ) {
			$words[] = $this->random();
		}

		return $this->output( $words, $tags, $array );
	}

	public function set_word_gauss( $mean = 6.16, $dev = 3.32 ) {
		$this->word_mean = floatval( $mean );
		$this->word_dev  = floatval( $dev );

		return $this;
	}

	public function random( $length = true ) {
		$length = $length === true ? $this->gauss( $this->word_mean, $this->word_dev ) : $length;

		if ( $length < 2 ) {
			$length = 2;
		}

		$max = $length / 2;

		$string = '';

		for ( $i = 1; $i <= $max; $i ++ ) {
			$string .= $this->consonants[ wp_rand( 0, count( $this->consonants ) - 1 ) ];
			$string .= $this->vowels[ wp_rand( 0, count( $this->vowels ) - 1 ) ];
		}

		return $string;
	}
}
