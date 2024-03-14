<?php if ( ! empty( $item['item_text'] ) ) { ?>
	<p class="qodef-e-text"><?php echo qi_addons_for_elementor_framework_wp_kses_html( 'content', $item['item_text'] ); ?></p>
	<?php
}
