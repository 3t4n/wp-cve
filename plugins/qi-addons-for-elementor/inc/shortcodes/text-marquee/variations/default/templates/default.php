<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-m-content">
		<div class="qodef-m-text qodef-text--original">
			<?php
			foreach ( $items as $item ) {
				$item_class = 'qodef-m-text-item elementor-repeater-item-' . $item['_id'];
				?>
				<span <?php qi_addons_for_elementor_framework_class_attribute( $item_class ); ?> ><?php echo wp_kses( $item['item_text'], array( 'span' => array( 'style' => true ) ) ); ?></span>
                <?php
                if ( isset( $separator_icon ) && ! empty( $separator_icon['value'] ) ) {
					qi_addons_for_elementor_template_part( 'shortcodes/text-marquee', 'templates/parts/icon', '', $params );
				}
                ?>
			<?php } ?>
		</div>
		<div class="qodef-m-text qodef-text--copy">
			<?php
			foreach ( $items as $item ) {
				$item_class = 'qodef-m-text-item elementor-repeater-item-' . $item['_id'];
				?>
				<span <?php qi_addons_for_elementor_framework_class_attribute( $item_class ); ?> ><?php echo wp_kses( $item['item_text'], array( 'span' => array( 'style' => true ) ) ); ?></span>
                <?php
				if ( isset( $separator_icon ) && ! empty( $separator_icon['value'] ) ) {
					qi_addons_for_elementor_template_part( 'shortcodes/text-marquee', 'templates/parts/icon', '', $params );
				}
				?>
			<?php } ?>
		</div>
	</div>
</div>
