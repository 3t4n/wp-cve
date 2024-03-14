<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php qi_addons_for_elementor_template_part( 'shortcodes/how-to', 'templates/section-title', '', $params ); ?>
	<div class="qodef-m-content">
		<?php
		if ( count( $items ) ) {
			foreach ( $items as $item ) {
				$item['item_title_tag'] = isset( $item_title_tag ) ? $item_title_tag : 'h3';
				$item['title_id']       = $this_shortcode->build_title_id( $item, $title );

				qi_addons_for_elementor_template_part( 'shortcodes/how-to', 'templates/step', '', $item );
			}
		}
		?>
	</div>
</div>
<?php if ( false !== $schema ) { ?>
	<script type="application/ld+json"><?php echo wp_json_encode( $schema ); ?></script>
<?php } ?>
