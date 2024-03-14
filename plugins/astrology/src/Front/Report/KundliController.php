<?php
/**
 * Kundli controller.
 *
 * @package   Prokerala\WP\Astrology
 * @subpackage WordPress Plugin
 * @author    Prokerala<support+api@prokerala.com>
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
use DateTimeInterface;
use Exception;
use Prokerala\Api\Astrology\Location;
use Prokerala\Api\Astrology\Result\Horoscope\AdvancedKundli;
use Prokerala\Api\Astrology\Service\Chart;
use Prokerala\Api\Astrology\Service\Kundli;
use Prokerala\Common\Api\Client;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Kundli Form Controller.
 *
 * @since   1.0.0
 */
class KundliController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	private const REPORT_LANGUAGES = [
		'en',
		'hi',
		'ta',
		'ml',
	];
	/**
	 * KundliController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( array $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render kundli form.
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
			'form/kundli',
			[
				'options'          => $options + $this->get_options(),
				'datetime'         => new DateTimeImmutable( $datetime, $this->get_timezone() ),
				'result_type'      => $result_type,
				'report_language'  => $report_language,
				'selected_lang'    => $form_language,
				'translation_data' => $translation_data,
			]
		);
	}

	/**
	 * Process result
	 *
	 * @param Client            $client API Client.
	 * @param Location          $location User location.
	 * @param DateTimeInterface $datetime Datetime.
	 * @param bool              $advanced Whether to return detailed report.
	 * @param string            $result_lang language of report.
	 * @return array
	 * @throws Exception On API query failure.
	 *
	 * @since 1.0.1
	 */
	protected function get_kundli_details(
		Client $client,
		Location $location,
		DateTimeInterface $datetime,
		bool $advanced,
		string $result_lang
	): array {

		$method = new Kundli( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result            = $method->process( $location, $datetime, $advanced, $result_lang );
		$nakshatra_details = $result->getNakshatraDetails();
		$nakshatra         = $nakshatra_details->getNakshatra();
		$nakshatra_lord    = $nakshatra->getLord();

		$chandra_rasi      = $nakshatra_details->getChandraRasi();
		$chandra_rasi_lord = $chandra_rasi->getLord();
		$soorya_rasi       = $nakshatra_details->getSooryaRasi();
		$soorya_rasi_lord  = $soorya_rasi->getLord();
		$zodiac            = $nakshatra_details->getZodiac();
		$additional_info   = $nakshatra_details->getAdditionalInfo();
		$mangal_dosha      = $result->getMangalDosha();
		$yoga_details      = $result->getYogaDetails();

		$kundli_result = [
			'nakshatra_details' => [
				'nakshatra'       => [
					'id'   => $nakshatra->getId(),
					'name' => $nakshatra->getName(),
					'lord' => [
						'id'         => $nakshatra_lord->getId(),
						'name'       => $nakshatra_lord->getName(),
						'vedic_name' => $nakshatra_lord->getVedicName(),
					],
					'pada' => $nakshatra->getPada(),
				],
				'chandra_rasi'    => [
					'id'   => $chandra_rasi->getId(),
					'name' => $chandra_rasi->getName(),
					'lord' => [
						'id'         => $chandra_rasi_lord->getId(),
						'name'       => $chandra_rasi_lord->getName(),
						'vedic_name' => $chandra_rasi_lord->getVedicName(),
					],
				],
				'soorya_rasi'     => [
					'id'   => $soorya_rasi->getId(),
					'name' => $soorya_rasi->getName(),
					'lord' => [
						'id'         => $soorya_rasi_lord->getId(),
						'name'       => $soorya_rasi_lord->getName(),
						'vedic_name' => $soorya_rasi_lord->getVedicName(),
					],
				],
				'zodiac'          => [
					'id'   => $zodiac->getId(),
					'name' => $zodiac->getName(),
				],
				'additional_info' => [
					'deity'          => $additional_info->getDeity(),
					'ganam'          => $additional_info->getGanam(),
					'symbol'         => $additional_info->getSymbol(),
					'animal_sign'    => $additional_info->getAnimalsign(),
					'nadi'           => $additional_info->getNadi(),
					'color'          => $additional_info->getColor(),
					'best_direction' => $additional_info->getBestdirection(),
					'syllables'      => $additional_info->getSyllables(),
					'birth_stone'    => $additional_info->getBirthstone(),
					'gender'         => $additional_info->getGender(),
					'planet'         => $additional_info->getPlanet(),
					'enemy_yoni'     => $additional_info->getEnemyYoni(),
				],
			],
			'mangal_dosha'      => [
				'has_dosha'   => $mangal_dosha->hasDosha(),
				'description' => $mangal_dosha->getDescription(),
			],
		];

		if ( $advanced ) {
			$kundli_advanced_info = $this->getAdvancedInfo( $result );
			$kundli_result        = array_merge( $kundli_result, $kundli_advanced_info );

		} else {
			$yoga_detail_result = [];
			foreach ( $yoga_details as $details ) {
				$yoga_detail_result[] = [
					'name'        => $details->getName(),
					'description' => $details->getDescription(),
				];
			}
			$kundli_result['yoga_details'] = $yoga_detail_result;
		}

		return $kundli_result;
	}

	/**
	 * Process result
	 *
	 * @param Client            $client API Client.
	 * @param Location          $location User location.
	 * @param DateTimeInterface $datetime Datetime.
	 * @param string            $chart_type Chart type.
	 * @param string            $chart_style Chart style.
	 * @return string
	 * @throws Exception On API query failure.
	 *
	 * @since 1.0.1
	 */
	protected function get_chart( Client $client, Location $location, DateTimeInterface $datetime, string $chart_type, string $chart_style ): string {

		$method = new Chart( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );

		return $method->process( $location, $datetime, $chart_type, $chart_style );
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
		$tz       = $this->get_timezone();
		$client   = $this->get_api_client();
		$location = $this->get_location( $tz );

		$datetime    = $this->get_post_input( 'datetime' );
		$result_type = $options['result_type'] ? $options['result_type'] : $this->get_post_input( 'result_type', 'basic' );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		$datetime = new DateTimeImmutable( $datetime, $tz );
		$advanced = 'advanced' === $result_type;

		$kundli_result = $this->get_kundli_details( $client, $location, $datetime, $advanced, $lang );

		if ( $options['display_charts'] ) {
			$chart_style = $options['chart_style'] ?? 'north-indian';

			$kundli_result['charts'] = [
				'lagna'   => $this->get_chart( $client, $location, $datetime, 'lagna', $chart_style ),
				'navamsa' => $this->get_chart( $client, $location, $datetime, 'navamsa', $chart_style ),
			];
		}

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/kundli',
			[
				'result'           => $kundli_result,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
			]
		);
	}

	/**
	 * Advanced result.
	 *
	 * @param AdvancedKundli $result Kundli result.
	 * @return array
	 */
	public function getAdvancedInfo( AdvancedKundli $result ): array {
		$mangal_dosha                  = $result->getMangalDosha();
		$yoga_details                  = $result->getYogaDetails();
		$kundli_result                 = [];
		$kundli_result['mangal_dosha'] = [
			'has_dosha'     => $mangal_dosha->hasDosha(),
			'description'   => $mangal_dosha->getDescription(),
			'has_exception' => $mangal_dosha->hasException(),
			'type'          => $mangal_dosha->getType(),
			'exceptions'    => $mangal_dosha->getExceptions(),
		];

		$yoga_detail_result = [];

		foreach ( $yoga_details as $details ) {
			$yoga_list = $details->getYogaList();
			$yogas     = [];
			foreach ( $yoga_list as $yoga ) {
				$yogas[] = [
					'name'        => $yoga->getName(),
					'hasYoga'     => $yoga->hasYoga(),
					'description' => $yoga->getDescription(),
				];
			}
			$yoga_detail_result[] = [
				'name'        => $details->getName(),
				'description' => $details->getDescription(),
				'yogaList'    => $yogas,
			];
		}

		$kundli_result['yoga_details']  = $yoga_detail_result;
		$kundli_result['dasha_periods'] = $this->getDashaPeriodsDetails( $result->getDashaPeriods() );

		return $kundli_result;
	}

	/**
	 * Dasha period details.
	 *
	 * @param array $dasha_periods dashaperiods.
	 * @return array
	 */
	public function getDashaPeriodsDetails( array $dasha_periods ): array { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		$dasha_period_result = [];
		foreach ( $dasha_periods as $dasha_period ) {
			$antardashas       = $dasha_period->getAntardasha();
			$antardasha_result = [];
			foreach ( $antardashas as $antardasha ) {
				$pratyantardashas       = $antardasha->getPratyantardasha();
				$pratyantardasha_result = [];
				foreach ( $pratyantardashas as $pratyantardasha ) {
					$pratyantardasha_result[] = [
						'id'    => $pratyantardasha->getId(),
						'name'  => $pratyantardasha->getName(),
						'start' => $pratyantardasha->getStart(),
						'end'   => $pratyantardasha->getEnd(),
					];
				}
				$antardasha_result[] = [
					'id'              => $antardasha->getId(),
					'name'            => $antardasha->getName(),
					'start'           => $antardasha->getStart(),
					'end'             => $antardasha->getEnd(),
					'pratyantardasha' => $pratyantardasha_result,
				];
			}
			$dasha_period_result[] = [
				'id'         => $dasha_period->getId(),
				'name'       => $dasha_period->getName(),
				'start'      => $dasha_period->getStart(),
				'end'        => $dasha_period->getEnd(),
				'antardasha' => $antardasha_result,
			];
		}
		return $dasha_period_result;
	}

	/**
	 * Get default values for supported attributes.
	 *
	 * @since 1.1.0
	 *
	 * @return array<string,mixed>
	 */
	public function get_attribute_defaults(): array {
		return $this->getCommonAttributeDefaults() + [
			'display_charts' => '',
			'chart_style'    => 'north-indian',
		];
	}
}
