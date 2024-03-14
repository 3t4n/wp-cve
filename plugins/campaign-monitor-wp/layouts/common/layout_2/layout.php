<?php

function fca_eoi_layout_descriptor_2( $layout_id, $texts ) {
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
			$width = '650px';
			break;
		
		default:
			$width = '100%';
	}

	return array(

		'editables' => array(

			// Added to the fieldset "Form Background"
			'form' => array(
				'.fca_eoi_layout_2.' . $class => array(
					'background-color' => array( __( 'Form Background Color' ), '#eeeeee' ),
					'width' => array( __( 'Width' ), $width ),
					'text-align' => array( __( 'Alignment' ), 'center' ),
				),
			),

			// Added to the fieldset "Headline"
			'headline' => array(
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_headline_copy_wrapper' => array(
					'color' => array( __( 'Font Color' ), '#FFF' ),
					'font-size' => array( __( 'Font Size' ), '20px' ),
					'background-color' => array( __( 'Background Color' ), '#3197e1' ),
				)
			),

			// Added to the fieldset "Description"
			'description' => array(
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_description_copy_wrapper p, ' .
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_description_copy_wrapper div' => array(
					'font-size' => array( __( 'Font Size' ), '14px' ),
					'color' => array( __('Font Color') , '#000' ),
				),
			),

			// Added to the fieldset "Name"
			'name_field' => array(
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_name_field_wrapper, ' . 
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_name_field_wrapper input, ' .
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_name_field_wrapper i.fa' => array(
					'color' => array( __('Font Color') , '#000' ),
					'font-size' => array( __( 'Font Size' ), '14px'),
					'background-color' => array( __('Background Color') , '#FFF' ),
				),
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_name_field_wrapper' => array(
					'border-color' => array( __('Border Color') , '#FFF' ),
					'width' => array( __( 'Width' ), '100%'),
				),
			),

			// Added to the fieldset "Email"
			'email_field' => array(
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_email_field_wrapper, ' .
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_email_field_wrapper input, ' .
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_email_field_wrapper i.fa'				=> array(
					'background-color' => array( __('Background Color') , '#FFF' ),
					'font-size' => array( __( 'Font Size' ), '14px'),
					'color' => array( __('Font Color') , '#000' ),
				),
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_email_field_wrapper' => array(
					'border-color' => array( __('Border Color') , '#FFF' ),
					'width' => array( __( 'Width' ), '100%'),
				),
			),

			// Added to the fieldset "Button"
			'button' => array(
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_submit_button_wrapper input' => array(
					'font-size' => array( __( 'Font Size' ), '16px' ),
					'color' => array( __('Font Color') , '#FFF' ),
					'background-color' => array( __('Background Color') , '#e84e34' ),
					'border-bottom-color' => array( __( 'Border Color' ), '#A83926' ),
					'hover-color' => array( __( 'Hover Color' ), '#A83926' ),
				
				),
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_submit_button_wrapper' => array(
					'width' => array( __( 'Width' ), '100%'),
				),
			),
			
			// Added to the fieldset "Privacy Policy"
			'privacy' => array(
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_privacy_copy_wrapper div' => array(
					'font-size' => array( __( 'Font Size' ), '12px' ),
					'color' => array( __('Font Color') , '#a1a1a1' ),
				),
			),

			// Added to the fieldset "Branding"
			'fatcatapps' => array(
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a, ' .
				'.fca_eoi_layout_2.' . $class . ' div.fca_eoi_layout_fatcatapps_link_wrapper a:hover' => array(
					'color' => array( __( 'Font Color' ), '#3197e1'),
				),
			),
		)
	);
}