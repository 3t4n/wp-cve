<?php
/**
 * Auspicious Period result.
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

// phpcs:disable VariableAnalysis, WordPress.WP.GlobalVariablesOverride.Prohibited

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$number_result_object       = null;
$number_result              = null;
$name_chart                 = null;
$multiple_age_number_result = false;
$multiple_number_result     = false;

if ( 'pythagorean' === $system ) {
	if ( 'life-path-number' === $calculator ) {
		$number_result_object = $result->getLifePathNumber();
	} elseif ( 'personality-number' === $calculator ) {
		$number_result_object = $result->getPersonalityNumber();
	} elseif ( 'personal-year-number' === $calculator ) {
		$number_result_object = $result->getPersonalYearNumber();
	} elseif ( 'universal-month-number' === $calculator ) {
		$number_result_object = $result->getUniversalMonthNumber();
	} elseif ( 'personal-day-number' === $calculator ) {
		$number_result_object = $result->getPersonalDayNumber();
	} elseif ( 'personal-month-number' === $calculator ) {
		$number_result_object = $result->getPersonalMonthNumber();
	} elseif ( 'birthday-number' === $calculator ) {
		$number_result_object = $result->getBirthdayNumber();
	} elseif ( 'birth-month-number' === $calculator ) {
		$number_result_object = $result->getBirthMonthNumber();
	} elseif ( 'universal-day-number' === $calculator ) {
		$number_result_object = $result->getUniversalDayNumber();
	} elseif ( 'universal-year-number' === $calculator ) {
		$number_result_object = $result->getUniversalYearNumber();
	} elseif ( 'inner-dream-number' === $calculator ) {
		$number_result_object = $result->getInnerDreamNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'attainment-number' === $calculator ) {
		$number_result_object = $result->getAttainmentNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'expression-number' === $calculator ) {
		$number_result_object = $result->getExpressionNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'soul-urge-number' === $calculator ) {
		$number_result_object = $result->getSoulUrgeNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'balance-number' === $calculator ) {
		$number_result_object = $result->getBalanceNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'destiny-number' === $calculator ) {
		$number_result_object = $result->getDestinyNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'capstone-number' === $calculator ) {
		$number_result_object = $result->getCapstoneNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'cornerstone-number' === $calculator ) {
		$number_result_object = $result->getCornerstoneNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'subconscious-self-number' === $calculator ) {
		$number_result_object = $result->getSubconsciousSelfNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'maturity-number' === $calculator ) {
		$number_result_object = $result->getMaturityNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'hidden-passion-number' === $calculator ) {
		$number_result_object = $result->getHiddenPassionNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'rational-thought-number' === $calculator ) {
		$number_result_object = $result->getRationalThoughtNumber();
		$name_chart           = $result->getNameChart();
	} elseif ( 'challenge-number' === $calculator ) {
		$name                       = $result->getChallengeNumber()->getName();
		$multiple_age_numbers       = $result->getChallengeNumber()->getChallenges();
		$multiple_age_number_result = true;
	} elseif ( 'pinnacle-number' === $calculator ) {
		$name                       = $result->getPinnacleNumber()->getName();
		$multiple_age_numbers       = $result->getPinnacleNumber()->getPinnacles();
		$multiple_age_number_result = true;
	} elseif ( 'karmic-debt-number' === $calculator ) {
		$name                   = $result->getKarmicDebtNumber()->getName();
		$multiple_numbers       = $result->getKarmicDebtNumber()->getDebts();
		$multiple_number_result = true;
	} elseif ( 'bridge-number' === $calculator ) {
		$name                   = $result->getBridgeNumber()->getName();
		$multiple_numbers       = $result->getBridgeNumber()->getDifferences();
		$multiple_number_result = true;
	}
} elseif ( 'birth-number' === $calculator ) {
		$number_result_object = $result->getBirthNumber();
} elseif ( 'life-path-number' === $calculator ) {
	$number_result_object = $result->getLifePathNumber();
} elseif ( 'identity-initial-code-number' === $calculator ) {
	$number_result_object = $result->getIdentityInitialCodeNumber();
} elseif ( 'daily-name-number' === $calculator ) {
	$number_result_object = $result->getDailyNameNumber();
} elseif ( 'whole-name-number' === $calculator ) {
	$name                   = $result->getWholeNameNumber()->getName();
	$multiple_numbers       = $result->getWholeNameNumber()->getEnergies();
	$multiple_number_result = true;
}
?>

<?php if ( $number_result_object ) : ?>
	<?php
	$this->render(
		__DIR__ . '/numerology-number-result-only.tpl.php',
		[
			'name'        => $number_result_object->getName(),
			'number'      => $number_result_object->getNumber(),
			'description' => $number_result_object->getDescription(),
		]
	);
	?>
<?php endif; ?>

<?php if ( $multiple_age_number_result ) : ?>
	<h3><?php echo $name; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
	<?php foreach ( $multiple_age_numbers as $number ) : ?>
		<?php
		$this->render(
			__DIR__ . '/numerology-number-result-only.tpl.php',
			[
				'name'        => $number->getName(),
				'age'         => $number->getAge(),
				'number'      => $number->getNumber(),
				'description' => $number->getDescription(),
			]
		);
		?>
	<?php endforeach; ?>
<?php endif; ?>


<?php if ( $multiple_number_result ) : ?>
	<h3><?php echo $name; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
	<?php foreach ( $multiple_numbers as $number ) : ?>
		<?php
		$this->render(
			__DIR__ . '/numerology-number-result-only.tpl.php',
			[
				'name'        => $number->getName(),
				'number'      => $number->getNumber(),
				'description' => $number->getDescription(),
			]
		);
		?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if ( $name_chart ) : ?>
	<?php $this->render( __DIR__ . '/numerology-name-chart-only.tpl.php', [ 'name_chart' => $name_chart ] ); ?>
<?php endif; ?>
