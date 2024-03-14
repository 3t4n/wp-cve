<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function element_ready_science_sets(){
	$icons = array(
		'science-001-skull',
		'science-002-plant',
		'science-003-moon',
		'science-004-bacteria',
		'science-005-magnifying-glass',
		'science-006-sperm',
		'science-007-test-tube',
		'science-008-atom',
		'science-009-flask',
		'science-010-test-tube',
		'science-011-flask',
		'science-012-molecule',
		'science-013-saturn',
		'science-014-planets',
		'science-015-syringe',
		'science-016-dna',
		'science-017-magnet',
		'science-018-eye',
		'science-019-eyedropper',
		'science-020-magnifying-glass',
		'science-021-bomb',
		'science-022-virus',
		'science-023-capsule',
		'science-024-spaceship',
		'science-025-bowl',
		'science-026-science-book',
		'science-027-paper',
		'science-028-heart-rate',
		'science-029-sun',
		'science-030-alert',
		'science-031-clipboard',
		'science-032-magnifying-glass',
		'science-033-test-tubes',
		'science-034-electric-pole',
		'science-035-paper',
		'science-036-pulley',
		'science-037-pentagon',
		'science-038-flask',
		'science-039-capsule',
		'science-040-globe',
		'science-041-binocular',
		'science-042-global',
		'science-043-candle',
		'science-044-plant',
		'science-045-virus',
		'science-046-bacteria',
		'science-047-molecules',
		'science-048-molecule',
		'science-049-molecule',
		'science-050-hexagon',
		'science-051-molecule',
		'science-052-whiteboard',
		'science-053-car-battery',
		'science-054-shape',
		'science-055-bacteria',
		'science-056-science-book',
		'science-057-formula',
		'science-058-mind',
		'science-059-alien',
		'science-060-biohazard-sign',
		'science-061-radar',
		'science-062-apple',
		'science-063-mirror',
		'science-064-seesaw',
		'science-065-bacteria',
		'science-066-graph',
		'science-067-circle',
		'science-068-satellite-dish',
		'science-069-biohazard-sign',
		'science-070-measuring-cup',
		'science-071-molecule',
		'science-072-shield',
		'science-073-molecule',
	);
	return $icons;
}

class Element_Ready_Add_Science_Icons {
    
    public function __construct() { 
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'element_ready_elementor_science_setup' ] );
	}
    
    public function element_ready_enqueue_science(){
        wp_enqueue_style( 'science', ELEMENT_READY_ROOT_ICON . 'science/science.css', array(), '1.0.1' );
    }

	public function element_ready_elementor_science_setup( $tabs = array()){

		$new_icons = element_ready_science_sets();

		$tabs['science'] = array(
			'name'          => 'science',
			'label'         => esc_html__( 'Science Icons', 'element-ready-lite' ),
			'labelIcon'     => 'science-014-planets',
			'prefix'        => '',
			'displayPrefix' => 'science',
			'url'           => esc_url(ELEMENT_READY_ROOT_ICON . 'science/science.css'),
			'icons'         => $new_icons,
			'ver'           => '1.0.0',
		);
		return $tabs;
	}

}
new Element_Ready_Add_Science_Icons();