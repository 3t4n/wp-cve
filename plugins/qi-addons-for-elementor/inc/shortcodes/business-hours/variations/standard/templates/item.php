<div <?php qi_addons_for_elementor_framework_class_attribute( $classes ); ?>>
	<?php if ( ! empty( $item_day_text ) ) : ?>
		<div class="qodef-e-title-holder">
			<?php if ( 'yes' === $enable_item_icon ) { ?>
				<div class="qodef-e-icon">
					<?php if ( isset( $item_icon ) && ! empty( $item_icon['value'] ) ) { ?>
						<?php \Elementor\Icons_Manager::render_icon( $item_icon, array( 'aria-hidden' => 'true' ) ); ?>
					<?php } ?>
				</div>
			<?php } ?>
			<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $day_tag ); ?> class="qodef-e-day">
				<?php echo esc_html( $item_day_text ); ?>
			</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $day_tag ); ?>>
		</div>
		<div class="qodef-e-line"></div>
		<?php if ( ! empty( $item_hours_text ) ) : ?>
			<p class="qodef-e-hours">
				<?php echo esc_html( $item_hours_text ); ?>
			</p>
		<?php endif; ?>
	<?php endif; ?>
</div>
