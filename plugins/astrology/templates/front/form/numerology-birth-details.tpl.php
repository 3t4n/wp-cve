<?php
/**
 * Birth Details input form template.
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

// phpcs:disable VariableAnalysis, WordPress.Security.EscapeOutput.OutputNotEscaped

// Exit if accessed directly.
use Prokerala\WP\Astrology\Templating\Context;

/**
 * Render Context.
 *
 * @var Context $this
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form class="pk-astrology-theme-<?php echo $options['theme']; ?> pk-astrology-form" method="POST" <?php echo isset( $options['form_action'] ) ? " action=\"{$options['form_action']}\"" : ''; ?>>
	<?php if ( $selected_calculator_name ) : ?>
		<h3><?php echo $selected_calculator_name; ?></h3>
	<?php endif; ?>
	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">First Name: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='text' name="first_name" class="pk-astrology-form-control" required="required" value="<?php echo $first_name; ?>" placeholder="Enter first name"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Middle Name: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='text' name="middle_name" class="pk-astrology-form-control" value="<?php echo $middle_name; ?>" placeholder="Enter middle name"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Last Name: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='text' name="last_name" class="pk-astrology-form-control" required="required" value="<?php echo $last_name; ?>" placeholder="Enter last name"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Date of Birth: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='date' name="date" class="pk-astrology-form-control" required="required" value="<?php echo $datetime->format( 'Y-m-d' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Enter reference year: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<input type='number' name="reference_year" class="pk-astrology-form-control" required="required" value="<?php echo $reference_year; ?>" placeholder="Enter reference year"/>
		</div>
	</div>

	<div class="pk-astrology-form-group pk-astrology-row">
		<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Additional Vowel: </label>
		<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
			<label>
				<input type="checkbox" name="vowel" value="1" <?php echo $vowel ? 'checked' : ''; ?>/>
				Consider characters Y and W as vowels
			</label>
		</div>
	</div>

	<?php if ( $options['system'] ) : ?>
		<input type="hidden" name="system" value="<?php echo $options['system']; ?>">
	<?php else : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">System: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<?php foreach ( $systems as $key => $val ) : ?>
					<div class="pk-astrology-form-check-inline">
						<input class="pk-astrology-form-check-input" type="radio" name="system" id="system_<?php echo $key; ?>" value="<?php echo $key; ?>" <?php echo $key === $system ? 'checked' : ''; ?> onclick="resetNumerologyCalculators(this)">
						<label class="pk-astrology-form-check-label" for="system_<?php echo $key; ?>"><?php echo $val; ?></label>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $options['calculator'] ) : ?>
		<input type="hidden" name="calculator" value="<?php echo $options['calculator']; ?>">
	<?php else : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Calculator: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6 ">
				<select name="calculator" class="pk-astrology-form-control" required>
					<?php foreach ( $systems as $key => $val ) : ?>
						<optgroup label="<?php echo $val; ?>" <?php echo $system === $key ? '' : 'hidden'; ?>>
						<?php foreach ( $calculators[ $key ] as $k => $v ) : ?>
							<option value="<?php echo $k; ?>" <?php echo $calculator === $k ? 'selected' : ''; ?>><?php echo $v; ?></option>
						<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	<?php endif; ?>

	<div class="pk-astrology-text-right">
		<button type="submit" class="pk-astrology-btn">Get Result</button>
		<input type="hidden" name="submit" value="1">
	</div>

	<script>
		function resetNumerologyCalculators(el) {
			const form = el.form;
			let calculators = form.querySelector('[name="calculator"]');
			const system = el.value;
			Array.from(calculators.querySelectorAll('optgroup')).forEach(function (el) {
				if (el.label.toLowerCase() === system) {
					el.removeAttribute('hidden');
					el.offsetWidth;
					setTimeout(function() {
						el.firstElementChild.selected = true;
					}, 100)
					console.log(el.firstElementChild);
				} else {
					el.setAttribute('hidden', 'hidden');
				}
			});
			calculators.value = '';
		}
	</script>
</form>
<?php echo $options['attribution'] ? '<div class="pk-astrology-text-right"><em>Powered by <a href="https://www.prokerala.com/">Prokerala.com</a></em></div>' : ''; ?>
