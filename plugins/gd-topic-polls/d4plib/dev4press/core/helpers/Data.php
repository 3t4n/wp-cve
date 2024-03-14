<?php
/**
 * Name:    Dev4Press\v43\Core\Helpers\Data
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

namespace Dev4Press\v43\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Data {
	public static function list_of_continents() : array {
		return array(
			'AF' => __( 'Africa', 'd4plib' ),
			'AS' => __( 'Asia', 'd4plib' ),
			'EU' => __( 'Europe', 'd4plib' ),
			'NA' => __( 'North America', 'd4plib' ),
			'SA' => __( 'South America', 'd4plib' ),
			'OC' => __( 'Oceania', 'd4plib' ),
			'AN' => __( 'Antarctica', 'd4plib' ),
		);
	}
}
