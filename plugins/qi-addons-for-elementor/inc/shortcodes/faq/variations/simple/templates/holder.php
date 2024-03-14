<div <?php qi_addons_for_elementor_framework_class_attribute( $holder_classes ); ?>>
	<?php
	if ( ! empty( $items ) ) {
		$i = 1;
		foreach ( $items as $item ) {
			$item['title_tag']       = $title_tag;
			$item['icon_open']       = $icon_open;
			$item['icon_close']      = $icon_close;
			$item['behavior']        = $behavior;
			$item['enable_numbered'] = $enable_numbered;
			$item['number']          = $i . '.';

			qi_addons_for_elementor_template_part( 'shortcodes/faq', 'variations/' . $layout . '/templates/child', '', $item );
			$i++;
		}
	}
	?>
</div>
<?php if ( false !== $schema ) { ?>
	<script type="application/ld+json"><?php echo wp_json_encode( $schema ); ?></script>
<?php } ?>
