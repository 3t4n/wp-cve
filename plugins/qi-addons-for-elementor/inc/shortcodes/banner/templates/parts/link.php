<?php if ( ! empty( $link_url['url'] ) ) { ?>
	<a itemprop="url" href="<?php echo esc_url( $link_url['url'] ); ?>" class="qodef-m-banner-link" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $link_url ) ); ?>></a>
<?php }
?>
