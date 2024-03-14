<?php
/**
 * Plugin front end class.
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

use Prokerala\Common\Api\Exception\AuthenticationException;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Exception\ValidationException;
use Prokerala\WP\Astrology\Configuration;
use Prokerala\WP\Astrology\Front\Controller\ReportController;
use Prokerala\WP\Astrology\Plugin;

/**
 * Front end class.
 *
 * @since   1.0.0
 */
final class Front {
	/**
	 * Plugin configuration object
	 *
	 * @since 1.0.0
	 *
	 * @var Configuration
	 */
	private $config;
	/**
	 * Report controller for rendering form / result.
	 *
	 * @since 1.0.0
	 *
	 * @var ReportController
	 */
	private $report_controller;

	/**
	 * Front constructor.
	 *
	 * @param Configuration $config Configuration object.
	 */
	public function __construct( Configuration $config ) {
		$this->config            = $config;
		$this->report_controller = new ReportController( $config );
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_shortcode( 'astrology', [ $this, 'render' ] );
		add_shortcode( 'astrology-form', [ $this, 'render_form' ] );
		add_shortcode( 'astrology-result', [ $this, 'render_result' ] );
	}

	/**
	 * Render short code
	 *
	 * @param array $atts Short code attributes.
	 *
	 * @return string
	 */
	public function render( $atts = [] ) {
		return $this->render_result( $atts ) . $this->render_form( $atts );
	}

	/**
	 * Replace shortcode with form HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Short code attributes.
	 * @return string
	 */
	public function render_form( $atts = [] ) {
		return $this->report_controller->render_form( $atts );
	}

	/**
	 * Replace shortcode with result.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Short code attributes.
	 * @return string
	 */
	public function render_result( $atts = [] ) {

		return $this->report_controller->render_result( $atts );
	}

	/**
	 * Enqueue styles for the front area.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		if ( ! $this->is_shortcode_enabled() ) {
			return;
		}

		$this->report_controller->enqueue_styles();
	}

	/**
	 * Enqueue scripts for the front area.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_shortcode_enabled() ) {
			return;
		}

		$this->report_controller->enqueue_scripts();
	}

	/**
	 * Check whether current request is via POST.
	 *
	 * @return bool
	 */
	private function is_post_request() {
		return (
			! isset( $_SERVER['REQUEST_METHOD'] )
			|| 'POST' === wp_unslash( $_SERVER['REQUEST_METHOD'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		);
	}

	/**
	 * Check whether short code is enabled in current page/post.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_shortcode_enabled() {
		global $post;

		return is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'astrology' );
	}
}
