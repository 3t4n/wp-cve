<?php
if ( ! empty( $item_text ) ) { ?>
	<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $item_text_tag ); ?> itemprop="description" class="qodef-e-text"><?php echo esc_html( $item_text ); ?></<?php echo qi_addons_for_elementor_framework_sanitize_tags( $item_text_tag ); ?>>
<?php } ?>
