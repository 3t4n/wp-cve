<?php
/**
 * Porutham result.
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
$alert_class = [
	'Excellent' => 'alert-success',
	'Good'      => 'alert-info',
	'Average'   => 'alert-warning',
	'Bad'       => 'alert-danger',
];
?>
<div class="pk-astrology-theme-<?php echo esc_attr( $options['theme'] ); ?>">
	<?php if ( ! empty( $result ) ) : ?>
		<h3><?php echo $translation_data['birth_details']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm">
			<tr class="pk-astrology-bg-secondary pk-astrology-text-center">
				<th>#</th>
				<th><?php echo $translation_data['details_of_girl']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $translation_data['details_of_boy']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
			</tr>
			<tr>
				<th><?php echo $translation_data['place_of_birth']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<td><?php echo $girl_dob->format( 'F d, Y' );  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
				<td><?php echo $boy_dob->format( 'F d, Y' );  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
			</tr>
			<?php foreach ( $result['girlInfo'] as $idx => $info ) : ?>
				<?php if ( in_array( $idx, [ 'nakshatra', 'rasi' ], true ) ) : ?>
					<?php foreach ( $info as $item => $item_vale ) : ?>
						<?php
						if ( 'id' === $item ) {
							continue;
						}
						?>
						<?php if ( 'lord' === $item ) : ?>
							<tr>
								<td><b><?php echo $translation_data[ $item ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></b></td>
								<td><?php echo "{$item_vale['vedicName']} ({$item_vale['name']})";  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
								<td><?php echo "{$result['boyInfo'][$idx][$item]['vedicName']} ({$result['boyInfo'][$idx][$item]['name']})";  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							</tr>
						<?php else : ?>
							<tr>
								<td><b><?php echo $translation_data[ $item ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></b></td>
								<td><?php echo $item_vale;  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
								<td><?php echo $result['boyInfo'][ $idx ][ $item ];  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</table>
		<div class="pk-astrology-alert pk-astrology-alert-info pk-astrology-text-center <?php echo $alert_class[ $result['message']['type'] ]; // phpcs:ignore WordPress.Security.EscapeOutput ?>">
			<?php echo $result['message']['description'];  // phpcs:ignore WordPress.Security.EscapeOutput ?> (<?php echo $result['totalPoints'];  // phpcs:ignore WordPress.Security.EscapeOutput ?> / 10)
		</div>
		<h3 class="pk-astrology-text-center"><?php echo $translation_data['place_of_birth']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm">
			<tr class="pk-astrology-bg-secondary pk-astrology-text-center">
				<th>#</th>
				<th><?php echo $translation_data['porutham']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<?php if ( 'advanced' === $result_type ) : ?>
					<th><?php echo $translation_data['status']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<?php endif; ?>
				<th class="pk-astrology-text-center"><?php echo $translation_data['obtained_point']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
			</tr>
			<?php $idx = 1; ?>
			<?php foreach ( $result['matches'] as $data ) : ?>
				<tr><td><?php echo $idx++; ?></td><td><?php echo $data['name'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<?php if ( 'advanced' === $result_type ) : ?>
						<td>
							<?php echo $translation_data['good'] === $data['poruthamStatus'] ? "<span class='pk-astrology-text-success'>{$translation_data['good']}</span>" : ( $translation_data['satisfactory'] === $data['poruthamStatus'] ? "<span class='pk-astrology-text-warning'>{$translation_data['satisfactory']}</span>" : "<span class='pk-astrology-text-danger'>{$translation_data['not_satisfactory']}</span>" ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</td>
						<td class="pk-astrology-text-center"><?php echo $data['points']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<?php else : ?>
						<td class="pk-astrology-text-center"><?php echo $data['hasPorutham'] ? 1 : 0; ?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			<tr class="pk-astrology-text-center">
				<th colspan="<?php echo 'advanced' === $result_type ? 3 : 2; ?>"><?php echo $translation_data['total_points']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
				<th><?php echo $result['totalPoints']; ?> / <?php echo $result['maximumPoints'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
			</tr>
		</table>

		<?php if ( 'advanced' === $result_type ) : ?>
			<h3><?php echo $translation_data['interpretation_of_10_porutham']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
			<?php foreach ( $result['matches'] as $key => $data ) : ?>
				<h3><?php echo $data['name'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
				<p><?php echo $data['description'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
