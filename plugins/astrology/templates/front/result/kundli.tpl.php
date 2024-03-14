<?php
/**
 * Kundli result.
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

			<?php if ( isset( $result['charts'] ) ) : ?>
				<h2><?php echo $translation_data['charts']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
				<div class="pk-astrology-kundli-charts-wrapper">
					<?php
					foreach ( [
						'lagna'   => 'Lagna',
						'navamsa' => 'Navamsa',
					] as $key => $value ) :
						?>
						<div class="pk-astrology-kundli-chart">
							<h3><?php echo $translation_data[ $key . '_chart' ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
							<?php echo $result['charts'][ $key ]; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php $nakshatra_details = $result['nakshatra_details']; ?>
			<table class="pk-astrology-table pk-astrology-table-responsive-sm">
				<tr class="pk-astrology-bg-secondary pk-astrology-text-center"><th colspan=2"><?php echo $translation_data['nakshatra_details']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th></tr>
				<?php foreach ( $result['nakshatra_details'] as $key => $kundli ) : ?>
					<?php $item = str_replace( '_', ' ', $key ); ?>
					<?php if ( in_array( $key, [ 'nakshatra', 'chandra_rasi', 'soorya_rasi' ], true ) ) : ?>
						<tr><th><?php echo $translation_data[ $key ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></th><td><?php echo $kundli['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
						<tr><th><?php echo $translation_data[ $key . '_lord' ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></th><td><?php echo "{$kundli['lord']['vedic_name']} ({$kundli['lord']['name']})"; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
					<?php elseif ( 'additional_info' === $key ) : ?>
						<tr class="pk-astrology-bg-secondary pk-astrology-text-center"><th colspan=2"><?php echo $translation_data['additional_info']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th></tr>
						<?php foreach ( $kundli as $index => $value ) : ?>
							<tr><th><?php echo $translation_data[ $index ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></th><td><?php echo $value; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr><th><?php echo $translation_data[ $key ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></th><td><?php echo $kundli['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
					<?php endif; ?>
				<?php endforeach; ?>
			</table>

			<h3 class="text-black"><?php echo $translation_data['yoga_details']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
			<?php foreach ( $result['yoga_details'] as $data ) : ?>
				<h3><?php echo ( $data['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
				<p>
				<?php
				echo $data['description']; // phpcs:ignore WordPress.Security.EscapeOutput
				?>
				</p>
				<?php if ( isset( $data['yogaList'] ) ) : ?>
					<?php foreach ( $data['yogaList'] as $yogas ) : ?>
						<?php if ( $yogas['hasYoga'] ) : ?>
						<b><?php echo $yogas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></b>
						<p><?php echo $yogas['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<div class="pk-astrology-alert pk-astrology-text-center  <?php echo $result['mangal_dosha']['has_dosha'] ? 'pk-astrology-alert-danger' : 'pk-astrology-alert-success'; ?>" >
				<?php echo $result['mangal_dosha']['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
			<?php if ( isset( $result['dasha_periods'] ) ) : ?>
				<?php if ( $result['mangal_dosha']['has_exception'] ) : ?>
					<h3><?php echo $translation_data['exceptions']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
					<ul>
						<?php foreach ( $result['mangal_dosha']['exceptions'] as $exceptions ) : ?>
							<li><?php echo $exceptions; // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="pk-astrology-dasha-periods">
				<?php foreach ( $result['dasha_periods'] as $mahadashas ) : ?>
					<h3><?php echo $translation_data['anthardashas_in']; // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo $mahadashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo $translation_data['mahadasha']; // phpcs:ignore WordPress.Security.EscapeOutput ?> </h3>
					<div class="pk-astrology-row">
					<?php foreach ( $mahadashas['antardasha'] as $anthardashas ) : ?>
						<table class="pk-astrology-table pk-astrology-col-12 pk-astrology-table-responsive-sm">
							<tr>
								<th><?php echo $translation_data['ad']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
								<th><?php echo $translation_data['pd']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
								<th><?php echo $translation_data['start']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
								<th><?php echo $translation_data['end']; // phpcs:ignore WordPress.Security.EscapeOutput ?></th>
							</tr>
						<?php foreach ( $anthardashas['pratyantardasha'] as $pratyantardashas ) : ?>
						<tr>
							<td><?php echo $anthardashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							<td><?php echo $pratyantardashas['name']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							<td><?php echo $pratyantardashas['start']->format( 'd-m-Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
							<td><?php echo $pratyantardashas['end']->format( 'd-m-Y' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
						</tr>
						<?php endforeach; ?>
						</table>
					<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
				<p class="pk-astrology-text-small pk-astrology-text-right pk-astrology-text-danger"><span class="pk-astrology-text-danger">**</span> <?php echo $translation_data['anthardashas_and_pratyantar_dasha']; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				</div>
				<?php
			endif;
			endif;
		?>
	</div>
<?php
