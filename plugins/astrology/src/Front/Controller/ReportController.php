<?php
/**
 * Report Controller
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
namespace Prokerala\WP\Astrology\Front\Controller;

use Prokerala\Common\Api\Exception\AuthenticationException;
use Prokerala\Common\Api\Exception\QuotaExceededException;
use Prokerala\Common\Api\Exception\RateLimitExceededException;
use Prokerala\Common\Api\Exception\ValidationException;
use Prokerala\WP\Astrology\Configuration;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;
use Prokerala\WP\Astrology\Plugin;
use RuntimeException;

/**
 * Report Controller class.
 *
 * @since 1.0.0
 */
class ReportController {

	/**
	 * Plugin configuration object.
	 *
	 * @since 1.0.0
	 *
	 * @var Configuration
	 */
	private $config;

	/**
	 * ReportController constructor.
	 *
	 * @param Configuration $config Configuration object.
	 */
	public function __construct( Configuration $config ) {
		$this->config = $config;
	}

	/**
	 * Enqueue styles for the report form/result.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'pk-astrology',
			PK_ASTROLOGY_PLUGIN_URL . 'assets/dist/css/main.css',
			[],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Enqueue scripts for the report form/result.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$options = $this->config->get_options();

		wp_enqueue_script(
			'pk-astrology-location-widget',
			'https://client-api.prokerala.com/static/js/location.min.js',
			[],
			Plugin::VERSION,
			true
		);
		wp_enqueue_script(
			'pk-astrology',
			PK_ASTROLOGY_PLUGIN_URL . 'assets/dist/js/main.js',
			[ 'pk-astrology-location-widget' ],
			Plugin::VERSION,
			true
		);
		wp_add_inline_script(
			'pk-astrology',
			'window.CLIENT_ID = ' . wp_json_encode( $options['client_id'] ),
			'before'
		);
	}

	/**
	 * Replace shortcode with form HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Short code attributes.
	 * @return string
	 */
	public function render_form( $atts = [] ) {  // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		try {
			$controller = $this->get_controller( $atts['report'] ?? '' );

			$args = shortcode_atts( $controller->get_attribute_defaults(), $atts );

			return $controller->render_form(
				[
					'result_type'     => $args['result_type'] ?? '',
					'form_action'     => $args['form_action'] ?? '',
					'calculator'      => $args['calculator'] ?? '',
					'system'          => $args['system'] ?? '',
					'form_language'   => $args['form_language'] ?? '',
					'report_language' => $args['report_language'] ?? '',
				] + $args
			);
		} catch ( RuntimeException $e ) {
			return "<blockquote>{$e->getMessage()}</blockquote>";
		}
	}

	/**
	 * Replace shortcode with result.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Short code attributes.
	 * @return string
	 */
	public function render_result( $atts = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		try {
			$controller = $this->get_controller( $atts['report'] ?? '' );

			if ( ! $controller->can_render_result( $atts ) ) {
				return '';
			}

			$args = shortcode_atts( $controller->get_attribute_defaults(), $atts );

			return $controller->process( $args );
		} catch ( ValidationException $e ) {
			$errors     = $e->getValidationErrors();
			$error_code = '<ul>';
			foreach ( $errors as $error ) {
				$error_code .= '<li>' . $error->detail . '</li>';
			}
			$error_code .= '</ul>';
			return $error_code;
		} catch ( QuotaExceededException $e ) {
			return '<blockquote><p>You have exceeded your quota allocation</p></blockquote>';
		} catch ( RateLimitExceededException $e ) {
			return 'Rate limit exceeded. Throttle your requests.';
		} catch ( AuthenticationException $e ) {
			return '<blockquote><p>' . wp_kses( $e->getMessage(), [] ) . '</p></blockquote>';
		} catch ( \Exception $e ) {
			return '<blockquote><p>Request failed with error <em>' . wp_kses( $e->getMessage(), [] ) . '</em></p></blockquote>';
		}
	}

	/**
	 * Get controller from report name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $report Report type.
	 * @return ReportControllerInterface
	 * @throws RuntimeException Throws on invalid report type.
	 */
	private function get_controller( $report ) {

		$report_controller = ucwords( $report ) . 'Controller';
		$controller_class  = "Prokerala\\WP\\Astrology\\Front\\Report\\{$report_controller}";

		if ( ! class_exists( $controller_class ) ) {
			throw new RuntimeException( 'Invalid report type' . $controller_class ); // phpcs:ignore:WordPress.Security.EscapeOutput.ExceptionNotEscaped
		}

		return new $controller_class( $this->config->get_options() );
	}
}
