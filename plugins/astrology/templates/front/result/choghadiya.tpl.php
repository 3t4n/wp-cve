<?php
/**
 * Choghadiya result.
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
<div class="pk-astrology-row pk-astrology-theme-<?php echo esc_attr( $options['theme'] ); ?>">
	<?php foreach ( $result as $type => $choghadiya ) : ?>
		<div class="pk-astrology-col-12">
			<table class="pk-astrology-table pk-astrology-table-responsive-sm">
				<tr class="pk-astrology-bg-secondary">
					<th colspan="4" class="pk-astrology-text-center"><?php echo $type ? $translation_data['day_choghadiya'] : $translation_data['night_choghadiya']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				</tr>
				<tr>
					<th><?php echo $translation_data['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['type']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['start']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
					<th><?php echo $translation_data['end']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				</tr>

				<?php foreach ( $choghadiya as $data ) : ?>
					<tr class="<?php echo $translation_data['good'] === $data['type'] ? 'pk-astrology-table-warning' : ( $translation_data['inauspicious'] === $data['type'] ? 'pk-astrology-table-danger' : 'pk-astrology-table-success' ); ?>">
						<td><?php echo $data['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?><br><i><?php echo $data['vela'] ? $data['vela'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?></i></td>
						<td><?php echo $data['type']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $data['start']->format( 'h:i:A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						<td><?php echo $data['end']->format( 'h:i:A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	<?php endforeach; ?>
</div>
