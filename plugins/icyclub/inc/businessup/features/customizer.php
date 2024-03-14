<?php


if ( ! function_exists( 'icycp_businessup_slider_customize_register' ) ) :
function icycp_businessup_slider_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

class Icycp_businessup_Toggle_Switch_Custom_control extends WP_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'toogle_switch';
	/**
	 * Enqueue our scripts and styles
	 */
	/**
	 * Render the control in the customizer
	 */
	public function render_content(){
	?>
		<div class="toggle-switch-control">
			<div class="toggle-switch">
				<input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>>
				<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
					<span class="toggle-switch-inner"></span>
					<span class="toggle-switch-switch"></span>
				</label>
			</div>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if( !empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
		</div>
	<?php
	}
}

class bussinessup_upgrade_notice extends WP_Customize_Control {
	public function render_content() { ?>
		<h3 class="customizer_businessup_upgrade_section" style="display: none;">
<?php _e('To add More Feature? Then','icyclub'); ?><a href="<?php echo esc_url( 'https://themeansar.com/themes/businessup-pro' ); ?>" target="_blank">
			<?php _e('Upgrade to Pro','icyclub'); ?> </a>  
		</h3>
	<?php
	}
}


/* Slider Section */
	$wp_customize->add_section( 'slider_section' , array(
		'title'      => __('Slider Settings', 'icyclub'),
		'panel'  => 'homepage_setting',
		'priority'   => 1,
   	) );
		
		$wp_customize->add_setting( 'businessup_slider_enable',
		   array(
			  'default' => 1 ,
			  'transport' => 'refresh',
                'capability' => 'edit_theme_options',
		   )
		);
		 
		$wp_customize->add_control( new Icycp_businessup_Toggle_Switch_Custom_control( $wp_customize, 'businessup_slider_enable',
		   array(
			  'label' => esc_html__( 'Slider Enable/Disable' ),
			  'section' => 'slider_section',
			  'type'    => 'radio',
			   'choices'=>array( 0 =>'Off' , 1=>'On'),
		   )
		) );


		//Slider Background Overlay Color
		$wp_customize->add_setting( 'businessup_slider_overlay_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0, 0,0, 0.4)',
            ) );	
            
            $wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'businessup_slider_overlay_color', array(
               'label'      => __('Image Overlay Color','businessup' ),
                'palette' => true,
                'section' => 'slider_section')
            ) );

		
			if ( class_exists( 'businessup_Repeater_Control' ) ) {
				$wp_customize->add_setting( 'businessup_slider_content', array(
				) );
	
				$wp_customize->add_control( new Businessup_Repeater_Control( $wp_customize, 'businessup_slider_content', array(
					'label'                             => esc_html__( 'Slider Content', 'bussinessup' ),
					'section'                           => 'slider_section',
					'add_field_label'                   => esc_html__( 'Add new slider', 'bussinessup' ),
					'item_name'                         => esc_html__( 'Slide', 'bussinessup' ),
					'customizer_repeater_title_control' => true,
					'customizer_repeater_text_control'  => true,
					'customizer_repeater_button_text_control' => true,
					'customizer_repeater_link_control'  => true,
					'customizer_repeater_image_control' => true,
					'customizer_repeater_checkbox_control' => true,
					) ) );
			}


			
		

			$bussinessup_slider_content_default_value_control = $wp_customize->get_setting( 'businessup_slider_content' );
				if ( ! empty( $bussinessup_slider_content_default_value_control ) ) 
				{
					$widget_default = get_option('widget_businessup_slider-widget') ;

					$defaults = array(
						array(
						'slider_title'      => esc_html__( 'We help from our fleet Send it anywhere', 'businessup' ),
						'slider_desc'       => esc_html__( 'we bring the proper people along to challenge established thinking and drive transformation.', 'businessup' ),
						'btnone'      => __('Read More','businessup'),
						'btnonelink'       => '#',
						'image_uri'  => ICYCP_PLUGIN_URL .'inc/businessup/images/slider/slider1.jpg',
						'open_btnone_new_window' => 'no',
						),
		
						array(
							'slider_title'      => esc_html__( 'Transport your goods Around the World', 'businessup' ),
							'slider_desc'       => esc_html__( 'we bring the proper people along to challenge established thinking and drive transformation.', 'businessup' ),
							'btnone'      => __('Read More','businessup'),
							'btnonelink'       => '#',
							'image_uri'  => ICYCP_PLUGIN_URL .'inc/businessup/images/slider/slider2.jpg',
							'open_btnone_new_window' => 'no',
							),
		
						array(
							'slider_title'      => esc_html__( 'Transport your goods Around the World', 'businessup' ),
							'slider_desc'       => esc_html__( 'we bring the proper people along to challenge established thinking and drive transformation.', 'businessup' ),
							'btnone'      => __('Read More','businessup'),
							'btnonelink'       => '#',
							'image_uri'  => ICYCP_PLUGIN_URL .'inc/businessup/images/slider/slider3.jpg',
							'open_btnone_new_window' => 'no',
							),
		
					);

					
					$service_widget_data = get_option('widget_businessup_slider-widget', $defaults );

					$arr = array(); //create empty array

					$i = 0;

					foreach(array_reverse($service_widget_data) as $widget_data) {

						if($i == 3){
							break;
						}
						$i++;

						if(isset($service_widget_data['_multiwidget'])){


							if($widget_data == $service_widget_data['_multiwidget']){


							}else{
	
								$arr[] = array(
									'title' => isset($widget_data['slider_title']) ? $widget_data['slider_title'] : 0,
									'text' => isset($widget_data['slider_desc']) ? $widget_data['slider_desc'] : 0,
									'button_text' => isset($widget_data['btnone']) ? $widget_data['btnone'] : 0,
									'link' => isset($widget_data['btnonelink']) ? $widget_data['btnonelink'] : 0,
									'image_url' => isset($widget_data['image_uri']) ? $widget_data['image_uri'] : 0,
									'open_new_tab' => isset($widget_data['open_btnone_new_window']) ? $widget_data['open_btnone_new_window'] : 0,
								);//assign each sub-array to the newly created array
								
							}

						}else{

							$arr[] = array(
								'title' => isset($widget_data['slider_title']) ? $widget_data['slider_title'] : 0,
								'text' => isset($widget_data['slider_desc']) ? $widget_data['slider_desc'] : 0,
								'button_text' => isset($widget_data['btnone']) ? $widget_data['btnone'] : 0,
								'link' => isset($widget_data['btnonelink']) ? $widget_data['btnonelink'] : 0,
								'image_url' => isset($widget_data['image_uri']) ? $widget_data['image_uri'] : 0,
								'open_new_tab' => isset($widget_data['open_btnone_new_window']) ? $widget_data['open_btnone_new_window'] : 0,
							);

						}

						
						
					} 
				$bussinessup_slider_content_default_value_control ->default = json_encode( $arr);

			}else{ }

			
			
			$wp_customize->add_setting( 'bussinessup_slider_upgrade_to_pro', array(
				'capability'			=> 'edit_theme_options',
			));
			$wp_customize->add_control(
				new bussinessup_upgrade_notice(
				$wp_customize,
				'bussinessup_slider_upgrade_to_pro',
					array(
						'section'				=> 'slider_section',
						'settings'				=> 'bussinessup_slider_upgrade_to_pro',
					)
				)
			);
		
		
		
}

