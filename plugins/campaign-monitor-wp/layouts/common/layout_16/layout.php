<?php

function fca_eoi_layout_descriptor_16( $layout_id, $texts ) {
	require_once FCA_EOI_PLUGIN_DIR . 'includes/eoi-layout.php';
	$layout_helper = new EasyOptInsLayout( $layout_id );
	$class = $layout_helper->layout_class;

	switch( $layout_helper->layout_type ) {
		
		case 'widget':
			$width = '300px';
			break;
		
		case 'postbox':
			$width = '580px';
			break;
		
		case 'lightbox':
			$width = '680px';
			break;
		
		default:
			$width = '100%';
	}

	return array(

		

		'editables' => array(

			// Added to the fieldset "Form Background"
			'form' => array(
				'.fca_eoi_layout_16.' . $class => array(
					'background-color' => array( __( 'Form Background' ), '#ecf0f1' ),
					'border-color' => array( __( 'Border Color' ), '#d4d8d8' ),
					'width' => array( __( 'Width' ), $width ),
					'text-align' => array( __( 'Alignment' ), 'center' ),
					'bottom-color' => array( __('Form Bottom'), '#3b3b3b'),
				),
			),

			// Added to the fieldset "Headline"
			'headline' => array(
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_headline_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '24px'),
					'color' => array( __('Font Color'), '#3b3b3b'),
				),
			),
			'description' => array(
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_description_copy_wrapper p, ' .
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_description_copy_wrapper div' => array(
					'font-size' => array( __('Font Size'), '16px'),
					'color' => array( __('Font Color'), '#3b3b3b'),
					
				),
			),
			'name_field' => array(
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_name_field_wrapper, ' .
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_name_field_wrapper input' => array(
					'font-size' => array( __( 'Font Size' ), '16px' ),
					'color' => array( __( 'Font Color' ), '#595252' ),
					'background-color' => array( __( 'Background Color' ), '#ffffff' ),
					
				),
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_name_field_wrapper' => array(
					'border-color' => array( __('Border Color'), '#3b3b3b'),
					'width' => array( __( 'Width' ), '200px'),
				),
			),
			'email_field' => array(
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_email_field_wrapper, ' .
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_email_field_wrapper input' => array(
					'font-size' => array( __( 'Font Size' ), '16px' ),
					'color' => array( __( 'Font Color' ), '#595252' ),
					'background-color' => array( __( 'Background Color' ), '#ffffff'),
					
				),
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_email_field_wrapper' => array(
					'border-color' => array( __( 'Border Color' ), '#3b3b3b'),
					'width' => array( __( 'Width' ), '200px'),
				),
			),
			'button' => array(
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_submit_button_wrapper input' => array(
					'font-size' => array( __('Font Size'), '16px' ),
					'color' => array( __( 'Font Color' ), '#FFF' ),
					'background-color' => array( __( 'Button Color' ), '#d35400' ),
					'hover-color' => array( __( 'Button Hover Color' ), '#873600' ),
				
				),
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_submit_button_wrapper' => array(
					'width' => array( __( 'Width' ), '175px'),
				),
			),
			'privacy' => array(
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_privacy_copy_wrapper' => array(
					'font-size' => array( __('Font Size'), '14px'),
					'color' => array( __('Font Color'), '#949494'),
				),
			),
			'fatcatapps' => array(
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a, ' .
				'.fca_eoi_layout_16.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a:hover' => array(
					'color' => array( __('Font Color'), '#949494'),
				),
			),
		)
	);
}