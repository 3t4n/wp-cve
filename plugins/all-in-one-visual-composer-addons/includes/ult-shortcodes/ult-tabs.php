<?php
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_wdo_ult_tabs_parent extends WPBakeryShortCodesContainer {
    	protected function content( $atts, $content = null ) {

			$args = array(
				'vca_tabs_style' => '',
			    'vca_tabs_align' => '',
			    'vca_tabs_icon_position' => '',
			    'vca_tabs_color_theme' => '',
			    'vca_tabs_trigger' => '',
	        );

	        $params  = shortcode_atts($args, $atts);

	        extract($params);

	        // Extract tab titles
	        preg_match_all('/vca_tab_title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE);
	        $tab_titles = array();

	        preg_match_all('/vca_tab_icon="([^\"]+)"/i', $content, $iconmatches, PREG_OFFSET_CAPTURE);
	        $tab_icons = array();

	        /**
	         * get tab titles array
	         *
	         */
	        if (isset($matches[0])) {
	            $tab_titles = $matches[0];
	        }
	        if (isset($iconmatches[0])) {
	        	$tab_icons = $iconmatches[0];
	        }

	        $tab_title_array = array();
	        $tab_icons_array = array();

	        foreach($tab_titles as $tab) {
	            preg_match('/vca_tab_title="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE);
	            $tab_title_array[] = $tab_matches[1][0];
	        }
	        foreach($tab_icons as $tab_icon) {
	        	preg_match('/vca_tab_icon="([^\"]+)"/i', $tab_icon[0], $tab_icon_matches, PREG_OFFSET_CAPTURE);
	        	$tab_icons_array[] = $tab_icon_matches[1][0];
	        }

	 
	        $tabs_color_scheme = $params['vca_tabs_color_theme'];
	        // echo '<pre>';var_dump($vca_tabs_color_theme);echo '</pre>';
	        $color_scheme_path =  '../../assets/tabs/themes/'.$tabs_color_scheme.'.css';

	        wp_enqueue_style( 'wdo-ult-colorscheme-css', plugins_url( $color_scheme_path , __FILE__ )); 

	        $include_path = '/tabs-templates/'.$vca_tabs_style.'.php';

	        ob_start();
	        wp_enqueue_style( 'wdo-ult-tabs-bootstrap',  ULT_URL.'assets/css/bootstrap-min.css');
    		wp_enqueue_style( 'wdo-ult-tabs-css',  ULT_URL.'assets/tabs/css/solid-tabs.css');
            wp_enqueue_style( 'wdo-font-awesome-css', ULT_URL.'assets/css/font-awesome.min.css');
	        wp_enqueue_script( 'wdo-ult-bootstrap-js', ULT_URL.'/assets/tabs/js/bootstrap.min.js', array('jquery'));
	        wp_enqueue_script( 'wdo-ult-solidtabs-js', ULT_URL.'/assets/tabs/js/solid-tabs.js', array('jquery','wdo-ult-bootstrap-js'));
	        wp_enqueue_script( 'wdo-ult-custom-js', ULT_URL.'/assets/tabs/js/script.js', array('jquery','wdo-ult-solidtabs-js'));

	        ?> 
	        
	        <div class="solid-tabs <?php echo $vca_tabs_color_theme; ?> wdo-ult-container" id="solid-tabs"> 
	            <?php include $include_path; ?>
	        </div>
	        <?php

	        $output = ob_get_clean();

	        return $output;
		}
    }
}

if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_wdo_ult_tabs_child extends WPBakeryShortCodesContainer {
    	protected function content( $atts, $content = null ) {
    		extract(shortcode_atts(array(

	        ), $atts));

	        $default_atts = array(
	        	'vca_tab_title' => 'Tab',
	            'vca_tab_id' => '',
	            'vca_tabs_animation' => '',
	        	'vca_tab_icon' => ''
	        );

	        $params = shortcode_atts($default_atts, $atts);
	        extract($params);

	        $rand_number = rand(0, 1000);
	        $params['vca_tab_title'] = '-'.$rand_number;

	        $params['content'] = $content;
	        ?>
	            <div id="tab-<?php echo $vca_tab_id; ?>" class="tab-pane <?php echo $vca_tabs_animation; ?>">
	                <?php echo do_shortcode($content); ?>
	            </div>

	        <?php
    	}

    }
}

