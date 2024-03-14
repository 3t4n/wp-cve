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
use Prokerala\Api\Horoscope\Service\DailyPrediction;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Chart Form Controller.
 *
 * @since   1.1.0
 */
class DailyPredictionController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	const SIGNS = [
		'aries',
		'taurus',
		'gemini',
		'cancer',
		'leo',
		'virgo',
		'libra',
		'scorpio',
		'sagittarius',
		'capricorn',
		'aquarius',
		'pisces',
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
		return '';
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
		$tz = $this->get_timezone();

		$day  = $options['day'] ? $options['day'] : $this->get_post_input( 'day' );
		$sign = $options['sign'] ? $options['sign'] : $this->get_post_input( 'sign' );

		$datetime = new \DateTimeImmutable( $day, $tz );

		$result = $this->load_predictions( $datetime, $sign );

		return $this->render(
			'result/daily-prediction',
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
			'sign' => '',
			'day'  => 'today',
		];
	}

	/**
	 * Check whether result can be rendered for current request.
	 *
	 * @since 1.1.0
	 *
	 * @param array $atts Short code attributes.
	 *
	 * @return bool
	 */
	public function can_render_result( $atts ) {
		return true;
	}

	/**
	 * Load prediction for the requested day from, updating cache if the value is not yet cached.
	 *
	 * @since 1.1.0
	 *
	 * @param \DateTimeInterface $datetime Datetime to load prediction.
	 * @param string             $sign Zodiac sign to fetch load. Empty to retrieve for all signs.
	 *
	 * @return array<string,mixed>
	 */
	private function load_predictions( $datetime, $sign ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$data = get_transient( 'astrology_daily_prediction_' . $datetime->format( 'Y_m_d' ) );

		if ( ! $data ) {
			$data = [];
		}

		$result = [];

		if ( '' !== $sign ) {
			return [
				$sign => $data[ $sign ] ?? $this->fetch_prediction( $datetime, $sign ),
			];
		}

		foreach ( self::SIGNS as $sign ) {
			$result[ $sign ] = $data[ $sign ] ?? $this->fetch_prediction( $datetime, $sign );
		}

		return $result;
	}

	/**
	 * Fetch prediction from API server.
	 *
	 * @since 1.1.0
	 *
	 * @param \DateTimeInterface $datetime Datetime to fetch prediction.
	 * @param string             $sign Zodiac sign to fetch load.
	 *
	 * @return array<string,mixed>
	 */
	private function fetch_prediction( $datetime, string $sign ) {
		$client            = $this->get_api_client();
		$method            = new DailyPrediction( $client );
		$daily_predictiton = $method->process( $datetime, $sign )->getDailyHoroscopePrediction();

		$data = get_transient( 'astrology_daily_prediction_' . $datetime->format( 'Y_m_d' ) );

		if ( ! $data ) {
			$data = [];
		}

		$data[ $sign ] = [
			'id'         => $daily_predictiton->getSignId(),
			'sign'       => $daily_predictiton->getSignName(),
			'prediction' => $daily_predictiton->getPrediction(),
		];
		set_transient( 'astrology_daily_prediction_' . $datetime->format( 'Y_m_d' ), $data, 259200 );

		return $data[ $sign ];
	}
}
