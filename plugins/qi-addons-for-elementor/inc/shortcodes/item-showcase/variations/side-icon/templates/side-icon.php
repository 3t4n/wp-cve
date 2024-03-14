<div class="qodef-m-item qodef-e">
	<div class="qodef-e-side-holder">
		<?php
		if ( 'icon' === $item['additional'] ) {
			qi_addons_for_elementor_template_part( 'shortcodes/item-showcase', 'templates/parts/icon', '', $params );
		} elseif ( 'number' === $item['additional'] ) {
			qi_addons_for_elementor_template_part( 'shortcodes/item-showcase', 'templates/parts/number', '', $params );
		}
		?>
	</div>
	<div class="qodef-e-content-holder">
		<?php qi_addons_for_elementor_template_part( 'shortcodes/item-showcase', 'templates/parts/title', '', $params ); ?>
		<?php qi_addons_for_elementor_template_part( 'shortcodes/item-showcase', 'templates/parts/text', '', $params ); ?>
	</div>
</div>