if (function_exists("vc_map")) {

    vc_map(array(
        "name" => __("Tabs", "wdo-ultimate-addons"),
        "base" => "wdo_ult_tabs_parent",
        "description" => __("Add content in tabs.", 'wdo-ultimate-addons'),
        "as_parent" => array('only' => 'wdo_ult_tabs_child'),
        "content_element" => true,
        "show_settings_on_create" => true,
        'category'	=> 'All in One Addons',
        "is_container" => true,
        "js_view" => 'VcColumnView',
        "icon" 		=> ULT_URL.'icons/tabs-icon.png',
        'admin_enqueue_css' => array( plugins_url( '/admin/style.css' , __FILE__ ) ),
        "params" => array(
            	array(
                    'type'          => 'dropdown',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Tabs Style', 'wdo-ultimate-addons'),
                    'param_name'    => 'vca_tabs_style',
                    'save_always'   => true,
                    'value' => array(
                        'Select Style' => '',
                        'Tab Design 1 - Top ' => 'tab-design-1',
                        'Tab Design 1 - Bottom  ' => 'tab-design-1-bottom',
                        'Tab Design 1 - Right  ' => 'tab-design-1-right',
                        'Tab Design 1 - Left  ' => 'tab-design-1-left',
                        'Tab Design 2 - Top  ' => 'tab-design-2',
                        'Tab Design 2 - Bottom  ' => 'tab-design-2-bottom',
                        'Tab Design 3 - Top  ' => 'tab-design-3',
                        'Tab Design 3 - Bottom  ' => 'tab-design-3-bottom',
                        'Tab Design 4 - Top  ' => 'tab-design-4',
                        'Tab Design 4 - Bottom  ' => 'tab-design-4-bottom',
                        'Tab Design 5 - Top  ' => 'tab-design-5',
                        'Tab Design 5 - Bottom  ' => 'tab-design-5-bottom',
                        'Tab Design 6 - Top  ' => 'tab-design-6',
                        'Tab Design 6 - Bottom  ' => 'tab-design-6-bottom',
                        'Tab Design 7 - Top  ' => 'tab-design-7',
                        'Tab Design 7 - Bottom  ' => 'tab-design-7-bottom',
                        'Tab Design 8 - Top  ' => 'tab-design-8',
                        'Tab Design 8 - Bottom  ' => 'tab-design-8-bottom',
                    )
                ),

            	array(
                    'type'          => 'dropdown',
                    'admin_label'   => true,
                    'save_always'   => true,
                    'heading'       => esc_html__('Tab Icon Postion', 'wdo-ultimate-addons'),
                    'param_name'    => 'vca_tabs_icon_position',
                    'value' => array(
                         'Inline' => 'l-inline-list',
                         'Block' => 'tab-marker-icon-block',
                         'Only Icon' => 'tab-marker-icon-only',
                    ),
                    "description" => "Select how you want to display the icon in tabs.",
                ),

            	array(
                    'type'          => 'dropdown',
                    'admin_label'   => true,
                    'heading'       => esc_html__('Color Theme', 'wdo-ultimate-addons'),
                    'param_name'    => 'vca_tabs_color_theme',
                    'value' => array(
                        'Select Color Scheme' => '',
                        'Theme 1' => 'theme-01',
                        'Theme 2' => 'theme-02',
                        'Theme 3' => 'theme-03',
                        'Theme 4' => 'theme-04',
                        'Theme 5' => 'theme-05',
                    )
                ),

                array(
            		'type'			=> 'dropdown',
            		'admin_label'	=> true,
                    'save_always'   => true,
            		'heading'		=> esc_html__('Tabs Trigger', 'wdo-ultimate-addons'),
            		'param_name'	=> 'vca_tabs_trigger',
            		'value' => array(
                        'Click' => '',
                        'Hover' => 'hover',
            		)
            	),
    	)
    ));

}

if (function_exists("vc_map")) {
	$rand_id = rand(1000, 10000);
    vc_map(array(
        "name" => __("Tab", "wdo-ultimate-addons"),
        "base" => "wdo_ult_tabs_child",
        "icon" 		=> ULT_URL.'icons/3d-button-admin-icon.png',
        "content_element" => true,
        "as_child" => array('only' => 'wdo_ult_tabs_parent'),
        'as_parent'			=> array(''),
        'js_view'					=> 'VcColumnView',
        'params' => array_merge(
			array(
                array(
                    'type' => 'textfield',
                    "value" => $rand_id,
                    'heading' => esc_html__('Tab ID', "wdo-ultimate-addons"),
                    'param_name' => 'vca_tab_id',
                    "description" => "Give ID for the tab.You can also give custom unique ID",
                ),
				array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Tab Title', "wdo-ultimate-addons"),
                    'param_name' => 'vca_tab_title',
                    "description" => "Will be displayed as the name of tab.",
                ),

                array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Icon', 'wdo-ultimate-addons' ),
                    'param_name' => 'vca_tab_icon',
                    'settings' => array(
                       'emptyIcon' => false,
                       'type' => 'fontawesome',
                       'iconsPerPage' => 500, 
                    ),
                ),
                array(
                    'type'          => 'dropdown',
                    'save_always'   => true,
                    'heading'       => esc_html__('Tabs Animation', 'wdo-ultimate-addons'),
                    'param_name'    => 'vca_tabs_animation',
                    "description" => "It will be animation for the content present in tabs.",
                    'value' => array(
                        'Select Animation' => '',
                        'Bounce' => 'animation-bounce',
                        'Flash' => 'animation-flash',
                        'Pulse' => 'animation-pulse',
                        'Rubber Band' => 'animation-rubberBand',
                        'Shake' => 'animation-shake',
                        'Swing' => 'animation-swing',
                        'Tada' => 'animation-tada',
                        'Wobble' => 'animation-wobble',
                        'Jello' => 'animation-jello',
                        'Bounce In' => 'animation-bounceIn',
                    )
                ),
			)
		)
    )); 

}
?>