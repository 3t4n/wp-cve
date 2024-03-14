<a <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?> href="<?php echo esc_url( $button_link['url'] ); ?>" <?php qi_addons_for_elementor_framework_inline_attrs( $data_attrs ); ?>>
	<span class="qodef-m-text"><?php echo esc_html( $button_text ); ?></span>
	<?php
	qi_addons_for_elementor_template_part( 'shortcodes/button', 'templates/parts/icon', '', $params );
	?>
	<div class="qodef-m-inner-border">
		<?php if ( 'move-outer-edge' !== $button_inner_border_hover_type ) { ?>
			<span class="qodef-m-border-top"></span>
			<span class="qodef-m-border-right"></span>
			<span class="qodef-m-border-bottom"></span>
			<span class="qodef-m-border-left"></span>
			<?php
		}
		?>
	</div>
	<?php if ( ! empty( $button_inner_border_hover_type ) && ( ( 'draw q-draw-center' == $button_inner_border_hover_type ) || ( 'draw q-draw-one-point' == $button_inner_border_hover_type ) || ( 'draw q-draw-two-points' == $button_inner_border_hover_type ) ) ) { ?>
		<div class="qodef-m-inner-border qodef-m-inner-border-copy">
			<span class="qodef-m-border-top"></span>
			<span class="qodef-m-border-right"></span>
			<span class="qodef-m-border-bottom"></span>
			<span class="qodef-m-border-left"></span>
		</div>
		<?php
	}
	?>
</a>
