<?php if ( ! empty( $link['url'] ) ) : ?>
	<a itemprop="url" href="<?php echo esc_url( $link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $link ) ); ?>>
<?php endif; ?>
		<div class="qodef-m-icon-holder">
			<?php \Elementor\Icons_Manager::render_icon( $icon_type, array( 'aria-hidden' => 'true' ) ); ?>
		</div>
<?php if ( ! empty( $link['url'] ) ) : ?>
	</a>
<?php endif; ?>
