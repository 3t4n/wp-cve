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
use Prokerala\Api\Astrology\Western\Service\AspectCharts\CompositeChart as CompositeAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\NatalChart as NatalAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\ProgressionChart as ProgressionAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\SolarReturnChart as SolarReturnAspectChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\SynastryChart as SynastryAspectChart;
use Prokerala\Api\Astrology\Western\Service\Charts\CompositeChart;
use Prokerala\Api\Astrology\Western\Service\Charts\NatalChart;
use Prokerala\Api\Astrology\Western\Service\Charts\ProgressionChart;
use Prokerala\Api\Astrology\Western\Service\Charts\SolarReturnChart;
use Prokerala\Api\Astrology\Western\Service\Charts\SynastryChart;
use Prokerala\Api\Astrology\Western\Service\Charts\TransitChart;
use Prokerala\Api\Astrology\Western\Service\AspectCharts\TransitChart as TransitAspectChart;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\CompositeChart as CompositePlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\NatalChart as NatalPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\ProgressionChart as ProgressionPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\SolarReturnChart as SolarReturnPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\SynastryChart as SynastryPlanetPositions;
use Prokerala\Api\Astrology\Western\Service\PlanetPositions\TransitChart as TransitPlanetPositions;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Western Chart Form Controller.
 *
 * @since   1.3.0
 */
