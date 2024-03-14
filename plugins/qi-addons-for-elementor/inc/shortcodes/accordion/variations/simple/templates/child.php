<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-e-title-holder">
	<span class="qodef-e-title"><?php echo esc_html( $item_title ); ?></span>
	<span class="qodef-e-mark">
		<span class="qodef-icon--plus">
			<?php
			if ( isset( $icon_open ) && ! empty( $icon_open['value'] ) ) {
				qi_addons_for_elementor_template_part( 'shortcodes/accordion', 'templates/parts/icon', '', array( 'icon' => $icon_open ) );
			} else {
				?>
				+
				<?php
			}
			?>
		</span>
		<span class="qodef-icon--minus">
			<?php
			if ( isset( $icon_close ) && ! empty( $icon_close['value'] ) ) {
				qi_addons_for_elementor_template_part( 'shortcodes/accordion', 'templates/parts/icon', '', array( 'icon' => $icon_close ) );
			} else {
				?>
				-
				<?php
			}
			?>
		</span>
	</span>
</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
<div class="qodef-e-content">
	<div class="qodef-e-content-inner">
		<?php echo qi_addons_for_elementor_framework_wp_kses_html( 'content', $item_content ); ?>
	</div>
</div>
