<?php
/**
 * Panchang result.
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
		<h2 class="pk-astrology-text-center"><?php echo $translation_data[ $title ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
		<div class="pk-astrology-panchang-details">
			<?php foreach ( $result['basic_info'] as $key => $data ) : ?>

				<?php if ( in_array( $key, [ 'nakshatra', 'tithi', 'karana', 'yoga' ], true ) ) : ?>
					<hr>
					<span class="pk-astrology-block"><b><?php echo $translation_data[ $key ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></b></span>
					<?php foreach ( $data as $idx => $value ) : ?>
						<span class="pk-astrology-block"><?php echo $value['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?>

							<?php if ( 'nakshatra' === $key ) : ?>
								(Lord: <?php echo $value['nakshatra_lord']; // phpcs:ignore WordPress.Security.EscapeOutput ?>)
							<?php endif; ?>
							: <?php echo $value['start']->format( 'h:i A' ) . ' - ' . $value['end']->format( 'h:i A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<?php endforeach; ?>

				<?php elseif ( 'vaara' === $key ) : ?>
					<span class="pk-astrology-block"><b><?php echo $translation_data[ $key ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></b> : <?php echo $data; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<?php else : ?>
					<span class="pk-astrology-block"><b><?php echo $translation_data[ $key ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></b> : <?php echo $data->format( 'h:i A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<?php endif; ?>
			<?php endforeach ?>

			<?php if ( 'advanced' === $result_type ) : ?>
				<hr>
				<table class="pk-astrology-table pk-astrology-table-responsive-sm">
					<tr class="pk-astrology-alert-success pk-astrology-text-center"><td colspan="2"><?php echo $translation_data['auspicious_timing']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
					<?php foreach ( $result['auspicious_period'] as $muhurat_name => $muhurat_details ) : ?>
						<tr>
							<td><?php echo ucwords( $muhurat_name ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td>
								<?php foreach ( $muhurat_details as $idx => $value ) : ?>
									<?php echo $value['start']->format( 'h:i A' ); ?> - <?php echo $value['end']->format( 'h:i A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><br>

								<?php endforeach; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					<tr class="pk-astrology-alert-danger pk-astrology-text-center"><td colspan="2"><?php echo $translation_data['inauspicious_timing']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
					<?php foreach ( $result['inauspicious_period'] as $muhurat_name => $muhurat_details ) : ?>
						<tr>
							<td><?php echo ucwords( $muhurat_name ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td>
								<?php foreach ( $muhurat_details as $idx => $value ) : ?>
									<?php echo $value['start']->format( 'h:i A' ); ?> - <?php echo $value['end']->format( 'h:i A' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><br>
								<?php endforeach; ?>
							</td>
						</tr>
					<?php endforeach; ?>

				</table>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
