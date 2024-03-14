<?php
/**
 * Template Style Fifteen for List
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

?>
<li class="absp-list-widget-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
	<div class="absp-list-image">
		<?php if ( ! empty( $item['list_image'] ) ) { ?>
			<img src="<?php echo esc_url( $item['list_image']['url'] ); ?>" alt="<?php echo esc_attr( $item['list_title'] ); ?>"/>
		<?php } ?>
	</div>
	<div class="absp-list-right-content">
		<?php if ( ! empty( $item['list_sub_title'] ) ) { ?>
			<span class="absp-sub-title"><?php absp_render_title( $item['list_sub_title'] ); ?></span>
		<?php }
		if ( ! empty( $item['list_title'] ) ) {
			$this->list_title( $item );
		}
		if ( ! empty( $item['list_description'] ) ) { ?>
			<div <?php $this->print_render_attribute_string( 'list_description' ); ?>  class="content">
				<p class="list-content"><?php absp_render_content_no_pe( $item['list_description'] ); ?></p>
			</div>
		<?php } ?>
	</div>
</li>
