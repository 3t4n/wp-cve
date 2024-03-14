<?php
/**
 * Gutenberg Editor Block Plugin
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
namespace Prokerala\WP\Astrology;

use Prokerala\WP\Astrology\Configuration;
use Prokerala\WP\Astrology\Front\Controller\ReportController;
use Prokerala\WP\Astrology\Plugin;

/**
 * Block class.
 *
 * @since 1.0.0
 */
class Block {

	/**
	 * Plugin configuration object.
	 *
	 * @since 1.0.0
	 *
	 * @var Configuration
	 */
	private $config;
	/**
	 * Report controller.
	 *
	 * @var ReportController
	 */
	private $report_controller;

	/**
	 * BlockController constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Configuration $config Plugin configuration object.
	 */
	public function __construct( Configuration $config ) {
		$this->config            = $config;
		$this->report_controller = new ReportController( $config );
		$this->report_controller->enqueue_styles();
		$this->report_controller->enqueue_scripts();
	}

	/**
	 * Register block.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		wp_register_script(
			'astrology-block',
			PK_ASTROLOGY_PLUGIN_URL . 'assets/dist/js/admin/block.js',
			[
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-components',
				'wp-editor',
			],
			Plugin::VERSION,
			false
		);

		register_block_type(
			'astrology/report',
			[
				'editor_script'   => 'astrology-block',
				'render_callback' => [ $this, 'render_block' ],
				'attributes'      => [
					'report'     => [
						'default' => 'Chart',
						'type'    => 'string',
					],
					'resultType' => [
						'default' => '',
						'type'    => 'string',
					],
					'options'    => [
						'default' => [],
						'type'    => 'object',
					],
				],
			]
		);
	}

	/**
	 * Render block preview.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attributes Short code attributes.
	 * @return string
	 */
	public function render_block( $attributes ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$result  = '';
		$options = [
			'report'      => $attributes['report'] ?? '',
			'result_type' => $attributes['resultType'] ?? '',
		] + $attributes['options'] ?? [];

		if ( ! wp_doing_ajax() ) {
			$result = $this->report_controller->render_result( $options );
		}

		return $result . $this->report_controller->render_form( $options );
	}
}
