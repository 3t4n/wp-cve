<?php
/**
 * Template Style Ten for List
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

?>
<li class="absp-list-widget-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
	<div class="absp-list-left">
		<?php $this->list_icon( $item ); ?>
	</div>
	<div class="absp-list-right">
		<?php if ( $item['list_sub_title'] ) { ?>
			<span class="absp-sub-title"><?php absp_render_title( $item['list_sub_title'] ); ?></span>
		<?php } ?>
		<?php $this->list_title( $item ); ?>
	</div>
</li>

