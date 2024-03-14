<?php

namespace Shop_Ready\extension\elewidgets\document;

use Shop_Ready\base\elementor\Document_Settings;
use Elementor\Controls_Manage;
use Elementor\Core\DocumentTypes\PageBase;
/*
* Page Settings
* @since 1.0
* Page Settings in Elementor Editor
* usege in login register widgets
*/
Class Page_Settings extends Document_Settings{
    const PANEL_TAB = 'woo-ready-tab';
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
		\Elementor\Controls_Manager::add_tab( self::PANEL_TAB, __( 'Shop Ready', 'shopready-elementor-addon' ) );
	}

	/**
	 * Resister additional document controls.
	 *
	 * @param PageBase $document
	 */
	public function register_document_controls( $document ) {
		// PageBase is the base class for documents like `post` `page` and etc.
		// In this example we check also if the document supports elements. (e.g. a Kit doesn't has elements)
		if ( ! $document instanceof PageBase || ! $document::get_property( 'has_elements' ) ) {
			return;
		}

        do_action('shop_ready_page_extra_settings',$document);
	}
 

}