add_action( 'customize_register', 'icycp_businessup_slider_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_businessup_register_slider_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'slider_image', array(
		'selector'            => '.businessup-slider-warraper .item figure',
		'settings'            => 'slider_image',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'businessup_slider_title', array(
		'selector'            => '#ta-slider .slide-caption h1',
		'settings'            => 'businessup_slider_title',
		'render_callback'  => 'icycp_businessup_slider_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'businessup_slider_discription', array(
		'selector'            => '#ta-slider .slide-caption .description p',
		'settings'            => 'businessup_slider_discription',
		'render_callback'  => 'icycp_businessup_slider_iscription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'businessup_slider_btn_txt', array(
		'selector'            => '.slide-caption .btn-tislider',
		'settings'            => 'businessup_slider_btn_txt',
		'render_callback'  => 'icycp_businessup_slider_btn_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_businessup_register_slider_section_partials' );


function icycp_businessup_slider_title_render_callback() {
	return get_theme_mod( 'businessup_slider_title' );
}

function icycp_businessup_slider_iscription_render_callback() {
	return get_theme_mod( 'businessup_slider_discription' );
}

function icycp_businessup_slider_btn_render_callback() {
	return get_theme_mod( 'businessup_slider_btn_txt' );
}


if ( ! function_exists( 'icycp_businessup_service_customize_register' ) ) :
function icycp_businessup_service_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Services section */
	$wp_customize->add_section( 'services_section' , array(
		'title'      => __('Service Settings', 'icyclub'),
		'panel'  => 'homepage_setting',
		'priority'   => 3,
	) );
		
		$wp_customize->add_setting( 'businessup_service_enable',
		   array(
			  'default' => 1 ,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_businessup_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_businessup_Toggle_Switch_Custom_control( $wp_customize, 'businessup_service_enable',
		   array(
			  'label' => esc_html__( 'Service Enable/Disable' ),
			  'section' => 'services_section',
			  'choices'=>array( 1 =>true , 0 =>false),
		   )
		) );

		//Service Overlay 
		$wp_customize->add_setting(
			'service_overlay_section_color', array( 'sanitize_callback' => '',
			
		) );
		
		$wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'service_overlay_section_color', array(
		   'label'      => __('Overlay Color', 'businessup' ),
			'palette' => true,
			'section' => 'services_section')
		) );

		//Service text color setting
		$wp_customize->add_setting(
			'businessup_service_text_color', array( 'sanitize_callback' => 'sanitize_hex_color',
			
		) );
		
		$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'businessup_service_text_color', array(
		   'label'      => __('Text Color', 'businessup' ),
			'palette' => true,
			'section' => 'services_section')
		) );

		//Service Title setting
		$wp_customize->add_setting(
			'businessup_service_title', array(
			'capability'     => 'edit_theme_options',
			'default' => __('Why We Best in Business Services','icyclub'),
			'transport'         => $selective_refresh,
		) );	
		$wp_customize->add_control( 
			'businessup_service_title',array(
			'label'   => __('Title','businessup'),
			'section' => 'services_section',
			'type' => 'text',
		) );

		//Service SubTitle setting
		$wp_customize->add_setting(
			'businessup_service_subtitle', array(
			'capability'     => 'edit_theme_options',
			'default' => __(' Lorem ipsum dolor sit amet, consectetur adipiscing elit Pull in ten extra bodies to help.','icyclub'),
			'transport'         => $selective_refresh,
		) );  
		$wp_customize->add_control( 'businessup_service_subtitle', array(
			'label'   => __('Description','businessup'),
			'section' => 'services_section',
			'type' => 'textarea',
		) );

		
		if ( class_exists( 'businessup_Repeater_Control' ) ) {
			$wp_customize->add_setting( 'businessup_service_content', array(
			) );

			$wp_customize->add_control( new Businessup_Repeater_Control( $wp_customize, 'businessup_service_content', array(
				'label'                             => esc_html__( 'Service Content', 'bussinessup' ),
				'section'                           => 'services_section',
				'add_field_label'                   => esc_html__( 'Add new Service', 'bussinessup' ),
				'item_name'                         => esc_html__( 'Service', 'bussinessup' ),
				'customizer_repeater_icon_control' => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_button_text_control' => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_checkbox_control' => true,
				) ) );
		}

		$bussinessup_service_content_default_value_control = $wp_customize->get_setting( 'businessup_service_content' );
				if ( ! empty( $bussinessup_service_content_default_value_control ) ) 
				{

					$defaults = array(
						array(
						'fa_icon' => 'fa fa-thumbs-up',	
						'service_title'      => esc_html__( 'Why We Best in Business Services', 'businessup' ),
						'service_desc'       => esc_html__( 'laoreet ipsum eu laoreet. ugiignissimat Vivamus.', 'businessup' ),
						'btnmore'      => __('Read More','businessup'),
						'btnlink'       => '#',
						'open_new_window' => 'no',
						),

						array(
							'fa_icon' => 'fa fa-bank',	
							'service_title'      => esc_html__( 'Business Planning', 'businessup' ),
							'service_desc'       => esc_html__( 'laoreet Pellentesque molestie laoreet laoreet.', 'businessup' ),
							'btnmore'      => __('Read More','businessup'),
							'btnlink'       => '#',
							'open_new_window' => 'no',
						),
				
						array(
							'fa_icon' => 'fa fa-bank',	
							'service_title'      => esc_html__( 'Financial Planning', 'businessup' ),
							'service_desc'       => esc_html__( 'laoreet Pellentesque molestie laoreet laoreet.', 'businessup' ),
							'btnmore'      => __('Read More','businessup'),
							'btnlink'       => '#',
							'open_new_window' => 'no',
						),		
		

		
					);


					$service_widget_data = get_option('widget_businessup_service_widget', $defaults );

					$arr = array(); //create empty array

					$i = 0;

					foreach(array_reverse($service_widget_data) as $widget_data) {

						if($i == 3){
							break;
						}
						if(isset($service_widget_data['_multiwidget'])){

							if($widget_data == $service_widget_data['_multiwidget']){


							}else{
	
								$arr[] = array(
									'icon_value' => isset($widget_data['fa_icon']) ? $widget_data['fa_icon'] : 0,
									'title' => isset($widget_data['service_title']) ? $widget_data['service_title'] : 0,
									'text' => isset($widget_data['service_desc']) ? $widget_data['service_desc'] : 0,
									'button_text' => isset($widget_data['btnmore']) ? $widget_data['btnmore'] : 0,
									'link' => isset($widget_data['btnlink']) ? $widget_data['btnlink'] : 0,
									'image_url' => isset($widget_data['image_uri']) ? $widget_data['image_uri'] : 0,
									'open_new_tab' => isset($widget_data['open_new_window']) ? $widget_data['open_new_window'] : 0,
								);//assign each sub-array to the newly created array
	
							}
							
						}else{

							$arr[] = array(
								'icon_value' => isset($widget_data['fa_icon']) ? $widget_data['fa_icon'] : 0,
								'title' => isset($widget_data['service_title']) ? $widget_data['service_title'] : 0,
								'text' => isset($widget_data['service_desc']) ? $widget_data['service_desc'] : 0,
								'button_text' => isset($widget_data['btnmore']) ? $widget_data['btnmore'] : 0,
								'link' => isset($widget_data['btnlink']) ? $widget_data['btnlink'] : 0,
								'image_url' => isset($widget_data['image_uri']) ? $widget_data['image_uri'] : 0,
								'open_new_tab' => isset($widget_data['open_new_window']) ? $widget_data['open_new_window'] : 0,
							);

						}
						
						$i++;
						
					} 
				$bussinessup_service_content_default_value_control ->default = json_encode( $arr);

				}

				$wp_customize->add_setting( 'bussinessup_service_upgrade_to_pro', array(
					'capability'			=> 'edit_theme_options',
				));
				$wp_customize->add_control(
					new bussinessup_upgrade_notice(
					$wp_customize,
					'bussinessup_service_upgrade_to_pro',
						array(
							'section'				=> 'services_section',
							'settings'				=> 'bussinessup_service_upgrade_to_pro',
						)
					)
				);

		
		
}

