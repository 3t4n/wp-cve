<?php
/**
 * Mangal Dosha result.
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
		<h2 class="pk-astrology-text-center"><?php echo $translation_data['mangal_dosha_result']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h2>
		<div class="pk-astrology-alert pk-astrology-text-center <?php echo $result['has_mangal_dosha'] ? 'pk-astrology-alert-danger' : 'pk-astrology-alert-success'; ?>" >
			<?php echo $result['description']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<?php if ( 'advanced' === $result_type ) : ?>
			<?php if ( $result['has_exception'] ) : ?>
				<h3><?php echo $translation_data['exceptions']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
				<ul>
					<?php foreach ( $result['exceptions'] as $exceptions ) : ?>
						<li><?php echo $exceptions; // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( $result['remedies'] ) : ?>
				<h3><?php echo $translation_data['remedies']; // phpcs:ignore WordPress.Security.EscapeOutput ?></h3>
				<ul>
					<?php foreach ( $result['remedies'] as $remedies ) : ?>
						<li><?php echo $remedies; // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
					<?php endforeach; ?>
				</ul>

			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
