<div class="fl-quadmenu">
	<div class="fl-clear"></div>
	<?php
	if ( ! empty( $settings->menu ) ) {

	$args = array(
		'echo'                        => false,
		'menu'                        => $settings->menu,
		'theme'                       => $settings->theme,
		'layout'                      => $settings->layout,
		'layout_align'                => $settings->layout_align,
		'layout_divider'              => $settings->layout_divider,
		'layout_caret'                => $settings->layout_caret,
		'layout_classes'              => $settings->layout_classes,
		'layout_width'                => wp_validate_boolean( $settings->layout_width ),
		'layout_width_inner'          => wp_validate_boolean( $settings->layout_width_inner ),
		'layout_width_inner_selector' => esc_html( $settings->layout_width_inner_selector ),
		'layout_lazyload'             => wp_validate_boolean( $settings->layout_lazyload ),
		'layout_current'              => wp_validate_boolean( $settings->layout_current ),
	);

		if ( isset( $settings->navbar_logo ) ) {
		$args['navbar_logo'] = (array) \FLBuilderPhoto::get_attachment_data( $settings->navbar_logo );
		}

		if ( wp_doing_ajax() ) {
		$args['layout_classes'] = 'js';
		}

	echo quadmenu( $args );
	}
	?>
</div>
