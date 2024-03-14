<?php if ( 'accordion' === $behavior ) { ?>
	<span class="qodef-e-mark">
		<span class="qodef-icon--plus">
			<?php
			if ( isset( $icon_open ) && ! empty( $icon_open['value'] ) ) {
				qi_addons_for_elementor_template_part( 'shortcodes/faq', 'templates/parts/icon', '', array( 'icon' => $icon_open ) );
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
				qi_addons_for_elementor_template_part( 'shortcodes/faq', 'templates/parts/icon', '', array( 'icon' => $icon_close ) );
			} else {
				?>
				-
				<?php
			}
			?>
		</span>
	</span>
	<?php
}
