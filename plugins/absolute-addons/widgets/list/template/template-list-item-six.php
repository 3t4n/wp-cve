<?php
/**
 * Template Style Six for List
 *
 * @package AbsoluteAddons

 */

defined( 'ABSPATH' ) || exit;

?>

<li class="absp-list-widget-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
	<?php
	$this->list_icon( $item );

	if ( ! empty( $item['list_description'] ) ) { ?>
	<div class="content">
		<p class="list-content"><?php absp_render_content_no_pe( $item['list_description'] ); ?></p>
	</div>
	<?php } ?>
</li>



