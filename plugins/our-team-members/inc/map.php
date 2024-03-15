<?php	

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * KingComposer Addon
 */

	if( defined('KC_VERSION') && function_exists('kc_add_map') ) {
	    kc_add_map(			    
	        array(  
	            'wpb-our-team-members' 		=> array(
	                'name' 					=> esc_html__('Team Members', 'our-team-members'),
	                'description'			=> esc_html__('Team Members addon.', 'our-team-members'),
	                'icon' 					=> 'fa-heart-o',
	                'category'				=> 'WPB OTM',
	                'description'     		=> esc_html__( 'Team Members grid. Add Team Member to the Team Members post type.', 'our-team-members' ),
	                'params' 				=> array(
						array(
							'label'   		=> esc_html__('Skin', 'our-team-members'),
							'name' 			=> 'skin',	
							'type' 			=> 'select',  
							'admin_label'	=> true,
							'options' 		=> array(    
								'default' 	=> esc_html__( 'Default', 'our-team-members' ),
								'one' 		=> esc_html__( 'One', 'our-team-members' ),
								'two' 		=> esc_html__( 'Two', 'our-team-members' ),
								'three' 	=> esc_html__( 'Three', 'our-team-members' )
							),
							'value' 		=> 'default'
						),	                	
						array(
						    'label' 		=> esc_html__('Number of Team Members', 'our-team-members'),
						    'name' 			=> 'number_of_member',
						    'type' 			=> 'number_slider',
						    'options' 		=> array(
						        'min' 		=> esc_html__('1', 'our-team-members'),
						        'max'		=> esc_html__('30', 'our-team-members'),
						        'show_input'=> true
						    ),
						    'value'         => '4',
						    'description' 	=> esc_html__('Number of team members to show. Default: 4.', 'our-team-members'),
						    'admin_label'   => true
						),
						array(
							'label'   		=> esc_html__('How many words to show in description?', 'our-team-members'),
							'name' 			=> 'excerpt_length',	
							'type' 			=> 'number',  
							'value' 		=> 20,
							'description'	=> esc_html__('Default 20 words.', 'our-team-members'),
							'admin_label'	=> true
						),
						array(
							'label'   		=> esc_html__('Columns', 'our-team-members'),
							'name' 			=> 'column',	
							'type' 			=> 'select',  
							'admin_label'	=> true,
							'options' 		=> array(    
								'6' 		=> esc_html__( '6 Columns', 'our-team-members' ),
								'4' 		=> esc_html__( '4 Columns', 'our-team-members' ),
								'3' 		=> esc_html__( '3 Columns', 'our-team-members' ),
								'2' 		=> esc_html__( '2 Columns', 'our-team-members' ),
								'1' 		=> esc_html__( '1 Columns', 'our-team-members' )
							),
							'value' 		=> '4', 
							'description'	=> esc_html__('Default 4 columns.', 'our-team-members')
						),
						array(
							'label'   		=> esc_html__('Order by', 'our-team-members'),
							'name' 			=> 'orderby',	
							'type' 			=> 'select',  
							'admin_label' 	=> true,
							'options' 		=> array(    
								'date' 				=> esc_html__('Date', 'our-team-members'),
								'menu_order'		=> esc_html__('Menu Order', 'our-team-members'),
								'title' 			=> esc_html__('Title', 'our-team-members'),
								'id' 				=> esc_html__('ID', 'our-team-members'),
								'last_modified' 	=> esc_html__('Last modified', 'our-team-members'),
								'rand' 			=> esc_html__('Random', 'our-team-members')
							),
							'value' 		=> 'date', 
							'description'	=> esc_html__('Team members orderby.', 'our-team-members')
						),
						array(
							'label'   		=> esc_html__('Order', 'our-team-members'),
							'name' 			=> 'order',
							'type' 			=> 'select',  
							'admin_label' 	=> true,
							'options' 		=> array(    
								'DESC' 		=> esc_html__('Descending', 'our-team-members'),
								'ASC' 		=> esc_html__('Ascending', 'our-team-members')
							),
							'value' 		=> 'DESC', 
							'description' 	=> esc_html__('Team members order.', 'our-team-members')
						),
						array(
							'label'  		=> esc_html__('Specific Category', 'our-team-members'),
							'name' 		   	=> 'member_categories',
							'type'        	=> 'text',
							'description'  	=> esc_html__( 'You can put comma separated team member\'s category id for showing those specific members only.', 'our-team-members' ),
							'admin_label'  	=> true
						),
						array(
							'label'  		=> esc_html__('Extra class', 'our-team-members'),
							'name' 		   	=> 'x_class',
							'type'        	=> 'text',
							'description'  	=> esc_html__( 'Extra CSS class.', 'our-team-members' ),
							'admin_label'  	=> true
						),
	                )
	            ),	 
	        ) 
	    );
	}



