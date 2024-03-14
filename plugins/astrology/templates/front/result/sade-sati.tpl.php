<?php
/**
 * SadeSati result.
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
		<div class="pk-astrology-alert pk-astrology-text-center pk-astrology-pad <?php echo ( ( $result['isInSadeSati'] ) ? 'pk-astrology-alert-danger' : 'pk-astrology-alert-success' ); ?>">
			<?php echo $result['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<?php if ( 'advanced' === $result_type ) : ?>
			<h3>The Detailed sade sati report is as follows</h3>
			<table class="pk-astrology-table pk-astrology-table-responsive-sm">
				<thead class="pk-astrology-bg-secondary">
				<tr>
					<th>Sade Sati Phase</th>
					<th>Start Time</th>
					<th>End Time</th>
				</tr>
				</thead>
				<?php $today = strtotime( gmdate( 'Y-m-d' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php foreach ( $result['transits'] as $transit ) : ?>
					<tr class="<?php echo ( $today >= strtotime( $transit['start']->format( 'Y-m-d' ) ) && $today <= strtotime( $transit['end']->format( 'Y-m-d' ) ) ) ? 'pk-astrology-table-danger' : ''; ?>">
						<td><?php echo $transit['phase']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $transit['start']->format( 'F d, Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $transit['end']->format( 'F d, Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	<?php endif; ?>
</div>
