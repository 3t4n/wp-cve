<?php
/**
 * Birth Details result.
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
	<table class="pk-astrology-table pk-astrology-table-responsive-sm">
		<tr class="pk-astrology-bg-secondary pk-astrology-text-center"><td colspan="2" class="pk-astrology-text-center"><?php echo $translation_data['nakshatra_details']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr><td><?php echo $translation_data['nakshatra']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $result['nakshatra']->getName(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr><td><?php echo $translation_data['nakshatra_pada']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $result['nakshatra']->getPada(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr><td><?php echo $translation_data['nakshatra_lord']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $result['nakshatra']->getLord(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr><td><?php echo $translation_data['chandra_rasi']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $result['chandra_rasi']->getName(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr><td><?php echo $translation_data['chandra_rasi_lord']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $result['chandra_rasi']->getLord() . '(' . $result['chandra_rasi']->getLord()->getVedicName() . ')'; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr><td><?php echo $translation_data['soorya_rasi']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $result['soorya_rasi']->getName(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr><td><?php echo $translation_data['soorya_rasi_lord']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $result['soorya_rasi']->getLord() . '(' . $result['soorya_rasi']->getLord()->getVedicName() . ')'; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr><td><?php echo $translation_data['zodiac']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $result['zodiac']->getName(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<tr class="pk-astrology-bg-secondary pk-astrology-text-center"><td colspan="2" class="pk-astrology-text-center"><?php echo $translation_data['additional_info']; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<?php foreach ( $result['additional_info'] as $key => $data ) : ?>
			<tr><td><?php echo $translation_data[ $key ]; // phpcs:ignore WordPress.Security.EscapeOutput ?></td><td><?php echo $data; // phpcs:ignore WordPress.Security.EscapeOutput ?></td></tr>
		<?php endforeach; ?>
	</table>
</div>
