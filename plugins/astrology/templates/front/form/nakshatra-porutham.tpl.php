<?php
/**
 * NakshatraPorutham input form template.
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

	<div class="pk-astrology-row">
		<div class="pk-astrology-col-12 pk-astrology-col-md-6">
			<legend class="pk-astrology-form-label"><?php echo $translation_data['enter_girl_detail']; ?></legend>
			<div class="pk-astrology-form-group pk-astrology-row">
				<label class="pk-astrology-col-sm-3 pk-astrology-col-md-6 pk-astrology-form-label"><?php echo $translation_data['girl_nakshatra']; ?></label>
				<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
					<select name="girl_nakshatra" class="pk-astrology-form-control">
						<?php foreach ( $translation_data['nakshatra_name_list'] as $nakshatra_id => $nakshatra ) : ?>
							<option value="<?php echo $nakshatra_id; ?>" <?php echo $nakshatra_id === $girl_nakshatra ? 'selected' : ''; ?>><?php echo $nakshatra; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="pk-astrology-form-group pk-astrology-row">
				<label class="pk-astrology-col-sm-3 pk-astrology-col-md-6 pk-astrology-form-label"><?php echo $translation_data['girl_nakshatra_pada']; ?></label>
				<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
					<select name="girl_nakshatra_pada" class="pk-astrology-form-control">
						<?php for ( $idx = 1; $idx <= 4; $idx++ ) : ?>
							<option value="<?php echo $idx; ?>" <?php echo $idx === $girl_nakshatra_pada ? 'selected' : ''; ?>><?php echo $idx; ?></option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
		</div>
		<div class="pk-astrology-col-12 pk-astrology-col-md-6">
			<legend class="pk-astrology-form-label"><?php echo $translation_data['enter_boy_detail']; ?></legend>
			<div class="pk-astrology-form-group pk-astrology-row">
				<label class="pk-astrology-col-sm-3 pk-astrology-col-md-6 pk-astrology-form-label"><?php echo $translation_data['boy_nakshatra']; ?></label>
				<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
					<select name="boy_nakshatra" class="pk-astrology-form-control">
						<?php foreach ( $nakshatra_list as $nakshatra_id => $nakshatra ) : ?>
							<option value="<?php echo $nakshatra_id; ?>" <?php echo $nakshatra_id === $boy_nakshatra ? 'selected' : ''; ?>><?php echo $nakshatra; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="pk-astrology-form-group pk-astrology-row">
				<label class="pk-astrology-col-sm-3 pk-astrology-col-md-6 pk-astrology-form-label"><?php echo $translation_data['boy_nakshatra_pada']; ?></label>
				<div class="pk-astrology-col-sm-9 pk-astrology-col-md-6">
					<select name="boy_nakshatra_pada" class="pk-astrology-form-control">
						<?php for ( $idx = 1; $idx <= 4; $idx++ ) : ?>
							<option value="<?php echo $idx; ?>" <?php echo $idx === $boy_nakshatra_pada ? 'selected' : ''; ?>><?php echo $idx; ?></option>
						<?php endfor; ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<?php $this->render( __DIR__ . '/result-type-option.tpl.php', [ 'result_type' => $options['result_type'] ] ); ?>

	<?php if ( $report_language ) : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label" for="select-lang"><?php echo $translation_data['language']; ?>: </label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8 ">
				<select name="lang" id="select-lang">
					<?php foreach ( $report_language as $language ) : ?>
						<option value=<?php echo $language; ?> <?php echo $selected_lang === $language ? 'selected' : ''; ?>><?php echo $translation_data[ $language ]; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	<?php endif; ?>

	<div class="pk-astrology-text-right">
		<button type="submit" class="pk-astrology-btn"><?php echo $translation_data['get_result']; ?></button>
		<input type="hidden" name="submit" value="1">
	</div>
</form>
<?php echo $options['attribution'] ? '<div class="pk-astrology-text-right"><em>Powered by <a href="https://www.prokerala.com/">Prokerala.com</a></em></div>' : ''; ?>
