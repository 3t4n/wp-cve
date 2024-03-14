<?php
/**
*Promotion customizer options
*
* @package walker_core
*
*/

if (! function_exists('walker_promotion_options_register')) {
	function walker_promotion_options_register( $wp_customize ) {
		if (! wc_fs()->can_use_premium_code() ) {
			require WALKER_CORE_PATH . 'admin/customizer/walker-core-promo-controls.php';
			$wp_customize->add_section('walker_core_promotion_options', 
			 	array(
			        'title' => esc_html__('Gridchamp Information', 'walker-core'),
			        'priority' => 1,
		
		    	)
			 );

			$description = esc_html__( 'Find out the demos and documentation of the theme.', 'walker-core' );
            $wp_customize->add_setting( 'pro_theme_message_text', 
                array(
                   'sanitize_callback' => 'sanitize_text_field',
                ) 
            );

        $wp_customize->add_control( new Walker_Core_Custom_Text( $wp_customize, 'pro_theme_message_text', 
            array(
                'label' => esc_html__( 'Theme Information', 'walker-core' ),
                'section' => 'walker_core_promotion_options',
                'settings' => 'pro_theme_message_text',
                'description' => $description,
                'type' => 'walker-core-custom-text',
                
            ) )
        );
        $gridchamp_info = '';
	    $gridchamp_info .= '<span class="gridchamp-info-row"><label class="row-element">' . esc_html__( 'Theme Details', 'walker-core' ) . ': </label><a class="button alignright" href="' . esc_url( 'https://walkerwp.com/gridchamp/' ) . '" target="_blank">' . esc_html__( 'Click Here', 'walker-core' ) . '</a></span>';
	    $gridchamp_info .= '<span class="gridchamp-info-row"><label class="row-element">' . esc_html__( 'Documentation', 'walker-core' ) . ': </label><a class="button alignright" href="' . esc_url( 'https://walkerwp.com/docs-category/gridchamp/' ) . '" target="_blank">' . esc_html__( 'Click Here', 'walker-core' ) . '</a></span>';
	    $gridchamp_info .= '<span class="gridchamp-info-row"><label class="row-element">' . esc_html__( 'View Demos', 'walker-core' ) . ': </label><a class="button alignright" href="' . esc_url( 'https://walkerwp.com/' ) . '" target="_blank">' . esc_html__( 'Click Here', 'walker-core' ) . '</a></span>';
	    $gridchamp_info .= '<span class="gridchamp-info-row"><label class="row-element">' . esc_html__( 'Need Support', 'walker-core' ) . ': </label><a class="button alignright" href="' . esc_url( 'https://walkerwp.com/support/' ) . '" target="_blank">' . esc_html__( 'Click Here', 'walker-core' ) . '</a></span>';

	    $gridchamp_info.='<span class="gridchamp-info-row premium-row">'.esc_html__('Premium Features','walker-core').'</span>';

	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('Find out more premium features of the theme.').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('5 Header Layout','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('3 More Slider','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('3 Custom Widgets','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('Footer Customizatio','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('2 Portfolio Layout','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('3 Testimonials Layout','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('2 Team Layout','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('FAQs Layout','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('Brand Logo Section','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('Notification Bar at Top','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('Scroll Top Option','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('Sticky Menu/Header','walker-core').'</span>';
	    $gridchamp_info.='<span class="gridchamp-info-row"><label class="row-element">'.esc_html__('Home Page Section Re-order','walker-core').'</span>';

	    $gridchamp_info .= '<span class="gridchamp-info-row"><label class="row-element"> </label><a class="button gridchamop-pro-button" href="' . esc_url( 'https://walkerwp.com/gridchamp/' ) . '" target="_blank">' . esc_html__( 'View More Deatils', 'walker-core' ) . '</a></span>';
	    $gridchamp_info .= '<span class="gridchamp-info-row"><label class="row-element"> </label><a class="button gridchamop-pro-button" href="' . esc_url( 'https://walkerwp.com/gridchamp/' ) . '" target="_blank">' . esc_html__( 'Upgrade to Pro', 'walker-core' ) . '</a></span>';
	    



        $wp_customize->add_setting( 'gridchamp_info', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        ) );

        $wp_customize->add_control( new Walker_Core_Custom_Text( $wp_customize, 'gridchamp_info', array(
	        'section' => 'walker_core_promotion_options',
	        'label'   => $gridchamp_info,
	    ) ) );

	    
	
		
	}
  }
}
add_action( 'customize_register', 'walker_promotion_options_register' );