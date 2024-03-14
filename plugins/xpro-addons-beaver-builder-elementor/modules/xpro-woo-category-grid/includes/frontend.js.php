(function ($){

		$('.fl-node-<?php echo esc_attr( $id ); ?> .xpro-woo-product-grid-main').cubeportfolio({
			layoutMode: 'grid',
			gridAdjustment: 'responsive',
			mediaQueries: [{
				width: <?php echo $global_settings->medium_breakpoint + 1; ?>,
				cols: <?php echo $settings->column_grid ? $settings->column_grid : 3; ?>,
				options: {
					gapHorizontal: <?php echo $settings->space_between ? $settings->space_between : ''; ?>,
					gapVertical: <?php echo $settings->space_between ? $settings->space_between : ''; ?>,
				}
			}, {
				width: <?php echo $global_settings->responsive_breakpoint + 1; ?>,
				cols: <?php echo $settings->column_grid_medium ? $settings->column_grid_medium : 2; ?>,
				options: {
					gapHorizontal: <?php echo $settings->space_between_medium ? $settings->space_between_medium : 15; ?>,
					gapVertical: <?php echo $settings->space_between_medium ? $settings->space_between_medium : 15; ?>,
				}
			}, {
				width: 0,
				cols: <?php echo $settings->column_grid_responsive ? $settings->column_grid_responsive : 1; ?>,
				options: {
					gapHorizontal: <?php echo $settings->space_between_responsive ? $settings->space_between_responsive : 15; ?>,
					gapVertical: <?php echo $settings->space_between_responsive ? $settings->space_between_responsive : 15; ?>,
				}
			}],
			displayType: 'sequentially',
			displayTypeSpeed: 80,
		});



}(jQuery));
