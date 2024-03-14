<?php
/**
 * KundliMatching controller.
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

use Prokerala\Api\Astrology\Service\KundliMatching;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;
use Prokerala\Api\Astrology\Profile;

/**
 * KundliMatching Form Controller.
 *
 * @since   1.0.0
 */
class KundliMatchingController implements ReportControllerInterface {

	use ReportControllerTrait;

	private const REPORT_LANGUAGES = [
		'en',
		'hi',
	];
	/**
	 * KundliMatchingController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render kundli-matching form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		$girl_dob         = $this->get_post_input( 'girl_dob', 'now' );
		$boy_dob          = $this->get_post_input( 'boy_dob', 'now' );
		$result_type      = $options['result_type'] ? $options['result_type'] : $this->get_post_input( 'result_type', 'basic' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		return $this->render(
			'form/kundli-matching',
			[
				'options'          => $options + $this->get_options(),
				'girl_dob'         => new \DateTimeImmutable( $girl_dob, $this->get_timezone( 'girl_' ) ),
				'boy_dob'          => new \DateTimeImmutable( $boy_dob, $this->get_timezone( 'boy_' ) ),
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
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function process( $options = [] ) {
		$girl_tz       = $this->get_timezone( 'girl_' );
		$boy_tz        = $this->get_timezone( 'boy_' );
		$client        = $this->get_api_client();
		$girl_location = $this->get_location( $girl_tz, 'girl_' );
		$boy_location  = $this->get_location( $boy_tz, 'boy_' );
		$girl_dob      = $this->get_post_input( 'girl_dob', '' );
		$boy_dob       = $this->get_post_input( 'boy_dob', '' );
		$result_type   = $options['result_type'] ? $options['result_type'] : $this->get_post_input( 'result_type', 'basic' );
		$lang          = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		$advanced = 'advanced' === $result_type;

		$girl_dob = new \DateTimeImmutable( $girl_dob, $girl_tz );
		$boy_dob  = new \DateTimeImmutable( $boy_dob, $boy_tz );

		$girl_profile = new Profile( $girl_location, $girl_dob );
		$boy_profile  = new Profile( $boy_location, $boy_dob );

		$method = new KundliMatching( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );
		$result = $method->process( $girl_profile, $boy_profile, $advanced, $lang );

		$compatibility_result = $this->get_compatibility_result( $result, $advanced );

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/kundli-matching',
			[
				'result'           => $compatibility_result,
				'result_type'      => $result_type,
				'girl_dob'         => $girl_dob,
				'boy_dob'          => $boy_dob,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
			]
		);
	}

	/**
	 * Get compatability result
	 *
	 * @param object $result API Result.
	 * @param int    $advanced Advanced Result.
	 * @return array
	 */
	private function get_compatibility_result( $result, $advanced ) {
		$compatibility_result = [];
		$girl_info            = $result->getGirlInfo();
		$girl_nakshatra       = $girl_info->getNakshatra();
		$girl_nakshatra_lord  = $girl_nakshatra->getLord();
		$girl_rasi            = $girl_info->getRasi();
		$girl_rasi_lord       = $girl_rasi->getLord();
		$girl_koot            = $girl_info->getKoot();

		$compatibility_result['girlInfo']['koot']      = $girl_koot->getKoot();
		$compatibility_result['girlInfo']['nakshatra'] = [
			'id'   => $girl_nakshatra->getId(),
			'name' => $girl_nakshatra->getName(),
			'pada' => $girl_nakshatra->getPada(),
			'lord' => [
				'id'        => $girl_nakshatra_lord->getId(),
				'name'      => $girl_nakshatra_lord->getName(),
				'vedicName' => $girl_nakshatra_lord->getVedicName(),
			],
		];

		$compatibility_result['girlInfo']['rasi'] = [
			'id'   => $girl_rasi->getId(),
			'name' => $girl_rasi->getName(),
			'lord' => [
				'id'        => $girl_rasi_lord->getId(),
				'name'      => $girl_rasi_lord->getName(),
				'vedicName' => $girl_rasi_lord->getVedicName(),
			],
		];

		$boy_info           = $result->getBoyInfo();
		$boy_nakshatra      = $boy_info->getNakshatra();
		$boy_nakshatra_lord = $boy_nakshatra->getLord();
		$boy_rasi           = $boy_info->getRasi();
		$boy_rasi_lord      = $boy_rasi->getLord();

		$boy_koot                                     = $boy_info->getKoot();
		$compatibility_result['boyInfo']['koot']      = $boy_koot->getKoot();
		$compatibility_result['boyInfo']['nakshatra'] = [
			'id'   => $boy_nakshatra->getId(),
			'name' => $boy_nakshatra->getName(),
			'pada' => $boy_nakshatra->getPada(),
			'lord' => [
				'id'        => $boy_nakshatra_lord->getId(),
				'name'      => $boy_nakshatra_lord->getName(),
				'vedicName' => $boy_nakshatra_lord->getVedicName(),
			],
		];
		$compatibility_result['boyInfo']['rasi']      = [
			'id'   => $boy_rasi->getId(),
			'name' => $boy_rasi->getName(),
			'lord' => [
				'id'        => $boy_rasi_lord->getId(),
				'name'      => $boy_rasi_lord->getName(),
				'vedicName' => $boy_rasi_lord->getVedicName(),
			],
		];

		$message                         = $result->getMessage();
		$compatibility_result['message'] = [
			'type'        => $message->getType(),
			'description' => $message->getDescription(),
		];

		$guna_milan                        = $result->getGunaMilan();
		$compatibility_result['gunaMilan'] = [
			'totalPoints'   => $guna_milan->getTotalPoints(),
			'maximumPoints' => $guna_milan->getMaximumPoints(),
		];

		if ( ! $advanced ) {
			return $compatibility_result;
		}

		$ar_guna = $guna_milan->getGuna();

		foreach ( $ar_guna as $guna ) {
			$compatibility_result['gunaMilan']['guna'][] = [
				'id'             => $guna->getId(),
				'name'           => $guna->getName(),
				'girlKoot'       => $guna->getGirlKoot(),
				'boyKoot'        => $guna->getBoyKoot(),
				'maximumPoints'  => $guna->getMaximumPoints(),
				'obtainedPoints' => $guna->getObtainedPoints(),
				'description'    => $guna->getDescription(),
			];
		}
		$compatibility_result['exceptions'] = $result->getExceptions();

		$girl_mangal_dosha_details = $result->getGirlMangalDoshaDetails();
		$boy_mangal_dosha_details  = $result->getBoyMangalDoshaDetails();

		$compatibility_result['girlMangalDoshaDetails'] = [
			'hasMangalDosha'  => $girl_mangal_dosha_details->hasDosha(),
			'hasException'    => $girl_mangal_dosha_details->hasException(),
			'mangalDoshaType' => $girl_mangal_dosha_details->getDoshaType(),
			'description'     => $girl_mangal_dosha_details->getDescription(),
		];

		$compatibility_result['boyMangalDoshaDetails'] = [
			'hasMangalDosha'  => $boy_mangal_dosha_details->hasDosha(),
			'hasException'    => $boy_mangal_dosha_details->hasException(),
			'mangalDoshaType' => $boy_mangal_dosha_details->getDoshaType(),
			'description'     => $boy_mangal_dosha_details->getDescription(),
		];

		return $compatibility_result;
	}
}
