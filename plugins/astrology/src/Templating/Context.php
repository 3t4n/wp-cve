<?php
/**
 * Template context.
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

namespace Prokerala\WP\Astrology\Templating;

/**
 * Render Context.
 */
class Context {

	/**
	 * Template Engine.
	 *
	 * @since 1.0.0
	 *
	 * @var Engine
	 */
	private $engine;
	/**
	 * Context data.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string,mixed>
	 */
	private $data;
	/**
	 * Layout name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $layout;
	/**
	 * Additional layout data.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string,mixed>
	 */
	private $layout_data = [];

	/**
	 * Context constructor.
	 *
	 * @param Engine              $engine Template engine.
	 * @param array<string,mixed> $data   Render data.
	 */
	public function __construct( $engine, array $data = [] ) {
		$this->data   = $data;
		$this->engine = $engine;
		$this->layout = null;
	}

	/**
	 * Set base layout for current template.
	 *
	 * @param string              $template Base template.
	 * @param array<string,mixed> $data     Additional data.
	 * @return void
	 */
	public function extends( $template, array $data = [] ) {
		$this->layout      = $template;
		$this->layout_data = $data;
	}

	/**
	 * Render template.
	 *
	 * @param string              $template Sub template.
	 * @param array<string,mixed> $data     Additional data.
	 * @return void
	 */
	public function render( $template, array $data = [] ) {
		echo $this->engine->render( $template, $data + $this->data ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get parent layout.
	 *
	 * @return string
	 */
	public function get_layout() {
		return $this->layout;
	}

	/**
	 * Get parent layout data.
	 *
	 * @return array<string,mixed>
	 */
	public function get_layout_data() {
		return $this->layout_data;
	}

	/**
	 * Get context data.
	 *
	 * @return array<string,mixed>
	 */
	public function get_data() {
		return $this->data;
	}
}
