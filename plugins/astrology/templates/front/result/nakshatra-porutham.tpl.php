<?php
/**
 * NakshatraPorutham result.
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
		<h3 class="pk-astrology-text-center">10 Poruthams and Your Compatibility</h3>
		<table class="pk-astrology-table pk-astrology-table-responsive-sm">
			<tr class="pk-astrology-bg-secondary">
				<th>#</th>
				<th>Porutham</th>
				<?php if ( 'advanced' === $result_type ) : ?>
					<th>Status</th>
				<?php endif; ?>
				<th class="pk-astrology-text-center">Obtained Point</th>
			</tr>
			<?php foreach ( $result['Matches'] as $idx => $data ) : ?>
				<tr><td><?php echo $idx + 1; ?></td><td><?php echo $data['name'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<?php if ( 'advanced' === $result_type ) : ?>
						<td>
							<?php
							echo 'Good' === $data['poruthamStatus'] ? '<span class="text-success">Good</span>' :
								( 'Bad' === $data['poruthamStatus'] ? '<span class="text-danger">Bad</span>' :
									'<span class="text-warning">Satisfactory</span>' )
							?>
									</td>
						<td class="pk-astrology-text-center"><?php echo $data['points'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
					<?php else : ?>
						<td class="pk-astrology-text-center"><?php echo $data['hasPorutham'] ? 1 : 0; ?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			<tr class="pk-astrology-text-center">
				<th colspan="<?php echo 'advanced' === $result_type ? 3 : 2; ?>">Total Points:</th>
				<th><?php echo $result['ObtainedPoint']; ?> / <?php echo $result['maximumPoint'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
			</tr>
		</table>

		<?php if ( 'advanced' === $result_type ) : ?>
			<h3>Interpretation of 10 porutham</h3>
			<?php foreach ( $result['Matches'] as $key => $data ) : ?>
				<h3><?php echo $data['name'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
				<p><?php echo $data['description'];  // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="pk-astrology-alert pk-astrology-text-center pk-astrology-alert-info">
			<?php echo $result['message']['description'];  // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>

	<?php endif; ?>
</div>
