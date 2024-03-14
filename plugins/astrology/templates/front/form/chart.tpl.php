<?php
/**
 * Chart input form template.
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

	<?php $this->render( __DIR__ . '/horoscope-form.tpl.php' ); ?>

	<?php if ( ! empty( $options['chart_type'] ) ) : ?>
		<input type="hidden" name="chart_type" value="<?php echo $options['chart_type']; ?>" >
	<?php else : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Chart Type</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8">
				<select name="chart_type" class="pk-astrology-form-control">
					<?php foreach ( $chart_types as $chart ) : ?>
						<option value="<?php echo $chart; ?>" <?php echo $chart === $chart_type ? 'selected' : ''; ?>><?php echo ucwords( $chart ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $options['chart_style'] ) ) : ?>
		<input type="hidden" name="chart_style" value="<?php echo $options['chart_style']; ?>" >
	<?php else : ?>
		<div class="pk-astrology-form-group pk-astrology-row">
			<label class="pk-astrology-col-sm-3 pk-astrology-col-md-4 pk-astrology-form-label">Chart Style</label>
			<div class="pk-astrology-col-sm-9 pk-astrology-col-md-8">
				<select name="chart_style" class="pk-astrology-form-control">
					<option value="south-indian" <?php echo 'south-indian' === $chart_style ? 'selected' : ''; ?>>South Indian</option>
					<option value="north-indian" <?php echo 'north-indian' === $chart_style ? 'selected' : ''; ?>>North Indian</option>
					<option value="east-indian" <?php echo 'east-indian' === $chart_style ? 'selected' : ''; ?>>East Indian</option>
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
