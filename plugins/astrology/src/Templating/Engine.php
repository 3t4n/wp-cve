<?php
/**
 * Template Engine.
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
 * Minimal templating engine with inheritance.
 *
 * @copyright Ennexa Technologies Private Limited
 */
class Engine {
	/**
	 * Template global data.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string,mixed>
	 */
	private $data = [];

	/**
	 * Render template.
	 *
	 * @since 1.0.0
	 *
	 * @param string              $template Template file.
	 * @param array<string,mixed> $data Additional data.
	 * @return mixed|string
	 */
	public function render( $template, array $data = [] ) {
		$context = new Context( $this, $data + $this->data );
		$content = $this->render_template( $template, $context );

		$layout = $context->get_layout();
		if ( $layout ) {
			$content = $this->render(
				$context->get_layout(),
				[ 'content' => $content ] + $context->get_layout_data()
			);
		}

		return $content;
	}

	/**
	 * Add global data.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,mixed> $data Additional data.
	 * @return void
	 */
	public function add_data( array $data ) {
		$this->data = array_merge( $this->data, $data );
	}

	/**
	 * Render template.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $template Template name.
	 * @param Context $context Render context.
	 * @return string
	 * @throws \RuntimeException On render failure.
	 */
	private function render_template( $template, Context $context ) {
		$level   = ob_get_level();
		$closure = \Closure::bind(
			function () use ( $template ) {
				/**
				 * Render context.
				 *
				 * @var Context $this
				 */
				extract( $this->get_data() ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
				ob_start();
				$status  = include $template;
				$content = ob_get_clean();
				if ( false === $status || false === $content ) {
					throw new \RuntimeException( "Failed to render template - {$template}" ); // phpcs:ignore:WordPress.Security.EscapeOutput.ExceptionNotEscaped
				}

				return $content;
			},
			$context
		);

		try {
			return $closure();
		} finally {
			while ( ob_get_level() > $level ) {
				ob_end_clean();
			}
		}
	}
}