add_action( 'customize_register', 'icycp_businessup_service_customize_register' );
endif;


/**
 * Selective refresh for service section
 */
function icycp_businessup_register_service_section_partials( $wp_customize ){

	//Service
	$wp_customize->selective_refresh->add_partial( 'businessup_service_title', array(
		'selector'            => '#service .businessup-heading h3',
		'settings'            => 'businessup_service_title',
		'render_callback'  => 'icycp_businessup_service_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'businessup_service_subtitle', array(
		'selector'            => '#service .businessup-heading p',
		'settings'            => 'businessup_service_subtitle',
		'render_callback'  => 'icycp_businessup_service_discription_render_callback',
	
	) );
	
}

add_action( 'customize_register', 'icycp_businessup_register_service_section_partials' );


function icycp_businessup_service_title_render_callback() {
	return get_theme_mod( 'businessup_service_title' );
}

function icycp_businessup_service_discription_render_callback() {
	return get_theme_mod( 'businessup_service_subtitle' );
}


//Callout
if ( ! function_exists( 'icycp_businessup_callout_customize_register' ) ) :
function icycp_businessup_callout_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Slider Section */
	$wp_customize->add_section( 'home_callout_section' , array(
		'title'      => __('Callout Settings', 'businessup'),
		'panel'  => 'homepage_setting',
		'priority'   => 2,
   	) );
		
		// Enable Callout
		$wp_customize->add_setting( 'businessup_callout_enable',
		   	array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_businessup_switch_sanitization'
		    )
		);
		 
		$wp_customize->add_control( new Icycp_businessup_Toggle_Switch_Custom_control( $wp_customize, 'businessup_callout_enable',
		   	array(
			  'label' => esc_html__( 'Callout Enable/Disable' ),
			  'section' => 'home_callout_section',
		    )
		) );

		//Callout background Image
		$wp_customize->add_setting( 'businessup_callout_background',array('default' => ICYCP_PLUGIN_URL .'inc/businessup/images/callout/callout-back.jpg',
		'sanitize_callback' => 'esc_url_raw'));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'businessup_callout_background',
				array(
					'type'        => 'upload',
					'label' => __('Image','businessup'),
					'settings' =>'businessup_callout_background',
					'section' => 'home_callout_section',
					
				)
			)
		);
		
		//Callout Background Overlay Color
		$wp_customize->add_setting( 'businessup_callout_overlay_color', array(
			'default' => 'rgba(0,41,84,0.8)',
            ) 
		);	
            
		$wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'businessup_callout_overlay_color', array(
			'label'      => __('Image Overlay Color','businessup' ),
			'palette' => true,
			'section' => 'home_callout_section')
		) );

		//Callout Text Color setting
		$wp_customize->add_setting(
			'businessup_callout_text_color', array( 'sanitize_callback' => 'sanitize_hex_color',
		) );
		
		$wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'businessup_callout_text_color', array(
		    'label'   => __('Text Color', 'businessup' ),
			'palette' => true,
			'section' => 'home_callout_section')
		) );
		
		
		// callout title
		$wp_customize->add_setting( 'businessup_callout_title',array(
		'default' => __('Trusted By Over 10,000 Worldwide Businesses. Try Today!','businessup'),
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'businessup_callout_title',array(
		'label'   => __('Title','businessup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));	
		
		//callout description
		$wp_customize->add_setting( 'businessup_callout_description',array(
		'default' => 'We must explain to you how all this mistaken idea of denouncing pleasure',
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'businessup_callout_description',array(
		'label'   => __('Description','businessup'),
		'section' => 'home_callout_section',
		'type' => 'textarea',
		));
		
		
		// callout button text
		$wp_customize->add_setting( 'businessup_callout_button_one_label',array(
		'default' => __('Get Started Now!','businessup'),
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'businessup_callout_button_one_label',array(
		'label'   => __('Button Text','businessup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));
		
		// Callout button link
		$wp_customize->add_setting( 'businessup_callout_button_one_link',array(
		'default' => '#',
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'businessup_callout_button_one_link',array(
		'label'   => __('Button Link','businessup'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));
		
		// Callout button target
		$wp_customize->add_setting(
		'businessup_callout_button_one_target', 
			array(
			'default'        => false,
			'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		));
		$wp_customize->add_control('businessup_callout_button_one_target', array(
			'label'   => __('Open link in a new tab', 'businessup'),
			'section' => 'home_callout_section',
			'type' => 'checkbox',
		));
		
		//Callout Button Two Label Setting	
		    $wp_customize->add_setting(
		    	'businessup_callout_button_two_label', array(
				'default' => __('Read More','businessup'),
		        'capability' => 'edit_theme_options',
		        'sanitize_callback' => 'sanitize_text_field',
		    ) );	
		    $wp_customize->add_control( 
		    	'businessup_callout_button_two_label', array(
		    	'label' => __('Button Text','businessup'),
		    	'section' => 'home_callout_section',
		    	'type' => 'text',
		    ) );	

		    //Callout Button Two Link Setting
		    $wp_customize->add_setting(
		    	'businessup_callout_button_two_link', array(
		        'capability' => 'edit_theme_options',
		        'sanitize_callback' => 'esc_url_raw',
		    ) );	
		    $wp_customize->add_control( 
		    	'businessup_callout_button_two_link', array(
		    	'label' => __('Button Link','businessup'),
		    	'type' => 'text',
		    	'section' => 'home_callout_section',
		    ) );	

		    //Callout Button Two Target Setting
		    $wp_customize->add_setting(
		    	'businessup_callout_button_two_target', array(
		        'capability' => 'edit_theme_options',
		        'sanitize_callback' => 'businessup_homepage_sanitize_checkbox',
		    ) );	
		    $wp_customize->add_control( 
		    	'businessup_callout_button_two_target', array(
		    	'label' => __('Open link in a new tab','businessup'),
		    	'section' => 'home_callout_section',
		    	'type' => 'checkbox',
		    ) );
}

add_action( 'customize_register', 'icycp_businessup_callout_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_businessup_register_callout_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'businessup_callout_title', array(
		'selector'            => '.businessup-callout .businessup-callout-inner h3',
		'settings'            => 'businessup_callout_title',
		'render_callback'  => 'icycp_businessup_callout_section_title_render_callback',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'businessup_callout_description', array(
		'selector'            => '.businessup-callout .businessup-callout-inner p',
		'settings'            => 'businessup_callout_description',
		'render_callback'  => 'icycp_businessup_callout_section_desc_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'businessup_callout_button_one_label', array(
		'selector'            => '.ta-callout a',
		'settings'            => 'businessup_callout_button_one_label',
		'render_callback'  => 'icycp_businessup_callout_btn_txt_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_businessup_register_callout_section_partials' );


function icycp_businessup_callout_section_title_render_callback() {
	return get_theme_mod( 'businessup_callout_title' );
}

function icycp_businessup_callout_section_desc_render_callback() {
	return get_theme_mod( 'businessup_callout_description' );
}

function icycp_businessup_callout_btn_txt_render_callback() {
	return get_theme_mod( 'businessup_callout_button_one_label' );
}

//Project Section
if ( ! function_exists( 'icycp_businessup_calltoaction_customizer' ) ) :
function icycp_businessup_calltoaction_customizer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	/* project Section */
	$wp_customize->add_section( 'calltoaction_section' , array(
			'title'      => __('Calltoaction Settings', 'icyclub'),
			'panel'  => 'homepage_setting',
			'priority'   => 4,
		) );
		
		$wp_customize->add_setting( 'calltoaction_section_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_businessup_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_businessup_Toggle_Switch_Custom_control( $wp_customize, 'calltoaction_section_enable',
		   array(
			  'label' => esc_html__( 'Calltoaction Enable/Disable' ),
			  'section' => 'calltoaction_section'
		   )
		) );

		$wp_customize->add_setting( 'businessup_calltoaction_overlay_color', array(
			'default' => 'rgba(0, 41, 84, 0.8)',
        ) );	
            
		$wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'businessup_calltoaction_overlay_color', array(
		'label'      => __('Overlay Color','businessup' ),
			'palette' => true,
			'section' => 'calltoaction_section')
		) );

		$wp_customize->add_setting( 'businessup_calltoaction_text_color', array(
			'sanitize_callback' => 'sanitize_hex_color',
			'default' => '#fff',
        ) );	
            
		$wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'businessup_calltoaction_text_color', array(
		'label'      => __('Text color','businessup' ),
			'palette' => true,
			'section' => 'calltoaction_section')
		) );
	
		// project section title
		$wp_customize->add_setting( 'businessup_calltoaction_title',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'default' => __('Make A Difference With Expert Team','icyclub'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'businessup_calltoaction_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'calltoaction_section',
		'type' => 'text',
		));	
		
		//project section discription
		$wp_customize->add_setting( 'businessup_calltoaction_subtitle',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'default' => 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'businessup_calltoaction_subtitle',array(
		'label'   => __('Description','icyclub'),
		'section' => 'calltoaction_section',
		'type' => 'textarea',
		));
		
		
		// callout button text
		$wp_customize->add_setting( 'businessup_calltoaction_button_one_label',array(
		'default' => __('Lets Start','businessup'),
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'businessup_calltoaction_button_one_label',array(
		'label'   => __('Button Text','businessup'),
		'section' => 'calltoaction_section',
		'type' => 'text',
		));
		
		// Callout button link
		$wp_customize->add_setting( 'businessup_calltoaction_button_one_link',array(
		'default' => '#',
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'businessup_calltoaction_button_one_link',array(
		'label'   => __('Button Link','businessup'),
		'section' => 'calltoaction_section',
		'type' => 'text',
		));
		
		// Callout button target
		$wp_customize->add_setting(
		'businessup_calltoaction_button_one_target', 
			array(
			'default'        => false,
			'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		));
		$wp_customize->add_control('businessup_calltoaction_button_one_target', array(
			'label'   => __('Open link in a new tab', 'businessup'),
			'section' => 'calltoaction_section',
			'type' => 'checkbox',
		));
		
		

}		
add_action( 'customize_register', 'icycp_businessup_calltoaction_customizer' );
endif;

/**
 * Add selective refresh for project section.
 */
function icycp_businessup_register_calltoaction_section_partials( $wp_customize ){

	
	//calltoaction section
	$wp_customize->selective_refresh->add_partial( 'businessup_calltoaction_title', array(
		'selector'            => '.ta-calltoaction-box-info h5',
		'settings'            => 'businessup_calltoaction_title',
		'render_callback'  => 'icycp_businessup_businessup_calltoaction_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'businessup_calltoaction_subtitle', array(
		'selector'            => '.ta-calltoaction-box-info p',
		'settings'            => 'businessup_calltoaction_subtitle',
		'render_callback'  => 'icycp_businessup_businessup_calltoaction_subtitle_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'businessup_calltoaction_button_one_label', array(
		'selector'            => '.ta-calltoaction .btn-theme',
		'settings'            => 'businessup_calltoaction_button_one_label',
		'render_callback'  => 'icycp_businessup_calltoaction_section_button_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_businessup_register_calltoaction_section_partials' );

//Project Section
function icycp_businessup_businessup_calltoaction_title_render_callback() {
	return get_theme_mod( 'businessup_calltoaction_title' );
}

function icycp_businessup_businessup_calltoaction_subtitle_render_callback() {
	return get_theme_mod( 'businessup_calltoaction_subtitle' );
}

function icycp_businessup_calltoaction_section_button_render_callback() {
	return get_theme_mod( 'businessup_calltoaction_button_one_label' );
}




if ( ! function_exists( 'icycp_businessup_testimonial_customize_register' ) ) :
function icycp_businessup_testimonial_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

/* Testimonial Section */
	$wp_customize->add_section( 'testimonial_section' , array(
		'title'      => __('Testimonial Settings', 'icyclub'),
		'panel'  => 'homepage_setting',
		'priority'   => 7,
	) );
	
	// Enable testimonial section
	$wp_customize->add_setting( 'testimonial_section_enable',
		array(
			'default' => 1,
			'transport' => 'refresh',
			'sanitize_callback' => 'icycp_businessup_switch_sanitization'
		)
	);
		
	$wp_customize->add_control( new Icycp_businessup_Toggle_Switch_Custom_control( $wp_customize, 'testimonial_section_enable',
		array(
			'label' => esc_html__( 'Testimonial Enable/Disable' ),
			'section' => 'testimonial_section',
			'choices'=>array( 1 =>true , 0 =>false),
		)
	) );
	
	
	
	//Testimonial Background Image
	$wp_customize->add_setting( 'businessup_testimonials_background',array(
	'sanitize_callback' => 'esc_url_raw', 
	));
	
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'businessup_testimonials_background', array(
		'label'    => __( 'Background Image', 'icyclub' ),
		'section'  => 'testimonial_section',
		'settings' => 'businessup_testimonials_background',
	) ) );
	
	// Image overlay
	$wp_customize->add_setting( 'testimonial_bg_overlay_enable', array(
		'default' => true,
		'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control('testimonial_bg_overlay_enable', array(
		'label'    => __('Enable Overlay Image', 'icyclub' ),
		'section'  => 'testimonial_section',
		'type' => 'checkbox',
	) );
	
	
	//Testimonial Background Overlay Color
	$wp_customize->add_setting( 'businessup_testimonials_overlay_color', array(
		'sanitize_callback' => 'sanitize_text_field',
		'default' => '#f5f5f5',
	) );	
		
	$wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'businessup_testimonials_overlay_color', array(
		'label'      => __('Overlay Color','icyclub' ),
		'palette' => true,
		'section' => 'testimonial_section'
	)
	) );

	//Testimonial text Color
	$wp_customize->add_setting( 'businessup_testimonials_text_color', array(
		'sanitize_callback' => 'sanitize_text_field',
		) 
	);	 
	$wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'businessup_testimonials_text_color', array(
		'label'      => __('Text Color','icyclub' ),
		'palette' => true,
		'section' => 'testimonial_section')
	) ); 
	// testimonial section title
	$wp_customize->add_setting( 'businessup_testimonials_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Our Clients Says','businessup'),
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'businessup_testimonials_title',array(
		'label'   => __('Title','businessup'),
		'section' => 'testimonial_section',
		'type' => 'text',
	));
	//testimonial section discription
	$wp_customize->add_setting( 'businessup_testimonials_subtitle',array(
		'capability'     => 'edit_theme_options',
		'default'=> 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
		'sanitize_callback' => 'icycp_businessup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
	));	
	$wp_customize->add_control( 'businessup_testimonials_subtitle',array(
		'label'   => __('Description','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'textarea',
	));

	if ( class_exists( 'businessup_Repeater_Control' ) ) {
		$wp_customize->add_setting( 'businessup_testimonial_content', array(
		) );

		$wp_customize->add_control( new Businessup_Repeater_Control( $wp_customize, 'businessup_testimonial_content', array(
			'label'                                => esc_html__( 'Testimonial Content', 'bussinessup' ),
			'section'                              => 'testimonial_section',
			'add_field_label'                      => esc_html__( 'Add new Testimonial', 'bussinessup' ),
			'item_name'                            => esc_html__( 'Testimonial', 'bussinessup' ),
			'customizer_repeater_title_control'    => true,
			'customizer_repeater_subtitle_control' =>true,
			'customizer_repeater_text_control'     => true,
			'customizer_repeater_designation_control' => true,
			'customizer_repeater_image_control'    => true,
			) ) 
		);
	}

	$wp_customize->add_setting( 'bussinessup_testimonial_upgrade_to_pro', array(
		'capability'			=> 'edit_theme_options',
	));
	$wp_customize->add_control(
		new bussinessup_upgrade_notice(
		$wp_customize,
		'bussinessup_testimonial_upgrade_to_pro',
			array(
				'section'				=> 'testimonials_section',
				'settings'				=> 'bussinessup_testimonial_upgrade_to_pro',
			)
		)
	);
	$bussinessup_testimonial_content_default_value_control = $wp_customize->get_setting( 'businessup_testimonial_content' );
	if ( ! empty( $bussinessup_testimonial_content_default_value_control ) ) 
	{

		$defaults = array(
			array(
				'title'      => esc_html__( 'Professional Team', 'businessup' ),
				'text'       => esc_html__('Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium.', 'businessup' ),
				'subtitle'      => __('Ronald Thompson','businessup'),
				'designation'       => ' Developer',
				'image_url' => ICYCP_PLUGIN_URL .'/inc/businessup/images/testimonial/testi1.jpg',
			),

			array(
				'title'      => esc_html__( 'Professional Team', 'businessup' ),
				'text'       => esc_html__('Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium.', 'businessup' ),
				'subtitle'      => __('Laura Walker','businessup'),
				'designation'       => ' Co-Founder',
				'image_url' => ICYCP_PLUGIN_URL .'/inc/businessup/images/testimonial/testi3.jpg',
			),

			array(
				'title'      => esc_html__( 'Professional Team', 'businessup' ),
				'text'       => esc_html__('Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium.', 'businessup' ),
				'subtitle'      => __('Williams Moore','businessup'),
				'designation'       => ' Designer',
				'image_url' => ICYCP_PLUGIN_URL .'/inc/businessup/images/testimonial/testi2.jpg',
			),

		); 
		$bussinessup_testimonial_content_default_value_control ->default = json_encode( $defaults);
	} 
		
}

add_action( 'customize_register', 'icycp_businessup_testimonial_customize_register' );
endif;


/**
 * Selective refresh for testimonial section
 */
function icycp_businessup_register_testimonial_section_partials( $wp_customize ){ 

	//Testimonial
	$wp_customize->selective_refresh->add_partial( 'businessup_testimonials_title', array(
		'selector'            => '.testimonials-section .businessup-heading-inner',
		'settings'            => 'businessup_testimonials_title',
		'render_callback'  => 'icycp_businessup_businessup_testimonials_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'businessup_testimonials_subtitle', array(
		'selector'            => '.testimonials-section .businessup-heading p',
		'settings'            => 'businessup_testimonials_subtitle',
		'render_callback'  => 'icycp_businessup_businessup_testimonials_subtitle_render_callback',
	
	) );
	
}

add_action( 'customize_register', 'icycp_businessup_register_testimonial_section_partials' );


//Testimonial Section
function icycp_businessup_businessup_testimonials_title_render_callback() {
	return get_theme_mod( 'businessup_testimonials_title' );
}

function icycp_businessup_businessup_testimonials_subtitle_render_callback() {
	return get_theme_mod( 'businessup_testimonials_subtitle' );
}


if ( ! function_exists( 'icycp_businessup_news_customize_register' ) ) :
	function icycp_businessup_news_customize_register($wp_customize){
			
		
		$wp_customize->add_section(
			'businessup_news_section_settings', array(
			'title' => __('Latest News Settings','businessup'),
			'description' => '',
			'panel'  => 'homepage_setting',
		) );
		
		//Latest News Enable / Disable setting 
		$wp_customize->add_setting( 'businessup_news_enable',
		   array(
			  'default' => 1 ,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_businessup_switch_sanitization'
		    )
		); 
		$wp_customize->add_control( new Icycp_businessup_Toggle_Switch_Custom_control( $wp_customize, 'businessup_news_enable',
		   array(
			  'label' => __('Hide / Show Section', 'businessup'),
			  'section' => 'businessup_news_section_settings',
			  'choices'=>array( 1 =>true , 0 =>false),
		    )
		) ); 

		//Latest News Background Image
		$wp_customize->add_setting( 
			'businessup_news_background', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 
			'businessup_news_background', array(
			'label'    => __( 'Background Image', 'businessup' ),
			'section'  => 'businessup_news_section_settings',
			'settings' => 'businessup_news_background', ) 
		) );
		
		//Latest News Overlay color
		$wp_customize->add_setting(
			'businessup_news_overlay_color', array( 
		) );
		
		$wp_customize->add_control(new businessup_Customize_Alpha_Color_Control( $wp_customize,'businessup_news_overlay_color', array(
			'label' => __('Overlay Color', 'businessup' ),
			'palette' => true,
			'section' => 'businessup_news_section_settings')
		) );

		//Latest News text color
		$wp_customize->add_setting(
			'businessup_news_text_color', array( 'sanitize_callback' => 'sanitize_hex_color',
		) );
		
		$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'businessup_news_text_color', array(
			'label' => __('Text Color', 'businessup' ),
			'palette' => true,
			'section' => 'businessup_news_section_settings')
		) );

		//Latest Meta Enable / Disable setting 
		$wp_customize->add_setting( 'businessup_disable_news_meta',
		   array(
			  'default' => false ,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_businessup_switch_sanitization'
		    )
		); 
		$wp_customize->add_control( new Icycp_businessup_Toggle_Switch_Custom_control( $wp_customize, 'businessup_disable_news_meta',
		   array(
			  'label' => __('Hide / Show Meta', 'businessup'),
			  'section' => 'businessup_news_section_settings',
		    )
		) ); 
		// Latest News Title Setting
		$wp_customize->add_setting(
			'businessup_news_title', array(
			'default' => __('Latest News', 'businessup'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		) );    
		$wp_customize->add_control( 
			'businessup_news_title',array(
			'label'   => __('Title','businessup'),
			'section' => 'businessup_news_section_settings',
			'type' => 'text',
		) );

		// Latest News Subtitle Setting
		$wp_customize->add_setting(
			'businessup_news_subtitle', array(
			'default' => __('laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.', 'businessup'),
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'businessup_homepage_sanitize_textarea_content',
		) );  
		$wp_customize->add_control( 
			'businessup_news_subtitle',array(
			'label'   => __('Description','businessup'),
			'section' => 'businessup_news_section_settings',
			'type' => 'textarea',
		) );  

		
	}
	
	add_action( 'customize_register', 'icycp_businessup_news_customize_register' );
	endif;

/**
 * Selective refresh for news section
 */
function icycp_businessup_register_news_section_partials( $wp_customize ){


	$wp_customize->selective_refresh->add_partial( 'businessup_news_title', array(
		'selector'            => '.businessup-blog-section .businessup-heading h3',
		'settings'            => 'businessup_news_title',
		'render_callback'  => 'icycp_businessup_businessup_news_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'businessup_news_subtitle', array(
		'selector'            => '.businessup-blog-section .businessup-heading p',
		'settings'            => 'businessup_news_subtitle',
		'render_callback'  => 'icycp_businessup_businessup_news_subtitle_render_callback',
	
	) ); 
}

add_action( 'customize_register', 'icycp_businessup_register_news_section_partials' );


//Latest News Section
function icycp_businessup_businessup_news_title_render_callback() {
	return get_theme_mod( 'businessup_news_title' );
}

function icycp_businessup_businessup_news_subtitle_render_callback() {
	return get_theme_mod( 'businessup_news_subtitle' );
}


if ( ! function_exists( 'icycp_businessup_switch_sanitization' ) ) {
	function icycp_businessup_switch_sanitization( $input ) {
		if ( true == $input ) {
			return 1;
		} else {
			return 0;
		}
	}
}

//Sanatize text validation
function icycp_businessup_home_page_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}