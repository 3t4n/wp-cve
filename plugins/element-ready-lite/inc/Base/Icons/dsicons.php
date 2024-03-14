<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function element_ready_dsicon_sets(){
	$icons = array(
		'dsicon-001-mouse',
		'dsicon-002-wireframe',
		'dsicon-003-magic-wand',
		'dsicon-004-rgb',
		'dsicon-005-book',
		'dsicon-006-protractor',
		'dsicon-008-image',
		'dsicon-009-sketch-1',
		'dsicon-010-paint-bucket',
		'dsicon-012-opacity',
		'dsicon-013-settings',
		'dsicon-014-3d-cube',
		'dsicon-028-browser',
		'dsicon-015-browser-1',
		'dsicon-016-pantone',
		'dsicon-017-pencil-case',
		'dsicon-018-sketch',
		'dsicon-019-text-editor',
		'dsicon-007-text-editor-1',
		'dsicon-020-laptop',
		'dsicon-021-eraser',
		'dsicon-036-idea',
		'dsicon-022-idea-1',
		'dsicon-023-visibility',
		'dsicon-024-creativity',
		'dsicon-025-reflect',
		'dsicon-026-target',
		'dsicon-027-lasso',
		'dsicon-029-crop',
		'dsicon-030-graphic-design',
		'dsicon-031-layout',
		'dsicon-032-drawing',
		'dsicon-033-compass',
		'dsicon-034-ruler',
		'dsicon-035-file',
		'dsicon-038-agenda',
		'dsicon-039-graphic-tablet',
		'dsicon-040-pipette',
		'dsicon-041-layers',
		'dsicon-042-tools',
		'dsicon-043-paint-palette',
		'dsicon-044-transform',
		'dsicon-045-paint-brush',
		'dsicon-046-paint-spray',
		'dsicon-047-paint-roller',
		'dsicon-048-color-palette',
		'dsicon-049-ideas',
		'dsicon-050-vector',
		'dsicon-037-vector-1',
		'dsicon-011-vector-2',
	);
	return $icons;
}

class Element_Ready_Add_Dsicons {
    
    public function __construct() { 
		// add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'element_ready_enqueue_dsicons' ] );
		// add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'element_ready_enqueue_dsicons' ] );    
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'element_ready_elementor_dsicon_setup' ] );
	}
    
    public function element_ready_enqueue_dsicons(){
        wp_enqueue_style( 'dsicon', ELEMENT_READY_ROOT_ICON . 'dsicons/dsicon.css', array(), '1.0.1' );
    }

	public function element_ready_elementor_dsicon_setup( $tabs = array()){

		$new_icons = element_ready_dsicon_sets();

		$tabs['dsicon'] = array(
			'name'          => 'dsicon',
			'label'         => esc_html__( 'Dsicon Icons', 'element-ready-lite' ),
			'labelIcon'     => 'dsicon-042-tools',
			'prefix'        => '',
			'displayPrefix' => 'dsicon',
			'url'           => esc_url(ELEMENT_READY_ROOT_ICON . 'dsicons/dsicon.css'),
			'icons'         => $new_icons,
			'ver'           => '1.0.0',
		);
		return $tabs;
	}

}
new Element_Ready_Add_Dsicons();