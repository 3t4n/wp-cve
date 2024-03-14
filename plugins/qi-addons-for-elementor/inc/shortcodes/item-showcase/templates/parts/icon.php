<?php if ( ! empty( $item['item_link']['url'] ) ) : ?>
	<a itemprop="url" href="<?php echo esc_url( $item['item_link']['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $item['item_link'] ) ); ?>>
<?php endif; ?>
		<?php if ( isset( $item['item_icon'] ) && ! empty( $item['item_icon']['value'] ) ) { ?>
			<div class="qodef-e-icon-holder">
				<?php \Elementor\Icons_Manager::render_icon( $item['item_icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</div>
		<?php } ?>
<?php if ( ! empty( $item['item_link']['url'] ) ) : ?>
	</a>
<?php endif; ?>
