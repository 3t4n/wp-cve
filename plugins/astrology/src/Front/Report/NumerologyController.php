<?php
/**
 * Birth Details controller.
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

use Prokerala\Api\Numerology\Service\Chaldean\LifePathNumber as ChaldeanLifePathNumber;
use Prokerala\Api\Numerology\Service\Chaldean\BirthNumber;
use Prokerala\Api\Numerology\Service\Chaldean\DailyNameNumber;
use Prokerala\Api\Numerology\Service\Chaldean\IdentityInitialCode;
use Prokerala\Api\Numerology\Service\Chaldean\WholeNameNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\AttainmentNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\BalanceNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\BirthdayNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\BirthMonthNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\BridgeNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\CapstoneNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\ChallengeNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\CornerstoneNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\DestinyNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\ExpressionNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\HiddenPassionNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\InnerDreamNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\KarmicDebtNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\LifeCycleNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\LifePathNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\MaturityNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\PersonalDayNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\PersonalityNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\PersonalMonthNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\PersonalYearNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\PinnacleNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\RationalThoughtNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\SoulUrgeNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\SubconsciousSelfNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\UniversalDayNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\UniversalMonthNumber;
use Prokerala\Api\Numerology\Service\Pythagorean\UniversalYearNumber;
use Prokerala\WP\Astrology\Front\Controller\ReportControllerTrait;
use Prokerala\WP\Astrology\Front\ReportControllerInterface;

/**
 * Numerology Form Controller.
 *
 * @since   1.1.0
 */
class NumerologyController implements ReportControllerInterface {

	use ReportControllerTrait {
		get_attribute_defaults as getCommonAttributeDefaults;
	}

	const DATE          = 0;
	const DATE_REF_YEAR = 1;
	const DATE_NAME     = 2;
	const NAME          = 3;
	const NAME_VOWEL    = 4;

	const CALCULATORS = [
		'pythagorean' => [
			'life-path-number'         => [ 'Life Path Number', LifePathNumber::class, self::DATE ],
			'capstone-number'          => [ 'Capstone Number', CapStoneNumber::class, self::NAME ],
			'personality-number'       => [ 'Personality Number', PersonalityNumber::class, self::NAME_VOWEL ],
			'challenge-number'         => [ 'Challenge Number', ChallengeNumber::class, self::DATE ],
			'inner-dream-number'       => [ 'Inner Dream Number', InnerDreamNumber::class, self::NAME_VOWEL ],
			'personal-year-number'     => [ 'Personal Year Number', PersonalYearNumber::class, self::DATE_REF_YEAR ],
			'expression-number'        => [ 'Expression Number', ExpressionNumber::class, self::NAME ],
			'universal-month-number'   => [ 'Universal Month Number', UniversalMonthNumber::class, self::DATE ],
			'personal-month-number'    => [ 'Personal Month Number', PersonalMonthNumber::class, self::DATE_REF_YEAR ],
			'soul-urge-number'         => [ 'Soul Urge Number', SoulUrgeNumber::class, self::NAME ],
			'destiny-number'           => [ 'Destiny Number', DestinyNumber::class, self::NAME ],
			'attainment-number'        => [ 'Attainment Number', AttainmentNumber::class, self::DATE_NAME ],
			'birthday-number'          => [ 'Birth Day Number', BirthDayNumber::class, self::DATE ],
			'universal-day-number'     => [ 'Universal Day Number', UniversalDayNumber::class, self::DATE ],
			'birth-month-number'       => [ 'Birth Month Number', BirthMonthNumber::class, self::DATE ],
			'universal-year-number'    => [ 'Universal Year Number', UniversalYearNumber::class, self::DATE ],
			'balance-number'           => [ 'Balance Number', BalanceNumber::class, self::NAME ],
			'personal-day-number'      => [ 'Personal Day Number', PersonalDayNumber::class, self::DATE_REF_YEAR ],
			'cornerstone-number'       => [ 'Cornerstone Number', CornerStoneNumber::class, self::NAME ],
			'subconscious-self-number' => [ 'Subconscious Self Number', SubconsciousSelfNumber::class, self::NAME ],
			'maturity-number'          => [ 'Maturity Number', MaturityNumber::class, self::DATE_NAME ],
			'hidden-passion-number'    => [ 'Hidden Passion Number', HiddenPassionNumber::class, self::NAME ],
			'rational-thought-number'  => [ 'Rational Thought Number', RationalThoughtNumber::class, self::DATE_NAME ],
			'pinnacle-number'          => [ 'Pinnacle Number', PinnacleNumber::class, self::DATE ],
			'karmic-debt-number'       => [ 'Karmic Debt Number', KarmicDebtNumber::class, self::DATE_NAME ],
			'bridge-number'            => [ 'Bridge Number', BridgeNumber::class, self::DATE_NAME ],
			'life-cycle-number'        => [ 'Life Cycle Number', LifeCycleNumber::class, self::DATE ],
		],
		'chaldean'    => [
			'birth-number'                 => [ 'Birth Number', BirthNumber::class, self::DATE ],
			'daily-name-number'            => [ 'Daily Name Number', DailyNameNumber::class, self::NAME ],
			'life-path-number'             => [ 'Life Path Number', ChaldeanLifePathNumber::class, self::DATE ],
			'identity-initial-code-number' => [ 'Identity Initial Code Number', IdentityInitialCode::class, self::NAME ],
			'whole-name-number'            => [ 'Whole Name Number', WholeNameNumber::class, self::NAME ],
		],
	];

