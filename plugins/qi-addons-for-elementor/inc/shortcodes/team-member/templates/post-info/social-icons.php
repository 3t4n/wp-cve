<?php
if ( is_array( $items ) && count( $items ) ) {
	?>
	<div class="qodef-m-social-icons">
		<?php
		foreach ( $items as $item ) {
			if ( ! empty( $item['icon'] ) ) {
				if ( ! empty( $item['link']['url'] ) ) {
					?>
					<a class="qodef-e-social-icon-link" itemprop="url" href="<?php echo esc_url( $item['link']['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $item['link'] ) ); ?>>
				<?php } ?>
				<span class="qodef-e-social-icon">
					<?php \Elementor\Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) ); ?>
				</span>
				<?php if ( ! empty( $item['link']['url'] ) ) { ?>
					</a>
					<?php
				}
			}
		}
		?>
	</div>
<?php } ?>
