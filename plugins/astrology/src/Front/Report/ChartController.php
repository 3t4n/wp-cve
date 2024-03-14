<?php
/**
 * Chart controller.
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

namespace Prokerala\WP\Astrology\Front\Report;

use Prokerala\Api\Astrology\Service\Chart;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Chart Form Controller.
 *
 * @since   1.0.0
 */
class ChartController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
		'hi',
		'ta',
		'ml',
		'te',
	];
	/**
	 * ChartController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render chart form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		$datetime         = $this->get_post_input( 'datetime', 'now' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		return $this->render(
			'form/chart',
			[
				'options'          => $options + $this->get_options(),
				'datetime'         => new \DateTimeImmutable( $datetime, $this->get_timezone() ),
				'chart_type'       => 'rasi',
				'chart_style'      => 'north-indian',
				'selected_lang'    => $form_language,
				'report_language'  => $report_language,
				'translation_data' => $translation_data,
				'chart_types'      => [
					'rasi',
					'navamsa',
					'lagna',
					'trimsamsa',
					'drekkana',
					'chaturthamsa',
					'dasamsa',
					'ashtamsa',
					'dwadasamsa',
					'shodasamsa',
					'hora',
					'akshavedamsa',
					'shashtyamsa',
					'panchamsa',
					'khavedamsa',
					'saptavimsamsa',
					'shashtamsa',
					'chaturvimsamsa',
					'saptamsa',
					'vimsamsa',
				],
			]
		);
	}

	/**
	 * Process result and render result.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ) {
		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$datetime    = $this->get_post_input( 'datetime', '' );
		$chart_type  = $this->get_post_input( 'chart_type', '' );
		$chart_style = $this->get_post_input( 'chart_style', '' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$datetime = new \DateTimeImmutable( $datetime, $tz );
		$method   = new Chart( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		$result['chart']      = $method->process( $location, $datetime, $chart_type, $chart_style, $lang );
		$result['chart_type'] = $chart_type;

		return $this->render(
			'result/chart',
			[
				'result'  => $result,
				'options' => $this->get_options(),
			]
		);
	}

	/**
	 * Get default values for supported attributes.
	 *
	 * @since 1.1.0
	 *
	 * @return array<string,mixed>
	 */
	public function get_attribute_defaults() {
		return $this->getCommonAttributeDefaults() + [
			'chart_type'  => 'rasi',
			'chart_style' => 'north-indian',
		];
	}
}
