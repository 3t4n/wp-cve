<?php
/*
 * Extending row setting
*/
function bb_er_row_extender( $form, $id ) {
	if ( 'row' == $id ) {
		$form['tabs']['bber_expandable'] = array(
			'title'    => __( 'Expandable Row', 'bb-expandable-row' ),
			'sections' => array(
				'expanbable_options' => array(
					'title'  => '',
					'fields' => array(
						'is_enable' => array(
							'type'    => 'select',
							'label'   => __( 'Expandable Row?', 'bb-expandable-row' ),
							'options' => array(
								'yes' => __( 'Yes', 'bb-expandable-row' ),
								'no'  => __( 'No', 'bb-expandable-row' )
							),
							'default' => 'no',
							'toggle'  => array(
								'yes' => array(
									'sections' => array(
										'er_general',
										'er_before_click',
										'er_after_click',
										'er_typography',
										'er_padding'
									),
								)
							),
						),
					)
				),// expandable option
				'er_general'         => array(
					'title'  => __( 'Common', 'bb-expandable-row' ),
					'fields' => array(
						'er_effect'        => array(
							'type'    => 'select',
							'label'   => __( 'Appear Animation/ Effect', 'bb-expandable-row' ),
							'options' => array(
								'slide' => __( 'Slide', 'bb-expandable-row' ),
								'fade'  => __( 'Fade', 'bb-expandable-row' ),
							),
							'default' => 'slide'
						),
						'er_img_type'      => array(
							'type'    => 'select',
							'label'   => __( 'Icon/Image Type', 'bb-expandable-row' ),
							'options' => array(
								'none'  => __( 'None', 'bb-expandable-row' ),
								'icon'  => __( 'Icon', 'bb-expandable-row' ),
								'image' => __( 'Image', 'bb-expandable-row' ),
							),
							'default' => 'none',
							'toggle'  => array(
								'icon'  => array(
									'fields' => array(
										'er_icon_size',
										'er_icon_position',
										'er_bc_icon',
										'er_ac_icon',
										'er_bc_icon_color',
										'er_bc_icon_hcolor',
										'er_ac_icon_color',
										'er_ac_icon_hcolor'
									)
								),
								'image' => array(
									'fields' => array(
										'er_image_size',
										'er_icon_position',
										'er_bc_image',
										'er_ac_image'
									)
								)
							),
						),
						'er_image_size'    => array(
							'type'        => 'text',
							'label'       => __( 'Image Size', 'bb-expandable-row' ),
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '',
							'description' => 'px',
						),
						'er_icon_size'     => array(
							'type'        => 'text',
							'label'       => __( 'Icon Size', 'bb-expandable-row' ),
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '',
							'description' => 'px',
						),
						'er_icon_position' => array(
							'type'    => 'select',
							'label'   => __( 'Icon/Image Position', 'bb-expandable-row' ),
							'options' => array(
								'top'    => __( 'Top', 'bb-expandable-row' ),
								'bottom' => __( 'Bottom', 'bb-expandable-row' ),
								'left'   => __( 'Left', 'bb-expandable-row' ),
								'right'  => __( 'Right', 'bb-expandable-row' ),
							),
							'default' => 'left',
						),
						'er_bg_type'       => array(
							'type'    => 'select',
							'label'   => __( 'Background Type', 'bb-expandable-row' ),
							'options' => array(
								'color' => __( 'Color', 'bb-expandable-row' ),
								'image' => __( 'Image', 'bb-expandable-row' ),
							),
							'default' => 'color',
							'toggle'  => array(
								'color' => array(
									'fields' => array(
										'er_bc_bg_color',
										'er_bc_bg_hcolor',
										'er_ac_bg_color',
										'er_ac_bg_hcolor'
									)
								),
								'image' => array(
									'fields' => array( 'er_bc_bg_image', 'er_ac_bg_image' )
								),
							),
						),
						'er_title_align'   => array(
							'type'    => 'select',
							'label'   => __( 'Title Alignment', 'bb-expandable-row' ),
							'options' => array(
								'center' => __( 'Center', 'bb-expandable-row' ),
								'left'   => __( 'Left', 'bb-expandable-row' ),
								'right'  => __( 'Right', 'bb-expandable-row' )
							),
							'default' => 'center',
						),
					)
				),
				'er_before_click'    => array(
					'title'  => __( 'Before Click', 'bb-expandable-row' ),
					'fields' => array(
						'er_bc_title'        => array(
							'type'    => 'text',
							'label'   => __( 'Title', 'bb-expandable-row' ),
							'preview' => array(
								'type'     => 'text',
								'selector' => '.bb-er-title',
							),
							'connections'   => array( 'string', 'html' )
						),
						'er_bc_title_color'  => array(
							'type'       => 'color',
							'label'      => __( 'Title Color', 'bb-expandable-row' ),
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bb-er-row',
								'property' => 'color',
							)
						),
						'er_bc_title_hcolor' => array(
							'type'       => 'color',
							'label'      => __( 'Title Hover Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_bc_bg_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-expandable-row' ),
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bb-er-row',
								'property' => 'background-color',
							)
						),
						'er_bc_bg_hcolor'    => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_bc_icon'         => array(
							'type'        => 'icon',
							'label'       => __( 'Select Icon', 'bb-expandable-row' ),
							'show_remove' => true,
						),
						'er_bc_icon_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'bb-expandable-row' ),
							'show_reset' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.bber-icon',
								'property' => 'color'
							)
						),
						'er_bc_icon_hcolor'  => array(
							'type'       => 'color',
							'label'      => __( 'Icon Hover Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_bc_image'        => array(
							'type'        => 'photo',
							'label'       => __( 'Select Icon Image', 'bb-expandable-row' ),
							'show_remove' => true,
						),
						'er_bc_bg_image'     => array(
							'type'        => 'photo',
							'label'       => __( 'Select Background Image', 'bb-expandable-row' ),
							'show_remove' => true,
						),
					)
				),
				'er_after_click'     => array(
					'title'  => __( 'After Click', 'bb-expandable-row' ),
					'fields' => array(
						'er_ac_title'        => array(
							'type'  => 'text',
							'label' => __( 'Title', 'bb-expandable-row' ),
							'connections'   => array( 'string', 'html' )
						),
						'er_ac_title_color'  => array(
							'type'       => 'color',
							'label'      => __( 'Title Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_ac_title_hcolor' => array(
							'type'       => 'color',
							'label'      => __( 'Title Hover Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_ac_bg_color'     => array(
							'type'       => 'color',
							'label'      => __( 'Background Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_ac_bg_hcolor'    => array(
							'type'       => 'color',
							'label'      => __( 'Background Hover Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_ac_icon'         => array(
							'type'        => 'icon',
							'label'       => __( 'Select Icon', 'bb-expandable-row' ),
							'show_remove' => true,
						),
						'er_ac_icon_color'   => array(
							'type'       => 'color',
							'label'      => __( 'Icon Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_ac_icon_hcolor'  => array(
							'type'       => 'color',
							'label'      => __( 'Icon Hover Color', 'bb-expandable-row' ),
							'show_reset' => true,
						),
						'er_ac_image'        => array(
							'type'        => 'photo',
							'label'       => __( 'Select Icon Image', 'bb-expandable-row' ),
							'show_remove' => true,
						),
						'er_ac_bg_image'     => array(
							'type'        => 'photo',
							'label'       => __( 'Select Background Image', 'bb-expandable-row' ),
							'show_remove' => true,
						),
					)
				),
				'er_typography'      => array(
					'title'  => __( 'Typography', 'bb-expandable-row' ),
					'fields' => array(
						'er_title_typography' => array(
							'type'    => 'font',
							'label'   => __( 'Before/After Click ', 'bb-expandable-row' ),
							'default' => array(
								'family' => 'Defaults',
								'weight' => 'Defaults'
							),
							'preview' => array(
								'type'     => 'font',
								'selector' => '.bb-er-row'
							)
						),
						'er_font_size'        => array(
							'type'        => 'text',
							'label'       => __( 'Font Size', 'bb-expandable-row' ),
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '28',
							'description' => 'px',
						),
						'er_line_height'      => array(
							'type'        => 'text',
							'label'       => __( 'Line Height', 'bb-expandable-row' ),
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '32',
							'description' => 'px',
						)
					)
				),// typography
				'er_padding'         => array(
					'title'  => __( 'Padding', 'bb-expandable-row' ),
					'fields' => array(
						// padding top
						'er_padding_top'    => array(
							'type'        => 'text',
							'label'       => __( 'Top', 'bb-expandable-row' ),
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '20',
							'description' => __( 'px', 'bb-expandable-row' ),
						),
						// padding bottom
						'er_padding_bottom' => array(
							'type'        => 'text',
							'label'       => __( 'Bottom', 'bb-expandable-row' ),
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '20',
							'description' => __( 'px', 'bb-expandable-row' ),
						),
						// padding left
						'er_padding_left'   => array(
							'type'        => 'text',
							'label'       => __( 'Left', 'bb-expandable-row' ),
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '20',
							'description' => __( 'px', 'bb-expandable-row' ),
						),
						// padding right
						'er_padding_right'  => array(
							'type'        => 'text',
							'label'       => __( 'Right', 'bb-expandable-row' ),
							'maxlength'   => '3',
							'size'        => '5',
							'placeholder' => '20',
							'description' => __( 'px', 'bb-expandable-row' ),
						),
					)
				),// padding
			),//sections
		);
	}

	return $form;
}

add_filter( 'fl_builder_register_settings_form', 'bb_er_row_extender', 10, 2 );

/*
 * CSS for Expandable Row
*/
function bb_er_row_css( $css, $nodes, $global_settings ) {

	$enable_icons = FLBuilderModel::get_enabled_icons();
	// check if foundation icon enable
	if( in_array( 'foundation-icons' , $enable_icons ) ) {
		wp_enqueue_style( 'foundation_icon', BB_ER_ROW_URL . 'expandable-row/fonts/foundation-icons/foundation-icons.css', array() );
	}
	// check if font-awesome icon enable
	if ( in_array( 'font-awesome' , $enable_icons ) ) {
		wp_enqueue_style( 'font_awesome_icon', BB_ER_ROW_URL . 'expandable-row/fonts/font-awesome/css/font-awesome.min.css', array() );
	}
	foreach ( $nodes['rows'] as $row ) {
		ob_start();
		include BB_ER_ROW_DIR . 'expandable-row/css/row-css.php';
		$css .= ob_get_clean();
	}
	return $css;
}

add_filter( 'fl_builder_render_css', 'bb_er_row_css', 10, 3 );

/*
 * jQuery and frontend structure for Expandable Row
*/
function bb_er_row_structure( $js, $nodes, $global_settings ) {

	foreach ( $nodes['rows'] as $row ) {
		ob_start();
		include BB_ER_ROW_DIR . 'expandable-row/js/row-js.php';
		$js .= ob_get_clean();
	}
	return $js;
}

add_filter( 'fl_builder_render_js', 'bb_er_row_structure', 10, 3 );

/*
 * Row structure when page builder is active
*/
function row_structure( $rows ) {
	if ( ( $rows->settings->is_enable == 'yes' ) && FLBuilderModel::is_builder_active() ) {
		include BB_ER_ROW_DIR . 'expandable-row/backend-row.php';
	}
}

add_action( 'fl_builder_before_render_row_bg', 'row_structure', 10, 1 );

/*
 * jQuery for Expandable row when page builder is active
*/
function backend_row_js() {
	if ( FLBuilderModel::is_builder_active() ) {
		wp_enqueue_script( 'backend-script', BB_ER_ROW_URL . 'expandable-row/js/backend-row-js.js', true );
	}
}

add_action( 'fl_builder_before_render_row_bg', 'backend_row_js', 10 );

?>
