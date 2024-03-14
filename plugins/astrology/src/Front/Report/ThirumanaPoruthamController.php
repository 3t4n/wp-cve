<?php
/**
 * ThirumanaPorutham controller.
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

use Exception;
use Prokerala\Api\Astrology\NakshatraProfile;
use Prokerala\Api\Astrology\Service\ThirumanaPorutham;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * ThirumanaPorutham Form Controller.
 *
 * @since   1.0.0
 */
class ThirumanaPoruthamController implements ReportControllerInterface {

	use ReportControllerTrait;

	private const REPORT_LANGUAGES = [
		'en',
	];
	/**
	 * ThirumanaPoruthamController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( array $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render ThirumanaPorutham form.
	 *
	 * @throws Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ): string {
		$result_type      = $options['result_type'] ? $options['result_type'] : $this->get_post_input( 'result_type', 'basic' );
		$form_language    = $this->get_form_language( $options['form_language'], self::REPORT_LANGUAGES );
		$report_language  = $this->filter_report_language( $options['report_language'], self::REPORT_LANGUAGES );
		$translation_data = $this->get_localisation_data( $form_language );

		return $this->render(
			'form/thirumana-porutham',
			[
				'options'          => $options + $this->get_options(),
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
	public function process( $options = [] ): string { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$client = $this->get_api_client();

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$girl_nakshatra      = isset( $_POST['girl_nakshatra'] ) ? (int) $_POST['girl_nakshatra'] : '';
		$boy_nakshatra       = isset( $_POST['boy_nakshatra'] ) ? (int) $_POST['boy_nakshatra'] : '';
		$girl_nakshatra_pada = isset( $_POST['girl_nakshatra_pada'] ) ? (int) $_POST['girl_nakshatra_pada'] : '';
		$boy_nakshatra_pada  = isset( $_POST['boy_nakshatra_pada'] ) ? (int) $_POST['boy_nakshatra_pada'] : '';
		$result_type         = isset( $_POST['result_type'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['result_type'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$girl_profile = new NakshatraProfile( $girl_nakshatra, $girl_nakshatra_pada );
		$boy_profile  = new NakshatraProfile( $boy_nakshatra, $boy_nakshatra_pada );
		$advanced     = 'advanced' === $result_type;

		$lang = $this->get_post_language( 'lang', self::REPORT_LANGUAGES, $options['form_language'] );

		$method = new ThirumanaPorutham( $client );
		$result = $method->process( $girl_profile, $boy_profile, $advanced, $lang );

		$compatibility_result = $this->get_compatibility_result( $result, $advanced );

		return $this->render(
			'result/thirumana-porutham',
			[
				'result'        => $compatibility_result,
				'result_type'   => $result_type,
				'options'       => $this->get_options(),
				'selected_lang' => $lang,
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
	private function get_compatibility_result( object $result, int $advanced ): array {

		$matches = [];

		foreach ( $result->getMatches() as $idx => $val ) {
			$match = [
				'id'          => $val->getId(),
				'name'        => $val->getName(),
				'hasPorutham' => $val->hasPorutham(),
			];
			if ( $advanced ) {
				$match += [
					'points'      => $val->getPoints(),
					'description' => $val->getDescription(),
				];
			}
			$matches[ $idx ] = $match;
		}

		$message = $result->getMessage();

		return [
			'maximumPoint'  => $result->getMaximumPoints(),
			'ObtainedPoint' => $result->getObtainedPoints(),
			'message'       => [
				'type'        => $message->getType(),
				'description' => $message->getDescription(),
			],
			'matches'       => $matches,
		];
	}
}
