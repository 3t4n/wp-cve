<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function element_ready_hospital_sets(){
	$icons = array(
		'hospital-001-hospital',
		'hospital-002-ambulance',
		'hospital-003-stretcher',
		'hospital-004-wheelchair',
		'hospital-005-crutches',
		'hospital-006-medical-report',
		'hospital-007-health-insurance',
		'hospital-008-rx',
		'hospital-009-hospital',
		'hospital-010-medical-appointment',
		'hospital-011-thermometer',
		'hospital-012-dropper',
		'hospital-013-test-tube',
		'hospital-014-syringe',
		'hospital-015-ointment',
		'hospital-016-cough-syrup',
		'hospital-017-drugs',
		'hospital-018-drugs',
		'hospital-019-drugs',
		'hospital-020-vial',
		'hospital-021-first-aid-kit',
		'hospital-022-band-aid',
		'hospital-023-bandage',
		'hospital-024-scissors',
		'hospital-025-iodine',
		'hospital-026-doctor',
		'hospital-027-surgeon',
		'hospital-028-nurse',
		'hospital-029-nurse',
		'hospital-030-patient',
		'hospital-031-patient',
		'hospital-032-patient',
		'hospital-033-coverall',
		'hospital-034-virus',
		'hospital-035-doctor',
		'hospital-036-stethoscope',
		'hospital-037-blood-pressure-gauge',
		'hospital-038-blood-bag',
		'hospital-039-iv-bag',
		'hospital-040-dna',
		'hospital-041-monitor',
		'hospital-042-x-ray',
		'hospital-043-scale',
		'hospital-044-ventilator',
		'hospital-045-defibrillator',
		'hospital-046-gloves',
		'hospital-047-mask',
		'hospital-048-mortar',
		'hospital-049-emergency',
		'hospital-050-emergency-call',
	);
	return $icons;
}

class Element_Ready_Add_Hospital_Icons {
    
    public function __construct() { 
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'element_ready_elementor_hospital_setup' ] );
	}
    
    public function element_ready_enqueue_hospital(){
        wp_enqueue_style( 'hospital', ELEMENT_READY_ROOT_ICON . 'hospital/hospital.css', array(), '1.0.1' );
    }

	public function element_ready_elementor_hospital_setup( $tabs = array()){

		$new_icons = element_ready_hospital_sets();

		$tabs['hospital'] = array(
			'name'          => 'hospital',
			'label'         => esc_html__( 'Hospital Icons', 'element-ready-lite' ),
			'labelIcon'     => 'hospital-009-hospital',
			'prefix'        => '',
			'displayPrefix' => 'hospital',
			'url'           => esc_url(ELEMENT_READY_ROOT_ICON . 'hospital/hospital.css'),
			'icons'         => $new_icons,
			'ver'           => '1.0.0',
		);
		return $tabs;
	}

}
new Element_Ready_Add_Hospital_Icons();