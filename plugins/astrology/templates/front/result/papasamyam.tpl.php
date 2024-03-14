<?php
/**
 * Papasamyam result.
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
		<h2 class="pk-astrology-text-center"><?php echo $translation_data['papasamyam_details']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm">
			<thead>
			<tr class="pk-astrology-bg-secondary">
				<th rowspan="2"><?php echo $translation_data['papa_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th colspan="2"><?php echo $translation_data['from_ascendant']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th colspan="2"><?php echo $translation_data['from_moon']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th colspan="2"><?php echo $translation_data['from_venus']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
			</tr>
			<tr class="pk-astrology-bg-secondary">
				<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['papam']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th></tr>
			</thead>
			<?php $ar_papa_planets = [ 'Mars', 'Saturn', 'Sun', 'Rahu' ]; ?>
			<?php $ar_papa_from_planets = [ 'Ascendant', 'Moon', 'Venus' ]; ?>
			<?php foreach ( $ar_papa_planets as $papa_planet => $papa_planet_name ) : ?>
				<tr><th><?php echo $papa_planet_name; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<?php foreach ( $ar_papa_from_planets as $from_planet => $from_planet_name ) : ?>
						<td><?php echo $result['papaPlanet'][ $from_planet ]['planetDosha'][ $papa_planet ]['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $result['papaPlanet'][ $from_planet ]['planetDosha'][ $papa_planet ]['hasDosha'] ? 1 : 0; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
			<tr><th colspan="7" class="pk-astrology-text-center"><?php echo $translation_data['total_papa_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?> : <?php echo $result['total_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th> </tr>
		</table>
	<?php endif; ?>
</div>
