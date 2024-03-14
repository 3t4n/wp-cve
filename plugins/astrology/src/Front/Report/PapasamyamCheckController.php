<?php
/**
 * PapasamyamCheck controller.
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
use Prokerala\Api\Astrology\Result\Horoscope\Papasamyam;
use Prokerala\Api\Astrology\Service\PapaSamyamCheck;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;
use Prokerala\Api\Astrology\Profile;

/**
 * PapasamyamCheck Form Controller.
 *
 * @since   1.0.0
 */
class PapasamyamCheckController implements ReportControllerInterface {

	use ReportControllerTrait;

	private const REPORT_LANGUAGES = [
		'en',
		'hi',
		'ta',
		'ml',
	];
	/**
	 * PapasamyamCheckController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render Papasamyam Check form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ): string {
		$girl_dob         = $this->get_post_input( 'girl_dob', 'now' );
		$boy_dob          = $this->get_post_input( 'boy_dob', 'now' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		return $this->render(
			'form/papasamyam-check',
			[
				'options'          => $options + $this->get_options(),
				'girl_dob'         => new DateTimeImmutable( $girl_dob, $this->get_timezone( 'girl_' ) ),
				'boy_dob'          => new DateTimeImmutable( $boy_dob, $this->get_timezone( 'boy_' ) ),
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
	public function process( $options = [] ): string {

		$girl_tz       = $this->get_timezone( 'girl_' );
		$boy_tz        = $this->get_timezone( 'boy_' );
		$girl_location = $this->get_location( $girl_tz, 'girl_' );
		$boy_location  = $this->get_location( $boy_tz, 'boy_' );
		$client        = $this->get_api_client();

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$girl_dob = isset( $_POST['girl_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['girl_dob'] ) ) : '';
		$boy_dob  = isset( $_POST['boy_dob'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['boy_dob'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$girl_dob = new DateTimeImmutable( $girl_dob, $girl_tz );
		$boy_dob  = new DateTimeImmutable( $boy_dob, $boy_tz );

		$girl_profile = new Profile( $girl_location, $girl_dob );
		$boy_profile  = new Profile( $boy_location, $boy_dob );
		$method       = new PapaSamyamCheck( $client );
		$method->setAyanamsa( $this->get_input_ayanamsa() );

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		$result = $method->process( $girl_profile, $boy_profile, $lang );

		$message                             = $result->getMessage();
		$papa_samyam_check_result['message'] = [
			'type'        => $message->getType(),
			'description' => $message->getDescription(),
		];

		$papa_samyam_check_result['girlPapasamyam'] = $this->getPapasamyam( $result->getGirlPapasamyam() );
		$papa_samyam_check_result['boyPapasamyam']  = $this->getPapasamyam( $result->getBoyPapasamyam() );

		$translation_data = $this->get_localisation_data( $lang );

		return $this->render(
			'result/papasamyam-check',
			[
				'result'           => $papa_samyam_check_result,
				'options'          => $this->get_options(),
				'selected_lang'    => $lang,
				'translation_data' => $translation_data,
			]
		);
	}

	/**
	 * Papasamyam details
	 *
	 * @param Papasamyam $papasamyam papasamyam data.
	 * @return array
	 */
	public function getPapasamyam( Papasamyam $papasamyam ): array {
		$papa_samyam_result                = [];
		$papa_samyam_result['total_point'] = $papasamyam->getTotalPoints();
		$papa_samyam                       = $papasamyam->getPapaSamyam();
		$papa_planets                      = $papa_samyam->getPapaPlanet();
		foreach ( $papa_planets as $idx => $papa_planet ) {
			$papa_samyam_result['papaPlanet'][ $idx ]['name'] = $papa_planet->getName();
			$planet_doshas                                    = $papa_planet->getPlanetDosha();
			foreach ( $planet_doshas as $planet_dosha ) {
				$papa_samyam_result['papaPlanet'][ $idx ]['planetDosha'][] = [
					'id'       => $planet_dosha->getId(),
					'name'     => $planet_dosha->getName(),
					'position' => $planet_dosha->getPosition(),
					'hasDosha' => $planet_dosha->hasDosha(),
				];
			}
		}

		return $papa_samyam_result;
	}
}
