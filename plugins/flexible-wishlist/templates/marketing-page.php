<?php
/**
@var WPDesk\FlexibleSubscriptions\Vendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes $boxes
 */

defined( 'ABSPATH' ) || exit;
?>
<style>
#marketing-page-wrapper {
	max-width: 1100px;
}
</style>
<div class="wrap">
	<div id="marketing-page-wrapper">
		<?php echo wp_kses_post( $boxes->get_boxes()->get_all() ); ?>
	</div>
</div>
