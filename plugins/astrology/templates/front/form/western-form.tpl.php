<?php
/**
 * Common form inputs for horoscope reports.
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

// phpcs:disable VariableAnalysis

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div>
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Birth Date: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<input type='datetime-local' name="datetime" class="pk-astrology-form-control pk-astrology-form-control-lg" required="required" value="<?php echo $datetime->format( 'Y-m-d\TH:i' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left"></label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="checkbox" name="birth_time_unknown" id="birth_time_unknown" <?php echo isset( $birth_time_unknown ) && $birth_time_unknown ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="birth_time_unknown">Check if exact birth time is unknown</label>
			</div>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left ">Birth Place:</label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<input type='text' id="fin-location" name="location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Place of birth" value="" required>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="house-system">House System: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<select name="house_system" id="house-system">
				<option value="placidus" <?php echo 'placidus' === $house_system ? 'selected' : ''; ?>>Placidus</option>
				<option value="koch" <?php echo 'koch' === $house_system ? 'selected' : ''; ?>>Koch</option>
				<option value="whole-sign" <?php echo 'whole-sign' === $house_system ? 'selected' : ''; ?>>Whole Sign</option>
				<option value="equal-house" <?php echo 'equal-house' === $house_system ? 'selected' : ''; ?>>Equal House</option>
				<option value="m-house" <?php echo 'm-house' === $house_system ? 'selected' : ''; ?>>M House</option>
				<option value="porphyrius" <?php echo 'porphyrius' === $house_system ? 'selected' : ''; ?>>Porphyrius</option>
				<option value="regiomontanus" <?php echo 'regiomontanus' === $house_system ? 'selected' : ''; ?>>Regiomontanus</option>
				<option value="campanus" <?php echo 'campanus' === $house_system ? 'selected' : ''; ?>>Campanus</option>
			</select>
		</div>
	</div>
	<?php if ( isset( $transit_datetime ) ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Transit Date Time: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type='datetime-local' name="transit_datetime" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1" required="required" value="<?php echo $transit_datetime->format( 'Y-m-d\TH:i' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"/>
			</div>
		</div>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left ">Transit Location:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Transit Location" value="" data-location_input_prefix="current" required>
			</div>
		</div>
	<?php elseif ( isset( $progression_year ) ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Progression Year: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type="number" name="progression_year" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1" placeholder="Enter Progression Year" value="<?php echo $progression_year; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" required>
			</div>
		</div>

		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left ">Progressed Location:</label>
			<div class="col-sm-9 col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Progressed Location" data-location_input_prefix="current" value="" required>
			</div>
		</div>
	<?php elseif ( isset( $solar_return_year ) ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left">Solar Return Year: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type="number" name="solar_return_year" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1" placeholder="Enter Solar Return Year" value="<?php echo $solar_return_year; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" required>
			</div>
		</div>

		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-col-form-label pk-astrology-text-md-right pk-astrology-text-xs-left ">Transit Location:</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<input type='text' id="fin-current-location" name="current_location" autocomplete="off" class="pk-astrology-form-control pk-astrology-form-control-lg pk-astrology-rounded-1 prokerala-location-input" placeholder="Transit Location" value="" data-location_input_prefix="current_" required>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( null !== $aspect_filter ) : ?>
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter">Aspect Filter: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<select name="aspect_filter" id="aspect-filter">
				<option value="major" <?php echo 'major' === $aspect_filter ? 'selected' : ''; ?>>Show major aspects</option>
				<option value="all" <?php echo 'all' === $aspect_filter ? 'selected' : ''; ?>>Show all aspects</option>
				<option value="minor" <?php echo 'minor' === $aspect_filter ? 'selected' : ''; ?>>Show minor aspects only</option>
			</select>
		</div>
	</div>
	<?php endif; ?>


	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter">Orb: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="orb" id="orb1" value="default" <?php echo 'default' === $orb ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="orb1">Default</label>
			</div>
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="orb" id="orb2" value="exact" <?php echo 'exact' === $orb ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="orb2">Exact</label>
			</div>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row d-none" id="birth_time_rectification_tab">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 col-form-label" for="aspect-filter">Birth Time Rectification Chart: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
			<div class="pk-astrology-form-check form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="birth_time_rectification" id="birth_time_rectification1" value="flat-chart" <?php echo 'flat-chart' === $rectification_chart ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="birth_time_rectification1">Flat Chart</label>
			</div>
			<div class="pk-astrology-form-check pk-astrology-form-check-inline">
				<input class="pk-astrology-form-check-input" type="radio" name="birth_time_rectification" id="birth_time_rectification2" value="true-sunrise-chart" <?php echo 'true-sunrise-chart' === $rectification_chart ? 'checked' : ''; ?>>
				<label class="pk-astrology-form-check-label" for="birth_time_rectification2">True Sunrise Chart</label>
			</div>
		</div>
	</div>
</div>

<div id="form-hidden-fields"></div>


<script>
	(function () {
		const $birthTimeUnknownCheckbox = document.getElementById('birth_time_unknown');
		const $birthTimeRectificationTab = document.getElementById('birth_time_rectification_tab');
		if ($birthTimeUnknownCheckbox.checked) {
			$birthTimeRectificationTab.classList.remove('d-none');
		}
		$birthTimeUnknownCheckbox.addEventListener('click', (e) => {
			if(e.target.checked){
				$birthTimeRectificationTab.classList.remove('d-none');
			} else {
				if(!$birthTimeRectificationTab.classList.contains('d-none')){
					$birthTimeRectificationTab.classList.add('d-none');
				}
			}
		});
	}());
</script>
