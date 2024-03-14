<?php
/**
 * Panchang controller.
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

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Prokerala\Api\Astrology\Location;
use Prokerala\Api\Astrology\Result\Panchang\AdvancedPanchang;
use Prokerala\Api\Astrology\Result\Panchang\Panchang as BasicPanchang;
use Prokerala\Api\Astrology\Service\Panchang;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Panchang Form Controller.
 *
 * @since   1.0.0
 */
class PanchangController implements ReportControllerInterface {

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
	 * PanchangController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( array $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render panchang form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ): string {
		$datetime         = $this->get_post_input( 'datetime', 'now' );
		$result_type      = $options['result_type'] ? $options['result_type'] : $this->get_post_input( 'result_type', 'basic' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		return $this->render(
			'form/panchang',
			[
				'options'          => $options + $this->get_options(),
				'datetime'         => new DateTimeImmutable( $datetime, $this->get_timezone() ),
				'result_type'      => $result_type,
				'selected_lang'    => $form_language,
				'report_language'  => $report_language,
				'translation_data' => $translation_data,

			]
		);
	}

	/**
	 * Process result and render result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ): string { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$tz     = $this->get_timezone();
		$client = $this->get_api_client();

		$result_type = $options['result_type'] ? $options['result_type'] : $this->get_post_input( 'result_type', 'basic' );

		$method = new Panchang( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$method->setTimeZone( $tz );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		$advanced = 'advanced' === $result_type;

		if ( $options['date'] ) {
			$result = $this->cache_fetch_result( $method, $advanced, $lang, $options, $tz );
		} else {
			$result = $this->fetch_result( $method, $advanced, $lang, $tz );
		}

		$data = $this->process_result( $result, $advanced );

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/panchang',
			[
				'result'           => $data,
				'result_type'      => $result_type,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
				'title'            => isset( $options['date'] ) ? 'daily_panchang' : 'panchang_details',

			]
		);
	}

	/**
	 * Check whether result can be rendered for current request.
	 *
	 * @since 1.2.0
	 *
	 * @param array $atts Short code attributes.
	 *
	 * @return bool
	 */
	public function can_render_result( $atts ) {
		return (
			isset( $atts['date'] )
			|| ! isset( $_SERVER['REQUEST_METHOD'] )
			|| 'POST' === wp_unslash( $_SERVER['REQUEST_METHOD'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		);
	}

	/**
	 * Get default values for supported attributes.
	 *
	 * @since 1.2.0
	 *
	 * @return array<string,mixed>
	 */
	public function get_attribute_defaults(): array {
		return $this->getCommonAttributeDefaults() + [
			'date'       => '',
			'coordinate' => '',
		];
	}

	/**
	 * Fetch result from cache.
	 *
	 * @param Panchang     $method Panchang Method.
	 * @param bool         $advanced Result Type.
	 * @param string       $lang Result language.
	 * @param array        $options Array of required attributes.
	 * @param DateTimeZone $tz Timezone variables.
	 *
	 * @return BasicPanchang|bool|AdvancedPanchang|array
	 * @throws Exception If something went wrong.
	 * @since 1.2.0
	 */
	private function cache_fetch_result( $method, $advanced, $lang, $options, $tz ) {

		$datetime    = new DateTimeImmutable( $options['date'], $tz );
		$location    = $this->get_location_from_shortcode( $options['coordinate'], $tz );
		$result_type = $advanced ? 'advanced' : 'basic';
		$key         = "astrology_daily_prediction_{$result_type}_{$lang}_{$options['date']}";
		$result      = $this->load_cached_panchang_data( $key );

		if ( empty( $result ) ) {
			$result = $method->process( $location, $datetime, $advanced, $lang );
			$this->cache_panchang_data( $key, $result );
		}

		return $result;
	}

	/**
	 * Create location object from short code.
	 *
	 * @since 1.2.0
	 *
	 * @param string       $coordinates Render options.
	 * @param DateTimeZone $tz Timezone.
	 *
	 * @return Location
	 */
	protected function get_location_from_shortcode( string $coordinates, DateTimeZone $tz ): Location {
		$default = [ 23.179300, 75.784912 ];

		if ( empty( $coordinates ) ) {
			[$latitude, $longitude] = $default;
		} else {
			[$latitude, $longitude] = array_map( 'floatval', explode( ',', $coordinates ) );

			if ( ! $this->in_range( $latitude, -90, 90, true ) || ! $this->in_range( $longitude, -180, 180, true ) ) {
				[$latitude, $longitude] = $default;
			}
		}

		return new Location( (float) $latitude, (float) $longitude, 0, $tz );
	}

	/**
	 * Try to load the panchang data from cache.
	 *
	 * @since 1.2.0
	 *
	 * @param string $key Cache key.
	 *
	 * @return array|false
	 */
	private function load_cached_panchang_data( string $key ) {
		return get_transient( $key )['panchang'];
	}

	/**
	 * Try to load the panchang data from cache.
	 *
	 * @since 1.2.0
	 *
	 * @param string                         $key Cache key.
	 * @param BasicPanchang|AdvancedPanchang $result .
	 *
	 * @return void
	 */
	private function cache_panchang_data( string $key, $result ): void {
		$data['panchang'] = $result;

		$now = new \DateTimeImmutable( 'now' );

		set_transient( $key . '_' . $now->format( 'Y_m_d' ), $data, 86400 );
	}

	/**
	 * Determines if $number is between $min and $max.
	 *
	 * @since 1.2.0
	 *
	 * @param  string  $number     The number from shortcode.
	 * @param  integer $min        The minimum value in the range.
	 * @param  integer $max        The maximum value in the range.
	 * @param  boolean $inclusive  Whether the range should be inclusive or not.
	 *
	 * @return boolean             Whether the number was in the range.
	 */
	private function in_range( $number, $min, $max, $inclusive = false ) {
		if ( $number && is_float( $number ) ) {
			return $inclusive
				? ( $number >= $min && $number <= $max )
				: ( $number > $min && $number < $max );
		}

		return false;
	}

	/**
	 * Muhurat timing
	 *
	 * @param array<string,mixed> $muhurat muhuratdetails.
	 * @return array
	 */
	public function get_advanced_info( array $muhurat ): array { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$muhurat_details = [];
		foreach ( $muhurat as $data ) {
			$field   = $data->getName();
			$periods = $data->getPeriod();
			foreach ( $periods as $period ) {
				$muhurat_details[ $field ][] = [
					'start' => $period->getStart(),
					'end'   => $period->getEnd(),
				];
			}
		}

		return $muhurat_details;
	}

	/**
	 * Panchang Details
	 *
	 * @param array<string,mixed> $panchang panchang data.
	 * @return array
	 */
	public function get_panchang_details( array $panchang ): array { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$data_list       = [ 'nakshatra', 'tithi', 'karana', 'yoga' ];
		$panchang_result = [];

		foreach ( $data_list as $key ) {
			foreach ( $panchang[ $key ] as $idx => $data ) {
				$panchang_result[ $key ][ $idx ] = [
					'id'    => $data->getId(),
					'name'  => $data->getName(),
					'start' => $data->getStart(),
					'end'   => $data->getEnd(),
				];
				if ( 'nakshatra' === $key ) {
					$panchang_result[ $key ][ $idx ]['nakshatra_lord'] = $data->getLord();
				} elseif ( 'tithi' === $key ) {
					$panchang_result[ $key ][ $idx ]['paksha'] = $data->getPaksha();
				}
			}
		}

		return $panchang_result;
	}


	/**
	 * Format result to display in template.
	 *
	 * @since 1.2.0
	 *
	 * @param BasicPanchang|AdvancedPanchang $result Result data to be processed.
	 * @param bool                           $advanced Result type.
	 *
	 * @return array
	 */
	private function process_result( $result, bool $advanced ): array {
	 // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$panchang_result = [
			'sunrise'  => $result->getSunrise(),
			'sunset'   => $result->getSunset(),
			'moonrise' => $result->getMoonrise(),
			'moonset'  => $result->getMoonset(),
			'vaara'    => $result->getVaara(),
		];

		$panchang              = [];
		$panchang['nakshatra'] = $result->getNakshatra();
		$panchang['tithi']     = $result->getTithi();
		$panchang['karana']    = $result->getKarana();
		$panchang['yoga']      = $result->getYoga();

		$panchang_details = $this->get_panchang_details( $panchang );
		$panchang_result  = array_merge( $panchang_result, $panchang_details );

		$data['basic_info'] = $panchang_result;

		if ( $advanced ) {
			$data['auspicious_period']   = $this->get_advanced_info( $result->getAuspiciousPeriod() );
			$data['inauspicious_period'] = $this->get_advanced_info( $result->getInauspiciousPeriod() );
		}

		return $data;
	}

	/**
	 * Format result to display in template.
	 *
	 * @param Panchang     $method Panchang Method.
	 * @param bool         $advanced Result Type.
	 * @param string       $lang Result language.
	 * @param DateTimeZone $tz Timezone variables.
	 * @return AdvancedPanchang|BasicPanchang
	 * @throws Exception If something went wrong.
	 * @since 1.2.0
	 */
	private function fetch_result( $method, $advanced, $lang, $tz ) {
		$location = $this->get_location( $tz );
		$datetime = new DateTimeImmutable( $this->get_post_input( 'datetime' ), $tz );
		return $method->process( $location, $datetime, $advanced, $lang );
	}
}