class CompatibilityChartController implements ReportControllerInterface {

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
			'report_type'     => 'synastry-chart',
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
	public function render_form( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$report_type = $options['report_type'];

		switch ( $report_type ) {
			case 'synastry-chart':
				return $this->getSynastryChartForm( $options );
			case 'composite-chart':
				return $this->getCompositeChartForm( $options );
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
	public function process( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$report_type = $options['report_type'];

		switch ( $report_type ) {
			case 'synastry-chart':
				return $this->getSynastryChartProcess( $options );
			case 'composite-chart':
				return $this->getCompositeChartProcess( $options );
		}
	}
	/**
	 * Render Synastry Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getSynastryChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
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
		$chart_type                   = isset( $_POST['chart_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['chart_type'] ) ) : 'zodiac-contact-chart';
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/compatability-chart',
			[
				'options'                      => $options + $this->get_options(),
				'primary_birth_time'           => $datetime->modify( '-25 years' ),
				'secondary_birth_time'         => $datetime->modify( '-20 years' ),
				'aspect_filter'                => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'                 => $house_system,
				'primary_birth_time_unknown'   => $primary_birth_time_unknown,
				'secondary_birth_time_unknown' => $secondary_birth_time_unknown,
				'rectification_chart'          => $rectification_chart,
				'chart_type'                   => $chart_type,
				'orb'                          => $orb,
				'selected_lang'                => $form_language,
				'report_language'              => $report_language,
				'translation_data'             => $translation_data,
			]
		);
	}

	/**
	 * Process result and render Synastry result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getSynastryChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$client                 = $this->get_api_client();
		$primary_tz             = $this->get_timezone( 'partner_a_' );
		$primary_birth_location = $this->get_location( $primary_tz, 'partner_a_' );

		$secondary_tz             = $this->get_timezone( 'partner_b_' );
		$secondary_birth_location = $this->get_location( $secondary_tz, 'partner_b_' );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = isset( $_POST['partner_a_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_dob'] ) ) : '';
		$secondary_birth_time = isset( $_POST['partner_b_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_b_dob'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = new DateTimeImmutable( $primary_birth_time, $primary_tz );
		$secondary_birth_time = new DateTimeImmutable( $secondary_birth_time, $secondary_tz );
		[
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		]                     = $this->getCommonInputValues( $options );

		if ( in_array( 'all', $display_options, true ) ) {
			$display_options = [ 'chart', 'aspect-chart', 'planet-aspects' ];
		} else {
			$display_options = array_filter(
				$display_options,
				function ( $option ) {
					return in_array( $option, [ 'chart', 'aspect-chart', 'planet-aspects' ], true );
				}
			);
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$chart_type                   = isset( $_POST['chart_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['chart_type'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new SynastryChart( $client );
			$chart  = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $house_system, $chart_type, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}

		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new SynastryAspectChart( $client );
			$aspect_chart = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $house_system, $chart_type, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'planet-aspects', $display_options, true ) ) {
			$method         = new SynastryPlanetPositions( $client );
			$result         = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $house_system, $chart_type, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart );
			$planet_aspects = $result->getAspects();
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/synastry-chart',
			[
				'chart'            => $chart ?? null,
				'aspect_chart'     => $aspect_chart ?? null,
				'planet_aspects'   => $planet_aspects ?? null,
				'synastry'         => true,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
				'display_options'  => $display_options,
			]
		);
	}

	/**
	 * Render Composite Chart form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getCompositeChartForm( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
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

		$transit_datetime = $datetime;
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		return $this->render(
			'form/compatability-chart',
			[
				'options'                      => $options + $this->get_options(),
				'primary_birth_time'           => $datetime->modify( '-25 years' ),
				'secondary_birth_time'         => $datetime->modify( '-20 years' ),
				'aspect_filter'                => ( in_array( 'planet-positions', $display_options, true ) || in_array( 'planet-aspects', $display_options, true ) ) ? null : $aspect_filter,
				'house_system'                 => $house_system,
				'primary_birth_time_unknown'   => $primary_birth_time_unknown,
				'secondary_birth_time_unknown' => $secondary_birth_time_unknown,
				'rectification_chart'          => $rectification_chart,
				'transit_datetime'             => $transit_datetime,
				'orb'                          => $orb,
				'selected_lang'                => $form_language,
				'report_language'              => $report_language,
				'translation_data'             => $translation_data,
			]
		);
	}

	/**
	 * Process result and render Composite result.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	private function getCompositeChartProcess( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$client                 = $this->get_api_client();
		$primary_tz             = $this->get_timezone( 'partner_a_' );
		$primary_birth_location = $this->get_location( $primary_tz, 'partner_a_' );

		$secondary_tz             = $this->get_timezone( 'partner_b_' );
		$secondary_birth_location = $this->get_location( $secondary_tz, 'partner_b_' );

		$transit_tz       = $this->get_timezone( 'current_' );
		$current_location = $this->get_location( $transit_tz );
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = isset( $_POST['partner_a_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_dob'] ) ) : '';
		$secondary_birth_time = isset( $_POST['partner_b_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_b_dob'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$primary_birth_time   = new DateTimeImmutable( $primary_birth_time, $primary_tz );
		$secondary_birth_time = new DateTimeImmutable( $secondary_birth_time, $secondary_tz );

		[
			'form_language' => $form_language,
			'display_options' => $display_options,
			'aspect_filter' => $aspect_filter,
			'house_system' => $house_system,
			'orb' => $orb,
			'rectification_chart' => $rectification_chart,
		] = $this->getCommonInputValues( $options );

		if ( in_array( 'all', $display_options, true ) ) {
			$display_options = [ 'chart', 'aspect-chart', 'planet-positions', 'planet-aspects' ];
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$primary_birth_time_unknown   = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$secondary_birth_time_unknown = isset( $_POST['partner_a_birth_time_unknown'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['partner_a_birth_time_unknown'] ) ) : '';
		$transit_datetime             = isset( $_POST['transit_datetime'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['transit_datetime'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$transit_datetime = new DateTimeImmutable( $transit_datetime, $transit_tz );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $form_language );

		$is_planet_positions = in_array( 'planet-positions', $display_options, true );
		$is_planet_aspects   = in_array( 'planet-aspects', $display_options, true );

		if ( in_array( 'chart', $display_options, true ) ) {
			$method = new CompositeChart( $client );
			$chart  = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $current_location, $transit_datetime, $house_system, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( in_array( 'aspect-chart', $display_options, true ) ) {
			$method       = new CompositeAspectChart( $client );
			$aspect_chart = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $current_location, $transit_datetime, $house_system, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart, $aspect_filter );
		}
		if ( $is_planet_positions || $is_planet_aspects ) {
			$method           = new CompositePlanetPositions( $client );
			$result           = $method->process( $primary_birth_location, $primary_birth_time, $secondary_birth_location, $secondary_birth_time, $current_location, $transit_datetime, $house_system, $orb, $primary_birth_time_unknown, $secondary_birth_time_unknown, $rectification_chart );
			$planet_aspects   = $result->getCompositeAspects();
			$houses           = $result->getCompositeHouses();
			$planet_positions = $result->getCompositePlanetPositions();
			$angles           = $result->getCompositeAngles();
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/composite-chart',
			[
				'chart'            => $chart ?? null,
				'aspect_chart'     => $aspect_chart ?? null,
				'planet_aspects'   => $planet_aspects ?? null,
				'houses'           => $houses ?? null,
				'planet_positions' => $planet_positions ?? null,
				'angles'           => $angles ?? null,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
				'display_options'  => $display_options,
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
	private function getCommonInputValues( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$datetime         = $this->get_post_input( 'datetime', 'now' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		$display_options = explode( ',', $options['display_options'] );

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
