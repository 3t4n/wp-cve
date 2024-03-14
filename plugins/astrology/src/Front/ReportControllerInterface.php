<?php
/**
 * Report Controller Interface.
 *
 * @package   Prokerala\WP\Astrology
 * @copyright 2022 Ennexa Technologies Private Limited
 * @license   https://www.gnu.org/licenses/gpl-2.0.en.html GPLV2
 * @link      https://api.prokerala.com
 */

/*
 * This file is part of Prokerala Astrology WordPress plugin
 *
 * Copyright (c) 2022 Ennexa Technologies Private Limited
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Prokerala\WP\Astrology\Front;

/**
 * Report Controller Interface.
 *
 * @since 1.0.0
 */
interface ReportControllerInterface {

	/**
	 * Render report form.
	 *
	 * @since 1.0.0
	 *
	 * @param array $options Short code attributes.
	 * @return string
	 */
	public function render_form( $options = [] );


	/**
	 * Process and render result.
	 *
	 * @since 1.0.1
	 *
	 * @param array $options Short code attributes.
	 * @return string
	 */
	public function process( $options = [] );

	/**
	 * Get default values for supported attributes.
	 *
	 * @since 1.1.0
	 *
	 * @return array<string,mixed>
	 */
	public function get_attribute_defaults();

	/**
	 * Check whether result can be rendered for current request.
	 *
	 * @since 1.2.0
	 *
	 * @param array $atts Short code attributes.
	 *
	 * @return bool
	 */
	public function can_render_result( $atts );
}
