<?php
if ( ! empty( $item_title ) ) { ?>
	<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $item_title_tag ); ?> class="qodef-e-title">
		<?php echo esc_html( $item_title ); ?>
	</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $item_title_tag ); ?>>
<?php } ?>
