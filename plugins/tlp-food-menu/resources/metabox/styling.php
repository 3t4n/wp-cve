<?php
/**
 * Shortcode Metabox: Styling.
 *
 * @package RT_FoodMenu
 */

use RT\FoodMenu\Helpers\Fns;
use RT\FoodMenu\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
?>

<div class="rt-field-wrapper rt-field-group">
	<div class="rt-label"><?php esc_html_e( 'General Styles', 'tlp-food-menu' ); ?></div>
	<div class="rt-field">
		<div class="rt-multiple-field-group">
			<?php
			Fns::print_html( Fns::rtFieldGenerator( Options::scStyleGeneralFields() ), true );
			?>
		</div>
	</div>
</div>

<div class="rt-field-wrapper rt-field-group">
	<div class="rt-label"><?php esc_html_e( 'Content Styles', 'tlp-food-menu' ); ?></div>
	<div class="rt-field">
		<div class="rt-multiple-field-group">
			<?php
			Fns::print_html( Fns::rtFieldGenerator( Options::scStyleContentFields() ), true );
			?>
		</div>
	</div>
</div>

<div class="rt-field-wrapper rt-field-group">
	<div class="rt-label"><?php esc_html_e( 'Button Styles', 'tlp-food-menu' ); ?></div>
	<div class="rt-field">
		<div class="rt-multiple-field-group">
			<div class="group-1">
				<?php
				Fns::print_html( Fns::rtFieldGenerator( Options::scStyleButtonBgColorFields() ), true );
				?>
			</div>
			<div class="group-2">
				<?php
				Fns::print_html( Fns::rtFieldGenerator( Options::scStyleButtonColorFields() ), true );
				?>
			</div>
		</div>
	</div>
</div>

<div class="rt-field-wrapper rt-field-group">
	<div class="rt-label"><?php esc_html_e( 'Extra Styles', 'tlp-food-menu' ); ?></div>
	<div class="rt-field">
		<div class="rt-multiple-field-group">
			<?php
			Fns::print_html( Fns::rtFieldGenerator( Options::scStyleExtraFields() ), true );
			?>
		</div>
	</div>
</div>
