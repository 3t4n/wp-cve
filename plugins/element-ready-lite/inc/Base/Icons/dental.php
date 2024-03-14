<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function element_ready_dental_sets(){
	$icons = array(
		'dental-001-bacteria',
		'dental-002-bill',
		'dental-003-braces',
		'dental-004-call',
		'dental-005-cavity',
		'dental-006-dentist',
		'dental-007-dental-implant',
		'dental-008-dental-checkup',
		'dental-009-dental-care',
		'dental-010-teeth',
		'dental-011-teeth-cleaning',
		'dental-012-dentist',
		'dental-013-dental-scaler',
		'dental-014-surgeon',
		'dental-015-equipment',
		'dental-016-gloves',
		'dental-017-toothbrush',
		'dental-018-hospital',
		'dental-019-toothbrush',
		'dental-020-first-aid-kit',
		'dental-021-mail',
		'dental-022-medicine',
		'dental-023-periodontal-scaler',
		'dental-024-map',
		'dental-025-pliers',
		'dental-026-protection',
		'dental-027-clipboard',
		'dental-028-medicine',
		'dental-029-search',
		'dental-030-stethoscope',
		'dental-031-teeth',
		'dental-032-dental',
		'dental-033-mirror',
		'dental-034-care',
		'dental-035-syringe',
		'dental-036-toothbrush',
		'dental-037-toothpaste',
		'dental-038-wisdom-tooth',
		'dental-039-dentist',
		'dental-040-surgeon',
	);
	return $icons;
}

class Element_Ready_Add_Dental_Icons {
    
    public function __construct() { 
		// add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'element_ready_enqueue_dental' ] );
		// add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'element_ready_enqueue_dental' ] );    
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'element_ready_elementor_dental_setup' ] );
	}
    
    public function element_ready_enqueue_dental(){
        wp_enqueue_style( 'dental', ELEMENT_READY_ROOT_ICON . 'dental/dental.css', array(), '1.0.1' );
    }

	public function element_ready_elementor_dental_setup( $tabs = array()){

		$new_icons = element_ready_dental_sets();

		$tabs['dental'] = array(
			'name'          => 'dental',
			'label'         => esc_html__( 'Dental Icons', 'element-ready-lite' ),
			'labelIcon'     => 'dental-001-bacteria',
			'prefix'        => '',
			'displayPrefix' => 'dental',
			'url'           => esc_url(ELEMENT_READY_ROOT_ICON . 'dental/dental.css'),
			'icons'         => $new_icons,
			'ver'           => '1.0.0',
		);
		return $tabs;
	}

}
new Element_Ready_Add_Dental_Icons();