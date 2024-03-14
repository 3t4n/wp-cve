<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function element_ready_education_sets(){
	$icons = array(
		'education-001-work-space',
		'education-002-alarm-clock',
		'education-003-paint-palette',
		'education-004-school-bag',
		'education-005-calculator',
		'education-006-desk',
		'education-007-student',
		'education-008-student',
		'education-009-lamp',
		'education-010-education',
		'education-011-dna',
		'education-012-projector',
		'education-013-document',
		'education-014-school',
		'education-015-invoice',
		'education-016-medal',
		'education-017-board',
		'education-018-conversation',
		'education-019-graduation',
		'education-020-online-library',
		'education-021-book',
		'education-022-document',
		'education-023-document',
		'education-024-document',
		'education-025-pen',
		'education-026-digital-pen',
		'education-027-tools',
		'education-028-pendrive',
		'education-029-document',
		'education-030-printer',
		'education-031-trophy',
		'education-032-certificate',
		'education-033-debit-card',
		'education-034-book',
		'education-035-online-learning',
		'education-036-pencil-sharpener',
		'education-037-shelf',
		'education-038-growing-knowledge',
		'education-039-flag',
		'education-040-note-book',
		'education-041-teacher',
		'education-042-teacher',
		'education-043-online-certificate',
		'education-044-online-learning',
		'education-045-website',
		'education-046-education',
		'education-047-monitor',
		'education-048-mail',
		'education-049-compass',
		'education-050-microscope',
	);
	return $icons;
}

class Element_Ready_Add_Education_Icons {
    
    public function __construct() { 
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'element_ready_elementor_education_setup' ] );
	}
    
    public function element_ready_enqueue_education(){
        wp_enqueue_style( 'education', ELEMENT_READY_ROOT_ICON . 'education/education.css', array(), '1.0.1' );
    }

	public function element_ready_elementor_education_setup( $tabs = array()){

		$new_icons = element_ready_education_sets();

		$tabs['education'] = array(
			'name'          => 'education',
			'label'         => esc_html__( 'Education Icons', 'element-ready-lite' ),
			'labelIcon'     => 'education-001-work-space',
			'prefix'        => '',
			'displayPrefix' => 'education',
			'url'           => ELEMENT_READY_ROOT_ICON . 'education/education.css',
			'icons'         => $new_icons,
			'ver'           => '1.0.0',
		);
		return $tabs;
	}

}
new Element_Ready_Add_Education_Icons();