<?php if ( ! empty($info_button_icon) && ! empty( array_values($info_button_icon)[0] ) ) {?>
	<span <?php qi_addons_for_elementor_framework_class_attribute( $icon_classes ); ?>>
		<span class="qodef-m-icon-inner">
			<?php \Elementor\Icons_Manager::render_icon( $info_button_icon, array( 'aria-hidden' => 'true' ) ); ?>
			<?php if ( ! empty($info_button_icon_hover_move) && ($info_button_icon_hover_move !=='move-horizontal-short')){ ?>
				<?php \Elementor\Icons_Manager::render_icon( $info_button_icon, array( 'aria-hidden' => 'true' ) ); ?>
			<?php } ?>
		</span>
	</span>
<?php } ?>
