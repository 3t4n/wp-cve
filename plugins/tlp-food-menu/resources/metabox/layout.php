<?php
/**
 * Shortcode Metabox: Layout.
 *
 * @package RT_FoodMenu
 */

use RT\FoodMenu\Helpers\Fns;
use RT\FoodMenu\Helpers\Options;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

Fns::print_html( Fns::rtFieldGenerator( Options::scLayoutMetaFields() ), true );
?>

<div class="rt-responsive-column">
	<?php
	Fns::print_html( Fns::rtFieldGenerator( Options::scResponsiveMetaFields() ), true );
	?>
</div>

<?php
do_action( 'fmp_sc_meta_after_columns' );
?>

<div class="rt-field-wrapper rt-field-group" id="rtfm_pagination">
	<div class="rt-label">Pagination Settings</div>
	<div class="rt-field">
		<?php
		Fns::print_html( Fns::rtFieldGenerator( Options::scPaginationFields() ), true );
		?>
	</div>
</div>
<div class="rt-field-wrapper rt-field-group" id="rtfm_category_title">
	<div class="rt-label">Category Title Settings</div>
	<div class="rt-field">
		<?php
		Fns::print_html( Fns::rtFieldGenerator( Options::scCategoryTitleFields() ), true );
		?>
	</div>
</div>
<div class="rt-field-wrapper rt-field-group">
	<div class="rt-label">Image Settings</div>
	<div class="rt-field">
		<?php
		Fns::print_html( Fns::rtFieldGenerator( Options::scImageMetaFields() ), true );
		?>
	</div>
</div>
<div class="rt-field-wrapper rt-field-group">
	<div class="rt-label">Excerpt Settings</div>
	<div class="rt-field">
		<?php
		Fns::print_html( Fns::rtFieldGenerator( Options::scExcerptMetaFields() ), true );
		?>
	</div>
</div>
<div class="rt-field-wrapper rt-field-group">
	<div class="rt-label">Detail Page</div>
	<div class="rt-field">
		<?php
		Fns::print_html( Fns::rtFieldGenerator( Options::scDetailsMetaFields() ), true );
		?>
	</div>
</div>

<?php
do_action( 'fmp_sc_meta_after_details' );
?>