	/**
	 * NumerologyController constructor
	 *
	 * @param array<string,string> $options Plugin options.
	 */
	public function __construct( $options ) {
		$this->set_options( $options );
	}

	/**
	 * Render birthdetails form.
	 *
	 * @throws \Exception On render failure.
	 *
	 * @param array $options Render options.
	 * @return string
	 */
	public function render_form( $options = [] ) {
		$calculators = array_map(
			function ( $calculators ) {
				return array_map(
					function ( $val ) {
							return $val[0];
					},
					$calculators
				); },
			self::CALCULATORS
		);

		$systems = [
			'chaldean'    => 'Chaldean',
			'pythagorean' => 'Pythagorean',
		];

		$selected_system          = $options['system'] ? $options['system'] : 'pythagorean';
		$selected_calculator_name = $calculators[ $selected_system ][ $options['calculator'] ] ?? '';

		$input = $this->get_post_data();

		return $this->render(
			'form/numerology-birth-details',
			[
				'options'                  => $options + $this->get_options(),
				'datetime'                 => new \DateTimeImmutable( 'now', $this->get_timezone() ),
				'reference_year'           => ( new \DateTimeImmutable( 'now', $this->get_timezone() ) )->format( 'Y' ),
				'selected_calculator_name' => $selected_calculator_name,
				'systems'                  => $systems,
				'calculators'              => $calculators,
			] + $input
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
	public function process( $options = [] ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		$tz     = $this->get_timezone();
		$client = $this->get_api_client();

		[
			'date' => $date, 'system' => $system, 'calculator' => $calculator,
			'first_name' => $first_name, 'middle_name' => $middle_name, 'last_name' => $last_name,
			'reference_year' => $reference_year, 'vowel' => $vowel,
		] = $this->get_post_data();

		$date = new \DateTimeImmutable( $date, $tz );

		[ $calculator_name, $calculator_class, $param_type ] = self::CALCULATORS[ $system ][ $calculator ];
		$method = new $calculator_class($client);

		if ( self::DATE === $param_type ) {
			$result = $method->process( $date );
		} elseif ( self::NAME === $param_type ) {
			$result = $method->process( $first_name, $middle_name, $last_name );
		} elseif ( self::DATE_NAME === $param_type ) {
			$result = $method->process( $date, $first_name, $middle_name, $last_name );
		} elseif ( self::NAME_VOWEL === $param_type ) {
			$result = $method->process( $first_name, $middle_name, $last_name, $vowel );
		} elseif ( self::DATE_REF_YEAR === $param_type ) {
			$result = $method->process( $date, $reference_year );
		} else {
			throw new \Exception( 'Selected calculator not found' );
		}

		$data = [
			'calculator_name' => ucwords( str_replace( '-', ' ', $calculator ) ),
			'system'          => $system,
			'calculator'      => $calculator,
			'result'          => $result,
			'first_name'      => $first_name,
			'middle_name'     => $middle_name,
			'last_name'       => $last_name,
			'date'            => $date,
			'vowel'           => $vowel,
			'reference_year'  => $reference_year,
		];

		return $this->render(
			'result/numerology-result',
			[
				'system'     => $system,
				'calculator' => $calculator,
				'result'     => $result,
				'data'       => $data,
				'options'    => $this->get_options(),
			]
		);
	}

	/**
	 * Retrieve post data from form submission.
	 *
	 * @return string[]
	 */
	private function get_post_data() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$data = [
			'date'           => $this->get_post_input( 'date', '' ),
			'system'         => $this->get_post_input( 'system', 'pythagorean' ),
			'calculator'     => $this->get_post_input( 'calculator', 'life-path-number' ),
			'first_name'     => $this->get_post_input( 'first_name', '' ),
			'middle_name'    => $this->get_post_input( 'middle_name', '' ),
			'last_name'      => $this->get_post_input( 'last_name', '' ),
			'reference_year' => $this->get_post_input( 'reference_year', '' ),
			'vowel'          => $this->get_post_input( 'vowel', '' ),
		];
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		return $data;
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
			'system'     => '',
			'calculator' => '',
		];
	}
}
