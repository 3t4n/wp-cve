<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<ul class="qodef-tabs-vertical-navigation">
		<?php
		foreach ( $items as $item ) {
			$item['title_tag'] = $title_tag;
			qi_addons_for_elementor_template_part( 'shortcodes/tabs-vertical', 'variations/' . $layout . '/templates/child-title', '', $item );
		}
		?>
	</ul>
	<?php
	foreach ( $items as $item ) {
		qi_addons_for_elementor_template_part( 'shortcodes/tabs-vertical', 'variations/' . $layout . '/templates/child-content', '', $item );
	}
	?>
</div>
