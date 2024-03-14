<?php
/**
 * Page: Overview page
 *
 * @package EverAccouting
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap eaccounting ea-overview">
	<h1><?php esc_html_e( 'Overview', 'wp-ever-accounting' ); ?></h1>

	<div class="ea-clearfix">
		<div class="ea-row">
			<?php eaccounting_do_meta_boxes( 'ea-overview', 'top', null ); ?>
		</div>
	</div>

	<div class="ea-clearfix">
		<div class="ea-row">
			<?php eaccounting_do_meta_boxes( 'ea-overview', 'middle', null ); ?>
		</div>
	</div>

	<div class="ea-clearfix">
		<div class="ea-row">
			<?php eaccounting_do_meta_boxes( 'ea-overview', 'advanced', null ); ?>
		</div>
	</div>

</div>
