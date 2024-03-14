<?php
/**
 * Western controller.
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
use Exception;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\NatalChart as NatalAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\ProgressionChart as ProgressionAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\SolarReturnChart as SolarReturnAspectChart;
use Prokerala\Api\Astrology\Western\Service\Charts\NatalChart;
use Prokerala\Api\Astrology\Western\Service\Charts\ProgressionChart;
use Prokerala\Api\Astrology\Western\Service\Charts\SolarReturnChart;
use Prokerala\Api\Astrology\Western\Service\Charts\TransitChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\TransitChart as TransitAspectChart;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\NatalChart as NatalPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\ProgressionChart as ProgressionPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\SolarReturnChart as SolarReturnPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\TransitChart as TransitPlanetPositions;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Western Chart Form Controller.
 *
 * @since   1.3.0
 */
class WesternChartController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
	];
	/**
	 * WesternChartController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
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
			'date'            => '',
			'report_type'     => 'natal-chart',
			'display_options' => 'chart',
			'coordinate'      => '',
		];
	}

	/**
	 * Render Western Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		$report_type = $options['report_type'];

		switch ( $report_type ) {
			case 'natal-chart':
				return $this->getNatalChartForm( $options );
			case 'transit-chart':
				return $this->getTransitChartForm( $options );
			case 'progression-chart':
				return $this->getProgressionChartForm( $options );
			case 'solar-return-chart':
				return $this->getSolarReturnChartForm( $options );
		}
	}

	/**
	 * Process result and render result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$report_type = $options['report_type'];

		switch ( $report_type ) {
			case 'natal-chart':
				return $this->getNatalChartProcess( $options );
			case 'transit-chart':
				return $this->getTransitChartProcess( $options );
			case 'progression-chart':
				return $this->getProgressionChartProcess( $options );
			case 'solar-return-chart':
				return $this->getSolarReturnChartProcess( $options );
		}
	}

	/**
	 * Render Natal Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getNatalChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => $datetime->modify( '-10 years' ),
				'aspect_filter'       => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'        => $house_system,
				'rectification_chart' => $rectification_chart,
				'birth_time_unknown'  => $birth_time_unknown,
				'orb'                 => $orb,
				'selected_lang'       => $form_language,
				'report_language'     => $report_language,
				'translation_data'    => $translation_data,
			]
		);
	}

	/**
	 * Process result and render Natal result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getNatalChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );

		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$is_planet_positions = in_array( 'planet-positions', $display_options, true );
		$is_planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new NatalChart( $client );
			$chart  = $method->process( $location, $datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new NatalAspectChart( $client );
			$aspect_chart = $method->process( $location, $datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $is_planet_positions || $is_planet_aspects ) {
			$method           = new NatalPlanetPositions( $client );
			$result           = $method->process( $location, $datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart );
			$planet_aspects   = $result->getAspects();
			$declinations     = $result->getDeclinations();
			$houses           = $result->getHouses();
			$planet_positions = $result->getPlanetPositions();
			$angles           = $result->getAngles();
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/natal-chart',
			[
				'chart'            => $chart ?? null,
				'aspect_chart'     => $aspect_chart ?? null,
				'declinations'     => $declinations ?? null,
				'houses'           => $houses ?? null,
				'angles'           => $angles ?? null,
				'planet_positions' => $planet_positions ?? null,
				'planet_aspects'   => $planet_aspects ?? null,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
				'display_options'  => $display_options,
			]
		);
	}

	/**
	 * Render Transit Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getTransitChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => $datetime->modify( '-10 years' ),
				'transit_datetime'    => $datetime,
				'aspect_filter'       => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'        => $house_system,
				'rectification_chart' => $rectification_chart,
				'birth_time_unknown'  => $birth_time_unknown,
				'orb'                 => $orb,
				'selected_lang'       => $form_language,
				'report_language'     => $report_language,
				'translation_data'    => $translation_data,
			]
		);
	}

	/**
	 * Process result and render transit result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getTransitChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$tz               = $this->get_timezone();
		$client           = $this->get_api_client();
		$location         = $this->get_location( $tz );
		$transit_tz       = $this->get_timezone( 'current_' );
		$transit_location = $this->get_location( $transit_tz, 'current_' );

		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$transit_datetime   = isset( $_POST['transit_datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['transit_datetime'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$transit_datetime = new DateTimeImmutable( $transit_datetime, $transit_tz );
		$lang             = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$is_planet_positions = in_array( 'planet-positions', $display_options, true );
		$is_planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new TransitChart( $client );
			$chart  = $method->process( $location, $datetime, $transit_location, $transit_datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new TransitAspectChart( $client );
			$aspect_chart = $method->process( $location, $datetime, $transit_location, $transit_datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $is_planet_positions || $is_planet_aspects ) {
			$method                = new TransitPlanetPositions( $client );
			$result                = $method->process( $location, $datetime, $transit_location, $transit_datetime, $house_system, $orb, $birth_time_unknown, $rectification_chart );
			$planet_aspects        = $result->getTransitDetails()->getAspects();
			$declinations          = $result->getTransitDetails()->getDeclinations();
			$houses                = $result->getTransitDetails()->getHouses();
			$planet_positions      = $result->getTransitDetails()->getPlanetPositions();
			$angles                = $result->getTransitDetails()->getAngles();
			$transit_natal_aspects = $result->getTransitNatalAspect();
		}
		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/transit-chart',
			[
				'chart'                 => $chart ?? null,
				'aspect_chart'          => $aspect_chart ?? null,
				'declinations'          => $declinations ?? null,
				'houses'                => $houses ?? null,
				'angles'                => $angles ?? null,
				'planet_positions'      => $planet_positions ?? null,
				'planet_aspects'        => $planet_aspects ?? null,
				'transit_natal_aspects' => $transit_natal_aspects ?? null,
				'options'               => $this->get_options(),
				'selected_lang'         => $lang,
				'translation_data'      => $translation_data,
				'display_options'       => $display_options,
			]
		);
	}

	/**
	 * Render Progression Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getProgressionChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$progression_year   = isset( $_POST['progression_year'] ) ? sanitize_text_field( wp_unslash( (int) $_POST['progression_year'] ) ) : $datetime->format( 'Y' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => $datetime->modify( '-10 years' ),
				'aspect_filter'       => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'        => $house_system,
				'rectification_chart' => $rectification_chart,
				'birth_time_unknown'  => $birth_time_unknown,
				'orb'                 => $orb,
				'selected_lang'       => $form_language,
				'report_language'     => $report_language,
				'translation_data'    => $translation_data,
				'progression_year'    => $progression_year,
			]
		);
	}

	/**
	 * Process result and render Progression result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getProgressionChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$tz                   = $this->get_timezone();
		$client               = $this->get_api_client();
		$location             = $this->get_location( $tz );
		$progression_tz       = $this->get_timezone( 'current_' );
		$progression_location = $this->get_location( $progression_tz, 'current_' );

		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$progression_year   = isset( $_POST['progression_year'] ) ? sanitize_text_field( wp_unslash( (int) $_POST['progression_year'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$is_planet_positions = in_array( 'planet-positions', $display_options, true );
		$is_planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new ProgressionChart( $client );
			$chart  = $method->process( $location, $datetime, $progression_location, $progression_year, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new ProgressionAspectChart( $client );
			$aspect_chart = $method->process( $location, $datetime, $progression_location, $progression_year, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $is_planet_positions || $is_planet_aspects ) {
			$method = new ProgressionPlanetPositions( $client );
			$result = $method->process( $location, $datetime, $progression_location, $progression_year, $house_system, $orb, $birth_time_unknown, $rectification_chart );

			$planet_aspects            = $result->getProgressionDetails()->getAspects();
			$declinations              = $result->getProgressionDetails()->getDeclinations();
			$houses                    = $result->getProgressionDetails()->getHouses();
			$planet_positions          = $result->getProgressionDetails()->getPlanetPositions();
			$angles                    = $result->getProgressionDetails()->getAngles();
			$progression_natal_aspects = $result->getProgressionNatalAspect();
			$progression_year          = $result->getProgressionYear();
			$progression_date          = $result->getProgressionDate();
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/progression-chart',
			[
				'progression'               => true,
				'chart'                     => $chart ?? null,
				'aspect_chart'              => $aspect_chart ?? null,
				'declinations'              => $declinations ?? null,
				'houses'                    => $houses ?? null,
				'angles'                    => $angles ?? null,
				'planet_positions'          => $planet_positions ?? null,
				'planet_aspects'            => $planet_aspects ?? null,
				'progression_natal_aspects' => $progression_natal_aspects ?? null,
				'progression_year'          => $progression_year ?? null,
				'progression_date'          => $progression_date ?? null,
				'options'                   => $this->get_options(),
				'selected_lang'             => $lang,
				'translation_data'          => $translation_data,
				'display_options'           => $display_options,
			]
		);
	}

	/**
	 * Render Solar Return Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getSolarReturnChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'report_language' => $report_language,
			'translation_data' => $translation_data,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$solar_return_year  = isset( $_POST['solar_return_year'] ) ? sanitize_text_field( wp_unslash( (int) $_POST['solar_return_year'] ) ) : $datetime->format( 'Y' );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/western-chart',
			[
				'options'             => $options + $this->get_options(),
				'datetime'            => $datetime->modify( '-10 years' ),
				'aspect_filter'       => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'        => $house_system,
				'rectification_chart' => $rectification_chart,
				'birth_time_unknown'  => $birth_time_unknown,
				'orb'                 => $orb,
				'selected_lang'       => $form_language,
				'report_language'     => $report_language,
				'translation_data'    => $translation_data,
				'solar_return_year'   => $solar_return_year,
			]
		);
	}

	/**
	 * Process result and render solar return result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getSolarReturnChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$tz               = $this->get_timezone();
		$client           = $this->get_api_client();
		$location         = $this->get_location( $tz );
		$current_tz       = $this->get_timezone( 'current_' );
		$current_location = $this->get_location( $current_tz, 'current_' );

		[
			'datetime' => $datetime,
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$birth_time_unknown = isset( $_POST['birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_unknown'] ) ) : '';
		$solar_return_year  = isset( $_POST['solar_return_year'] ) ? sanitize_text_field( wp_unslash( (int) $_POST['solar_return_year'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$is_planet_positions = in_array( 'planet-positions', $display_options, true );
		$is_planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new SolarReturnChart( $client );
			$chart  = $method->process( $location, $datetime, $current_location, $solar_return_year, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new SolarReturnAspectChart( $client );
			$aspect_chart = $method->process( $location, $datetime, $current_location, $solar_return_year, $house_system, $orb, $birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $is_planet_positions || $is_planet_aspects ) {
			$method = new SolarReturnPlanetPositions( $client );
			$result = $method->process( $location, $datetime, $current_location, $solar_return_year, $house_system, $orb, $birth_time_unknown, $rectification_chart );

			$planet_aspects      = $result->getSolarDetails()->getAspects();
			$declinations        = $result->getSolarDetails()->getDeclinations();
			$houses              = $result->getSolarDetails()->getHouses();
			$planet_positions    = $result->getSolarDetails()->getPlanetPositions();
			$angles              = $result->getSolarDetails()->getAngles();
			$solar_natal_aspects = $result->getSolarNatalAspect();
			$solar_year          = $result->getSolarReturnYear();
			$solar_date          = $result->getSolarDatetime();
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/solar-return-chart',
			[
				'progression'         => true,
				'chart'               => $chart ?? null,
				'aspect_chart'        => $aspect_chart ?? null,
				'declinations'        => $declinations ?? null,
				'houses'              => $houses ?? null,
				'angles'              => $angles ?? null,
				'planet_positions'    => $planet_positions ?? null,
				'planet_aspects'      => $planet_aspects ?? null,
				'solar_natal_aspects' => $solar_natal_aspects ?? null,
				'solar_year'          => $solar_year ?? null,
				'solar_date'          => $solar_date ?? null,
				'options'             => $this->get_options(),
				'selected_lang'       => $lang,
				'translation_data'    => $translation_data,
				'display_options'     => $display_options,
			]
		);
	}

	/**
	 * Process result and render result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return array
	 */
	private function getCommonInputValues( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$datetime         = $this->get_post_input( 'datetime', 'now' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		$display_options = explode( ',', $options['display_options'] );
		if ( in_array( 'all', $display_options, true ) ) {
			$display_options = [ 'chart', 'aspect-chart', 'planet-positions', 'planet-aspects' ];
		}
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$aspect_filter       = isset( $_POST['aspect_filter'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['aspect_filter'] ) ) : 'major';
		$house_system        = isset( $_POST['house_system'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['house_system'] ) ) : 'placidus';
		$orb                 = isset( $_POST['orb'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['orb'] ) ) : 'default';
		$rectification_chart = isset( $_POST['birth_time_rectification'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['birth_time_rectification'] ) ) : 'flat-chart';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		return [
			'datetime'            => new DateTimeImmutable( $datetime, $this->get_timezone() ),
			'form_language'       => $form_language,
			'report_language'     => $report_language,
			'translation_data'    => $translation_data,
			'display_options'     => $display_options,
			'aspect_filter'       => $aspect_filter,
			'house_system'        => $house_system,
			'orb'                 => $orb,
			'rectification_chart' => $rectification_chart,
		];
	}
}
