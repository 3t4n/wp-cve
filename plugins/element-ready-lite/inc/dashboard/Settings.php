<?php

namespace Element_Ready\dashboard;

class Settings {

    public function register() {
        add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
        add_filter( 'plugin_action_links_'.ELEMENT_READY_PLUGIN_BASE, [ $this ,'add_plugin_page_settings_link'] );
    }

    function add_plugin_page_settings_link( $links ) {
	
        $links[] = wp_kses_post('<a href="' .
            admin_url( 'admin.php?page='.ELEMENT_READY_SETTING_PATH ) .
            '">' . esc_html__('Settings','element-ready-lite') . '</a>');
    
        if(!did_action('element_ready_pro_init')){

            $links[] = wp_kses_post('<a style="color: #325DF6; font-weight: bold;"  href="' .
            esc_url( ELEMENT_READY_DEMO_URL ).'#er-pricing' .
            '">' . esc_html__('Go Pro','element-ready-lite') . '</a>');
        }
     
        return $links;
    }

    public function plugin_row_meta( $plugin_meta, $plugin_file ) {

		if ( ELEMENT_READY_PLUGIN_BASE === $plugin_file ) {
			$row_meta = [
				'docs' => wp_kses_post('<a href="'.esc_url(ELEMENT_READY_DEMO_URL.'docs/element-ready').'" aria-label="' . esc_attr__( 'View Documentation', 'element-ready-lite' ) . '" target="_blank">' . esc_html__( 'Docs & FAQs', 'element-ready-lite' ) . '</a>'),
				'plugin-demos' => wp_kses_post('<a href="'.esc_url(ELEMENT_READY_DEMO_URL).'" aria-label="' . esc_attr__( 'View Demos', 'element-ready-lite' ) . '" target="_blank">' . esc_html__( 'Demos', 'element-ready-lite' ) . '</a>'),
				'plugin-support' => wp_kses_post('<a href="https://support.quomodosoft.com/support/" aria-label="' . esc_attr__( 'Get Support', 'element-ready-lite' ) . '" target="_blank">' . esc_html__( 'Get Support', 'element-ready-lite' ) . '</a>'),
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

    
}    