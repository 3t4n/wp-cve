lightgallerywp_document_ready(function() {
	jQuery('<?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'invoke_target_ignore' ) ); ?>').justifiedGallery({
		rowHeight :<?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'justified_gallery_row_height_ignore', '220' ) ); ?>,
		lastRow : '<?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'justified_gallery_last_row_ignore', 'nojustify' ) ); ?>',
		maxRowHeight : <?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'justified_gallery_max_row_height_ignore', 'false' ) ); ?>,
		maxRowsCount: <?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'justified_gallery_max_row_count_ignore', '0' ) ); ?>,
		margins: <?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'justified_gallery_margin_ignore', '1' ) ); ?>,
		border: <?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'justified_gallery_border_ignore', '-1' ) ); ?>
	});
});
