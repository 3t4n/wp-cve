<?php
/**
 * Papasamyam Check result.
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
?>
<div class="pk-astrology-theme-<?php echo esc_attr( $options['theme'] ); ?>">
	<?php if ( ! empty( $result ) ) : ?>
		<?php $ar_papa_planets = [ 'Mars', 'Saturn', 'Sun', 'Rahu' ]; ?>
		<?php $ar_papa_from_planets = [ 'Ascendant', 'Moon', 'Venus' ]; ?>
		<h2 class="pk-astrology-text-center"><?php echo $translation_data['papasamyam_details']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
		<h3><?php echo $translation_data['girl_papa_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm">
			<thead class="pk-astrology-bg-secondary">
				<tr>
					<th rowspan="2"><?php echo $translation_data['papa_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th colspan="2"><?php echo $translation_data['from_ascendant']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th colspan="2"><?php echo $translation_data['from_moon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th colspan="2"><?php echo $translation_data['from_venus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				</tr>
				<tr>
					<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				</tr>
			</thead>
			<?php foreach ( $ar_papa_planets as $papa_planet => $papa_planet_name ) : ?>
				<tr><th><?php echo $papa_planet_name; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<?php foreach ( $ar_papa_from_planets as $from_planet => $from_planet_name ) : ?>
						<td><?php echo $result['girlPapasamyam']['papaPlanet'][ $from_planet ]['planetDosha'][ $papa_planet ]['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $result['girlPapasamyam']['papaPlanet'][ $from_planet ]['planetDosha'][ $papa_planet ]['hasDosha'] ? 1 : 0; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>

			<tr><th colspan="7" class="pk-astrology-text-center"><?php echo $translation_data['total_papa_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?> : <?php echo $result['girlPapasamyam']['total_point']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th> </tr>
		</table>
		<h3><?php echo $translation_data['boy_papa_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm">
			<thead class="pk-astrology-bg-secondary">
				<tr>
					<th rowspan="2"><?php echo $translation_data['papa_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th colspan="2"><?php echo $translation_data['from_ascendant']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th colspan="2"><?php echo $translation_data['from_moon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th colspan="2"><?php echo $translation_data['from_venus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				</tr>
				<tr>
					<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				</tr>
			</thead>
			<?php foreach ( $ar_papa_planets as $papa_planet => $papa_planet_name ) : ?>
				<tr><th><?php echo $papa_planet_name; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<?php foreach ( $ar_papa_from_planets as $from_planet => $from_planet_name ) : ?>
						<td><?php echo $result['boyPapasamyam']['papaPlanet'][ $from_planet ]['planetDosha'][ $papa_planet ]['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $result['boyPapasamyam']['papaPlanet'][ $from_planet ]['planetDosha'][ $papa_planet ]['hasDosha'] ? 1 : 0; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
			<tr><th colspan="7" class="pk-astrology-text-center"><?php echo $translation_data['total_papa_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?> : <?php echo $result['boyPapasamyam']['total_point']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th> </tr>
		</table>
		<div class="pk-astrology-alert  pk-astrology-text-center
			<?php
			echo 'Excellent' === $result['message']['type'] ? 'pk-astrology-alert-success' :
			( 'Satisfactory' === $result['message']['type'] ? 'pk-astrology-alert-warning' : 'pk-astrology-alert-danger' )
			?>
			" role="alert">
			<?php echo $translation_data['papasamyam_for_this_couple_is']; // phpcs:ignore WordPress.Security.EscapeOutput ?> <b><?php echo $result['message']['type']; // phpcs:ignore WordPress.Security.EscapeOutput ?></b><br>
			<?php echo $result['message']['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	<?php endif; ?>
</div>
