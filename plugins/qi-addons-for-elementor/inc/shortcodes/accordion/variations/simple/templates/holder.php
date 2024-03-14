<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php
	foreach ( $items as $item ) {
		$item['title_tag']  = $title_tag;
		$item['icon_open']  = $icon_open;
		$item['icon_close'] = $icon_close;

		qi_addons_for_elementor_template_part( 'shortcodes/accordion', 'variations/' . $layout . '/templates/child', '', $item );
	}
	?>
</div>
