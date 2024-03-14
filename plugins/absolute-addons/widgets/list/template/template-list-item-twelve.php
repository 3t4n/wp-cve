<?php
/**
 * Template Style Twelve for List
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

?>
<li class="absp-list-widget-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
	<?php
	$this->list_title( $item );
	if ( ! empty( $item['list_description2'] ) ) { ?>
		<div class="absp-list-content">
			<?php absp_render_content( $item['list_description2'] ); ?>
		</div>
	<?php } ?>
</li>
