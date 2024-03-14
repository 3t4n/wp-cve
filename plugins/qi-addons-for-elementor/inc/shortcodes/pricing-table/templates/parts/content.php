<?php
if ( isset( $items ) && count( $items ) ) {
	?>
	<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $list_tag, 'list' ); ?> class="qodef-m-content">
		<?php
		foreach ( $items as $item ) {
			$classes   = array( 'qodef-e-item' );
			$classes[] = ( 'yes' === $item['item_excluded'] ) ? 'qodef--excluded' : '';
			?>
			<li <?php qi_addons_for_elementor_framework_class_attribute( $classes ); ?>>
				<?php if ( 'unordered' === $list_type && ! empty( $icon ) ) { ?>
					<span class="qodef-e-icon"><?php \Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?></span>
				<?php } ?>
				<span class="qodef-e-text"><?php echo esc_html( $item['item_text'] ); ?></span>
			</li>
		<?php } ?>
	</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $list_tag, 'list' ); ?>>
<?php } ?>