/**
 * Visual Composer Addon
 */


    if ( function_exists( 'vc_add_param' ) ) {

		vc_add_shortcode_param( 'numberfield', 'wpb_otm_number_param_settings_field' );
		/**
         * VC Params Number
         */
		function wpb_otm_number_param_settings_field( $settings, $value ) {
			return '<div class="number_param_block">'
			.'<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
			esc_attr( $settings['param_name'] ) . ' ' .
			esc_attr( $settings['type'] ) . '_field" type="number" value="' . esc_attr( $value ) . '" />' .
			'</div>';
		}


        /**
         * WPB Our Team Member Addon(VisualComposer)
         */     
        vc_map( array(        
            'name'            => esc_html__( 'Team Members', 'our-team-members' ),
            'base'            => 'wpb-our-team-members',
            'icon'            => 'xt-vc-icon',
            'category'        => esc_html__( 'WPB OTM', 'our-team-members' ),
            'wrapper_class'   => 'clearfix',
            'description'     => esc_html__( 'Team Members grid. Add Team Member to the Team Members post type.', 'our-team-members' ),
            'params'          => array(           
                array(
                    'type'              => 'dropdown',
                    'heading'           => esc_html__( 'Skin', 'our-team-members' ),
                    'param_name'        => 'skin',
                    'value'             => array(
                        esc_html__( 'Default', 'our-team-members' ) => 'default',
                        esc_html__( 'One', 'our-team-members' )     => 'one',
                        esc_html__( 'Two', 'our-team-members' )     => 'two',
                        esc_html__( 'Three', 'our-team-members' )   => 'three'
                    ),
                    'admin_label'       => true
                ),
                array(
                    'type'              => 'numberfield',
                    'heading'           => esc_html__( 'Number of Team Members', 'our-team-members' ),
                    'param_name'        => 'number_of_member',
                    'value'             => '4',
                    'description'       => esc_html__( 'Number of Volanteers to show. Default: 4.', 'our-team-members' ),
                    'admin_label'       => true,
                ),
                array(
                    'type'              => 'numberfield',
                    'heading'           => esc_html__( 'How many words to show in description?', 'our-team-members' ),
                    'param_name'        => 'excerpt_length',
                    'value'             => 20,
                    'description'       => esc_html__( 'Default 20 words.', 'our-team-members' ),
                    'admin_label'       => true,
                ),
                array(
                    'type'              => 'dropdown',
                    'heading'           => esc_html__( 'Columns', 'our-team-members' ),
                    'param_name'        => 'column',
                    'value'             => array(
                        esc_html__( 'Select grid columns', 'our-team-members' ) => '',
                        esc_html__( '6 Columns', 'our-team-members' )           => 6,
                        esc_html__( '4 Columns', 'our-team-members' )           => 4,
                        esc_html__( '3 Columns', 'our-team-members' )           => 3,
                        esc_html__( '2 Columns', 'our-team-members' )           => 2,
                        esc_html__( '1 Column', 'our-team-members' )            => 1
                    ),
                    'description'       => esc_html__( 'Default 4 columns', 'our-team-members' ),
                    'admin_label'       => true,
                    'std'               => 4
                ),
                array(
                    'type'              => 'dropdown',
                    'heading'           => esc_html__( 'Order by', 'our-team-members' ),
                    'param_name'        => 'orderby',
                    'value'             => array(
                        esc_html__( 'Menu Order', 'our-team-members' )    => 'menu_order',
                        esc_html__( 'Date', 'our-team-members' )          => 'date',
                        esc_html__( 'Title', 'our-team-members' )         => 'title',
                        esc_html__( 'ID', 'our-team-members' )            => 'ID',
                        esc_html__( 'Last modified', 'our-team-members' ) => 'modified',
                        esc_html__( 'Random', 'our-team-members' )        => 'rand'
                    ),
                    'std'               => 'date',
                    'description'       => esc_html__( 'Team members orderby.', 'our-team-members' ),
                    'admin_label'       => true
                ),
                array(
                    'type'              => 'dropdown',
                    'heading'           => esc_html__( 'Order', 'our-team-members' ),
                    'param_name'        => 'order',
                    'value'             => array(
                        esc_html__( 'Ascending', 'our-team-members' )  => 'ASC',
                        esc_html__( 'Descending', 'our-team-members' ) => 'DESC'
                    ),
                    'std'               => 'DESC',
                    'description'       => esc_html__( 'Team members order.', 'our-team-members' ),
                    'admin_label'       => true
                ),          
                array(
                    'type'              => 'textfield',
                    'heading'           => esc_html__( 'Specific Category', 'our-team-members' ),
                    'param_name'        => 'member_categories',
                    'value'             => '',
                    'description'       => esc_html__( 'You can put comma separated team member\'s category id for showing those specific members only.', 'our-team-members' ),
                    'admin_label'       => true
                ),       
                array(
                    'type'              => 'textfield',
                    'heading'           => esc_html__( 'Extra class', 'our-team-members' ),
                    'param_name'        => 'x_class',
                    'value'             => '',
                    'description'       => esc_html__( 'Extra CSS class.', 'our-team-members' ),
                    'admin_label'       => true
                ),             
            )
        ) );

    }





