<?php if ( isset( $client_icon ) && ! empty( $client_icon['value'] ) ) { ?>
	<div class="qodef-e-icon">
		<?php if ( ! empty( $client_link['url'] ) ) { ?>
			<a href="<?php echo esc_url( $client_link['url'] ); ?>" <?php echo qi_addons_for_elementor_framework_get_inline_attrs( qi_addons_for_elementor_get_link_attributes( $client_link ) ); ?>>
		<?php } ?>
		<?php \Elementor\Icons_Manager::render_icon( $client_icon, array( 'aria-hidden' => 'true' ) ); ?>
		<?php if ( ! empty( $client_link['url'] ) ) { ?>
			</a>
		<?php } ?>
	</div>
<?php } ?>
