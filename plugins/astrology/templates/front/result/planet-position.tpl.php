<?php
/**
 * Planet Position result.
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
		<h3 class="pk-astrology-text-center"><?php echo $translation_data['planet_positions']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm">
			<thead class="pk-astrology-bg-secondary">
				<tr>
				<th><?php echo $translation_data['planets']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['degree']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['rasi_name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['rasi_lord']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				</tr>
			</thead>
			<?php foreach ( $result as $planet ) : ?>
				<tr>
					<td><?php echo $planet['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<td><?php echo $planet['position']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<td><?php echo $planet['degree']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<td><?php echo $planet['rasi']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<td><?php echo $planet['rasiLord'] . ' (' . $planet['rasiLordEn'] . ') '; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>
