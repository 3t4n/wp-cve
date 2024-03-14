<?php
/**
 * Numerology number result.
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

<div class="alert alert-info text-center">
	<h3 class="my-2">
		<?php echo $name; // phpcs:ignore WordPress.Security.EscapeOutput ?> :
		<?php echo $number ?? 'No Number';  // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</h3>
</div>
<?php if ( isset( $age ) ) : ?>
	<p>Age: <?php echo $age; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
<?php endif; ?>
<p><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