/**
 * Elementor addon
 */

add_action( 'elementor/init', 'wpb_otm_add_elementor_category' );
add_action( 'elementor/widgets/widgets_registered', 'wpb_otm_add_elementor_widgets' );
add_action('elementor/frontend/after_register_scripts', 'wpb_otm_elementor_register_scripts', 10);
add_action('elementor/frontend/after_register_styles', 'wpb_otm_elementor_register_style', 10);
add_action('elementor/frontend/after_enqueue_styles', 'wpb_otm_elementor_enqueue_frontend_styles', 10);


/**
 * Add the Category WPB Widgets.
 */

if ( !function_exists( 'wpb_otm_add_elementor_category' ) ) {
  function wpb_otm_add_elementor_category() {
    \Elementor\Plugin::instance()->elements_manager->add_category(
      'wpb-elementor-widgets',
      array(
        'title' => __( 'WPB Widgets', 'our-team-members' ),
        'icon'  => 'fa fa-plug',
      ),
      1
    );
  }
}


/**
 * Require and instantiate Elementor Widgets.
 *
 * @param $widgets_manager
 */

if ( !function_exists( 'wpb_otm_add_elementor_widgets' ) ) {
  function wpb_otm_add_elementor_widgets( $widgets_manager ) {

    require_once WPB_OTM_PATH . 'inc/elementor-our-team-widget.php';
    
    // Pricing table
    $widget = new Elementor\WPB_Elementor_Widget_Our_Team_Members();
    $widgets_manager->register_widget_type( $widget );
  }
}