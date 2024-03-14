<div class="qodef-widgets-item col-sm-12 col-md-6 <?php echo ( ! $shortcode['active'] ? 'qodef-widgets-item-no-active' : '' ); ?>">
	<div class="qodef-widgets-item-top">
		<h4 class="qodef-widgets-title <?php echo ( $shortcode['premium'] ? 'qodef-premium' : '' ); ?>">
			<span class="qodef-widgets-title-inner">
				<?php echo esc_attr( $shortcode['title'] ); ?>
				<?php echo ( $shortcode['premium'] ? '<sup class="qodef-widgets-premium-label">' . esc_html__( 'premium', 'qi-addons-for-elementor' ) . '</sup>' : '' ); ?>
			</span>
		</h4>
		<?php if ( $shortcode['active'] ) : ?>
		<div class="qodef-checkbox-toggle qodef-field" data-option-name="<?php echo esc_attr( $shortcode_key ); ?>">
			<input type="checkbox" id="<?php echo esc_attr( $shortcode_key ); ?>" name="<?php echo esc_attr( $shortcode_key ); ?>" value="yes" <?php echo ( isset( $disabled[ $shortcode_key ] ) && $disabled[ $shortcode_key ] === $shortcode['base'] ) ? '' : 'checked'; ?> />
			<label for="<?php echo esc_attr( $shortcode_key ); ?>"><?php echo esc_html( $shortcode['title'] ); ?></label>
		</div>
		<?php else : ?>
			<div class="qodef-checkbox-toggle">
				<label></label>
			</div>
		<?php endif; ?>
	</div>
	<?php qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'sub-pages/widgets/templates/parts/demo', '', array( 'shortcode' => $shortcode ) ); ?>
	<?php qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'sub-pages/widgets/templates/parts/documentation', '', array( 'shortcode' => $shortcode ) ); ?>
	<?php qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'sub-pages/widgets/templates/parts/video', '', array( 'shortcode' => $shortcode ) ); ?>
</div>
