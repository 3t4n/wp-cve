<a <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> href="<?php echo esc_url( $info_button_link['url'] ); ?>" <?php qi_addons_for_elementor_framework_inline_attrs( $data_attrs ); ?>>
	<div class="qodef-m-text-holder">
		<span class="qodef-m-text"><?php echo esc_html( $info_button_text ); ?></span>
		<?php if ( ! empty( $info_button_subtext ) ) { ?>
			<span class="qodef-m-subtext"><?php echo esc_html( $info_button_subtext ); ?></span>
		<?php } ?>
	</div>
		<?php if ( 'yes' === $info_button_icon_enable_side_border ) { ?>
			<span class="qodef-m-border"></span>
			<?php
		}
		qi_addons_for_elementor_template_part( 'shortcodes/info-button', 'templates/parts/icon', '', $params );
		?>
</a>
