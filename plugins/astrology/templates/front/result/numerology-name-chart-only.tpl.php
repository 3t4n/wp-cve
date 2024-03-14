<?php
/**
 * Numerology name chart.
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

<?php if ( $name_chart->getFirstName() ) : ?>
	<p class="text-center text-dark">First Name</p>
	<table class="table table-bordered">
		<tr>
		<?php foreach ( $name_chart->getFirstName() as $name_character_value ) : ?>
			<td><?php echo $name_character_value->getCharacter(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
		<?php endforeach; ?>
		</tr>
		<tr>
		<?php foreach ( $name_chart->getFirstName() as $name_character_value ) : ?>
			<td><?php echo $name_character_value->getNumber(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
		<?php endforeach; ?>
		</tr>
	</table>
<?php endif; ?>
<?php if ( $name_chart->getMiddleName() ) : ?>
	<p class="text-center text-dark">Middle Name</p>
	<table class="table table-bordered">
		<tr>
			<?php foreach ( $name_chart->getMiddleName() as $name_character_value ) : ?>
				<td><?php echo $name_character_value->getCharacter(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
			<?php endforeach; ?>
		</tr>
		<tr>
			<?php foreach ( $name_chart->getMiddleName() as $name_character_value ) : ?>
				<td><?php echo $name_character_value->getNumber(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
			<?php endforeach; ?>
		</tr>
	</table>
<?php endif; ?>
<?php if ( $name_chart->getLastName() ) : ?>
	<p class="text-center text-dark">Last Name</p>
	<table class="table table-bordered">
		<tr>
			<?php foreach ( $name_chart->getLastName() as $name_character_value ) : ?>
				<td><?php echo $name_character_value->getCharacter(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
			<?php endforeach; ?>
		</tr>
		<tr>
			<?php foreach ( $name_chart->getLastName() as $name_character_value ) : ?>
				<td><?php echo $name_character_value->getNumber(); // phpcs:ignore WordPress.Security.EscapeOutput ?></td>
			<?php endforeach; ?>
		</tr>
	</table>
<?php endif; ?>
