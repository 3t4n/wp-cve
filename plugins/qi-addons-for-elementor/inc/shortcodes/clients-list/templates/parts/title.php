<?php if ( ! empty( $client_title ) ) : ?>
	<?php echo '<' . qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-e-title">
	<?php if ( ! empty( $client_link['url'] ) ) { ?>
		<a href="<?php echo esc_url( $client_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $client_link ) ); ?>>
	<?php } ?>
		<?php echo esc_html( $client_title ); ?>
	<?php if ( ! empty( $client_link['url'] ) ) { ?>
		</a>
	<?php } ?>
	<?php echo '</' . qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
<?php endif; ?>
