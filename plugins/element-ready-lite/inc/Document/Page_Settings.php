<?php

namespace Element_Ready\Document;

use Shop_Ready\base\elementor\Document_Settings;
use Elementor\Core\DocumentTypes\PageBase;
/*
* Page Settings
* @since 1.0
* Page Settings in Elementor Editor
* usege in login register widgets
*/
Class Page_Settings extends Document_Settings{
	
    const PANEL_TAB = 'elements-ready-tab';
    public function register(){
        
        add_action( 'elementor/init', [ $this, 'add_panel_tab' ] );
		add_action( 'elementor/documents/register_controls', [ $this, 'register_document_controls' ] );
    }
    /******** ::::::::::::::::: 
    * Page Banner
    * 
    * action hook elementor/element/wp-page/document_settings/after_section_end
    * @return void 
    ::::::::::::::::::::::::::::::::*/ 
	public function add_panel_tab() {
		\Elementor\Controls_Manager::add_tab( self::PANEL_TAB, __( 'ElementsReady', 'element-ready-lite' ) );
	}

	/**
	 * Resister additional document controls.
	 *
	 * @param PageBase $document
	 */
	public function register_document_controls( $document ) {
		// PageBase is the base class for documents like `post` `page` and etc.
		
		if ( ! $document instanceof PageBase || ! $document::get_property( 'has_elements' ) ) {
			return;
		}

		$document->start_controls_section(
			'woo_ready_page_banner_section',
			[
				'label' => __( 'Banner', 'element-ready-lite' ),
				'tab' => self::PANEL_TAB,
			]
		);


		$document->end_controls_section();

        do_action('element_ready_page_extra_settings',$document);
	}
 

}