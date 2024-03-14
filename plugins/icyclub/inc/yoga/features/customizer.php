<?php


if ( ! function_exists( 'icycp_yoga_slider_customize_register' ) ) :
function icycp_yoga_slider_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

class Icycp_Yoga_Toggle_Switch_Custom_control extends WP_Customize_Control {
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

class yoga_upgrade_notice extends WP_Customize_Control {
	public function render_content() { ?>
		<h3 class="customizer_yoga_upgrade_section" style="display: none;">
<?php _e('To add More Feature? Then','icyclub'); ?><a href="<?php echo esc_url( 'https://themeansar.com/themes/yoga-wordpress-theme/' ); ?>" target="_blank">
			<?php _e('Upgrade to Pro','icyclub'); ?> </a>  
		</h3>
	<?php
	}
}


/* --------------------------------------
=========================================
Slider Section
=========================================
-----------------------------------------*/
$wp_customize->add_section(
	'yoga_slider_section_settings', array(
	'title' => __('Slider Section','yoga'),
	'panel'  => 'homepage_setting',
	'priority'   => 1,
) );
	//Slider Enable/Disable setting
	$wp_customize->add_setting(
		'yoga_slider_enable', array(
		'default' => '1',
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'icycp_yoga_switch_sanitization'
	));
	$wp_customize->add_control( new Icycp_Yoga_Toggle_Switch_Custom_control( $wp_customize, 'yoga_slider_enable',
	 array(
		'label'   => __('Enable/Diaable Section', 'yoga'),
		'section' => 'yoga_slider_section_settings',
		'type'    => 'radio',
		'choices'=>array('1'=>'On','0'=>'Off'),
		)
	));
	
	//Slider Overlay Color
	$wp_customize->add_setting(
		'yoga_slider_overlay_color', array( 'sanitize_callback' => 'yoga_sanitize_colors',

	) );
	
	$wp_customize->add_control(new Yoga_Customize_Alpha_Color_Control( $wp_customize,
		'yoga_slider_overlay_color', array(
		'label'      => __('Overlay Color', 'yoga' ),
		'palette' => true,
		'section' => 'yoga_slider_section_settings')
	) );
		 	
	if ( class_exists( 'Yoga_Repeater_Control' ) ) {
		$wp_customize->add_setting( 'yoga_slider_content', array(
		) );

		$wp_customize->add_control( new Yoga_Repeater_Control( $wp_customize, 'yoga_slider_content', array(
			'label'                             => esc_html__( 'Slider Content', 'yoga' ),
			'section'                           => 'yoga_slider_section_settings',
			'add_field_label'                   => esc_html__( 'Add new slider', 'yoga' ),
			'item_name'                         => esc_html__( 'Slide', 'yoga' ),
			'customizer_repeater_title_control' => true,
			'customizer_repeater_text_control'  => true,
			'customizer_repeater_button_text_control' => true,
			'customizer_repeater_link_control'  => true,
			'customizer_repeater_image_control' => true, 
			'customizer_repeater_checkbox_control' => true,
			) ) );
	}


			
		

			$yoga_slider_content_default_value_control = $wp_customize->get_setting( 'yoga_slider_content' );
				if ( ! empty( $yoga_slider_content_default_value_control ) ) 
				{
					$widget_default = get_option('widget_yoga_slider-widget') ;

					$defaults = array(
						array(
						'slider_title'      => esc_html__( 'You can simply control what goes ahead inside', 'yoga' ),
						'slider_desc'       => esc_html__( 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin..', 'yoga' ),
						'btnone'      => __('Read More','yoga'),
						'btnonelink'       => '#',
						'image_uri'  => ICYCP_PLUGIN_URL .'inc/yoga/images/slider/slider1.jpg',
						'open_btnone_new_window' => 'no',
						),
		
						array(
							'slider_title'      => esc_html__( 'Transform your body with a yoga coach', 'yoga' ),
							'slider_desc'       => esc_html__( 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin..', 'yoga' ),
							'btnone'      => __('Read More','yoga'),
							'btnonelink'       => '#',
							'image_uri'  => ICYCP_PLUGIN_URL .'inc/yoga/images/slider/slider2.jpg',
							'open_btnone_new_window' => 'no',
							),
		
						array(
							'slider_title'      => esc_html__( 'Confinement and find your brain', 'yoga' ),
							'slider_desc'       => esc_html__( 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin..', 'yoga' ),
							'btnone'      => __('Read More','yoga'),
							'btnonelink'       => '#',
							'image_uri'  => ICYCP_PLUGIN_URL .'inc/yoga/images/slider/slider1.jpg',
							'open_btnone_new_window' => 'no',
							),
		
					);

					
					$slider_widget_data = get_option('widget_yoga_slider-widget', $defaults );

					$arr = array(); //create empty array

					$i = 0;

					foreach(array_reverse($slider_widget_data) as $widget_data) {

						if($i == 4){
							break;
						}
						$i++;

						if(isset($slider_widget_data['_multiwidget'])){


							if($widget_data == $slider_widget_data['_multiwidget']){


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
				$yoga_slider_content_default_value_control ->default = json_encode( $arr);

			}else{ }

			
			
			$wp_customize->add_setting( 'yoga_slider_upgrade_to_pro', array(
				'capability'			=> 'edit_theme_options',
			));
			$wp_customize->add_control(
				new yoga_upgrade_notice(
				$wp_customize,
				'yoga_slider_upgrade_to_pro',
					array(
						'section'				=> 'yoga_slider_section_settings',
						'settings'				=> 'yoga_slider_upgrade_to_pro',
					)
				)
			);
		
		
		
}

add_action( 'customize_register', 'icycp_yoga_slider_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_yoga_register_slider_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'slider_image', array(
		'selector'            => '.yoga-slider-warraper .item figure',
		'settings'            => 'slider_image',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'yoga_slider_title', array(
		'selector'            => '#ta-slider .slide-caption h1',
		'settings'            => 'yoga_slider_title',
		'render_callback'  => 'icycp_yoga_slider_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'yoga_slider_discription', array(
		'selector'            => '#ta-slider .slide-caption .description p',
		'settings'            => 'yoga_slider_discription',
		'render_callback'  => 'icycp_yoga_slider_iscription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'yoga_slider_btn_txt', array(
		'selector'            => '.slide-caption .btn-tislider',
		'settings'            => 'yoga_slider_btn_txt',
		'render_callback'  => 'icycp_yoga_slider_btn_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_yoga_register_slider_section_partials' );


function icycp_yoga_slider_title_render_callback() {
	return get_theme_mod( 'yoga_slider_title' );
}

function icycp_yoga_slider_iscription_render_callback() {
	return get_theme_mod( 'yoga_slider_discription' );
}

function icycp_yoga_slider_btn_render_callback() {
	return get_theme_mod( 'yoga_slider_btn_txt' );
}


/* --------------------------------------
=========================================
Service Section
=========================================
-----------------------------------------*/

if ( ! function_exists( 'icycp_yoga_service_customize_register' ) ) :
function icycp_yoga_service_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Services section */
	$wp_customize->add_section( 'yoga_service_section_settings' , array(
		'title'      => __('Service Section', 'icyclub'),
		'panel'  => 'homepage_setting',
		'priority'   => 3,
	) );
		
		$wp_customize->add_setting( 'yoga_service_enable',
		   array(
			  'default' => 1 ,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_yoga_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Yoga_Toggle_Switch_Custom_control( $wp_customize, 'yoga_service_enable',
		   array(
			  'label' => esc_html__( 'Service Enable/Disable' ),
			  'section' => 'yoga_service_section_settings',
			  'choices'=>array( 1 =>true , 0 =>false),
		   )
		) );

		//Service Overlay 
		$wp_customize->add_setting(
			'yoga_service_overlay_color', array( 'sanitize_callback' => '',
			
		) );
		
		$wp_customize->add_control(new Yoga_Customize_Alpha_Color_Control( $wp_customize,'yoga_service_overlay_color', array(
		   'label'      => __('Overlay Color','yoga' ),
			'palette' => true,
			'section' => 'yoga_service_section_settings')
		) );

		//Service text color setting
		$wp_customize->add_setting(
			'yoga_service_text_color', array( 'sanitize_callback' => 'sanitize_hex_color',
			
		) );
		
		$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'yoga_service_text_color', array(
		   'label'      => __('Text Color','yoga' ),
			'palette' => true,
			'section' => 'yoga_service_section_settings')
		) );

		//Service Title setting
		$wp_customize->add_setting(
			'yoga_service_title', array(
			'capability'     => 'edit_theme_options',
			'default' => __('Why We Best in yoga','icyclub'),
			'transport' => $selective_refresh,
		) );	
		$wp_customize->add_control( 
			'yoga_service_title',array(
			'label'   => __('Title','yoga'),
			'section' => 'yoga_service_section_settings',
			'type' => 'text',
		) );

		//Service SubTitle setting
		$wp_customize->add_setting(
			'yoga_service_subtitle', array(
			'capability'     => 'edit_theme_options',
			'default' => __(' Lorem ipsum dolor sit amet, consectetur adipiscing elit Pull in ten extra bodies to help.','icyclub'),
			'transport' => $selective_refresh,
		) );  
		$wp_customize->add_control( 'yoga_service_subtitle', array(
			'label'   => __('Description','yoga'),
			'section' => 'yoga_service_section_settings',
			'type' => 'textarea',
		) );

		
		if ( class_exists( 'yoga_Repeater_Control' ) ) {
			$wp_customize->add_setting( 'yoga_service_content', array(
			) );

			$wp_customize->add_control( new Yoga_Repeater_Control( $wp_customize, 'yoga_service_content', array(
				'label'                             => esc_html__( 'Service Content', 'yoga' ),
				'section'                           => 'yoga_service_section_settings',
				'add_field_label'                   => esc_html__( 'Add new Service', 'yoga' ),
				'item_name'                         => esc_html__( 'Service', 'yoga' ),
				'customizer_repeater_icon_control' => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_button_text_control' => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_checkbox_control' => true,
				) ) );
		}

		$yoga_service_content_default_value_control = $wp_customize->get_setting( 'yoga_service_content' );
				if ( ! empty( $yoga_service_content_default_value_control ) ) 
				{

					$defaults = array(
						array(
						'fa_icon' => 'fa fa-child',	
						'service_title'      => esc_html__( 'Lotus position', 'yoga' ),
						'service_desc'       => esc_html__( 'laoreet ipsum eu laoreet. ugiignissimat Vivamus.', 'yoga' ),
						'btnmore'      => __('Read More','yoga'),
						'btnlink'       => '#',
						'open_new_window' => 'no',
						),
				  
						array(
						  'fa_icon' => 'fa fa-handshake-o',	
						  'service_title'      => esc_html__( 'Bakasana', 'yoga' ),
						  'service_desc'       => esc_html__( 'laoreet Pellentesque molestie laoreet laoreet.', 'yoga' ),
						  'btnmore'      => __('Read More','yoga'),
						  'btnlink'       => '#',
						  'open_new_window' => 'no',
						),
					
						array(
						  'fa_icon' => 'fa fa-thumbs-up',	
						  'service_title'      => esc_html__( 'Handstand', 'yoga' ),
						  'service_desc'       => esc_html__( 'laoreet Pellentesque molestie laoreet laoreet.', 'yoga' ),
						  'btnmore'      => __('Read More','yoga'),
						  'btnlink'       => '#',
						  'open_new_window' => 'no',
						),		
				  
					);


					$service_widget_data = get_option('widget_yoga_service_widget', $defaults );

					$arr = array(); //create empty array

					$i = 0;

					foreach(array_reverse($service_widget_data) as $widget_data) {

						if($i == 4){
							break;
						}
						$i++;
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
						
						
						
					} 
				$yoga_service_content_default_value_control ->default = json_encode( $arr);

				}

				$wp_customize->add_setting( 'yoga_service_upgrade_to_pro', array(
					'capability'			=> 'edit_theme_options',
				));
				$wp_customize->add_control(
					new yoga_upgrade_notice(
					$wp_customize,
					'yoga_service_upgrade_to_pro',
						array(
							'section'				=> 'yoga_service_section_settings',
							'settings'				=> 'yoga_service_upgrade_to_pro',
						)
					)
				);

		
		
}

add_action( 'customize_register', 'icycp_yoga_service_customize_register' );
endif;


/**
 * Selective refresh for service section
 */
function icycp_yoga_register_service_section_partials( $wp_customize ){

	//Service
	$wp_customize->selective_refresh->add_partial( 'yoga_service_title', array(
		'selector'            => '#service .yoga-heading h3',
		'settings'            => 'yoga_service_title',
		'render_callback'  => 'icycp_yoga_service_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'yoga_service_subtitle', array(
		'selector'            => '#service .yoga-heading p',
		'settings'            => 'yoga_service_subtitle',
		'render_callback'  => 'icycp_yoga_service_subtitle_render_callback',
	
	) );
	
	//Service one
	$wp_customize->selective_refresh->add_partial( 'service_one_title', array(
		'selector'            => '.yoga-service h3',
		'settings'            => 'service_one_title',
		'render_callback'  => 'icycp_yoga_service_one_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_one_description', array(
		'selector'            => '.service-one .ta-service.three p',
		'settings'            => 'service_one_description',
		'render_callback'  => 'icycp_yoga_service_one_desc_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'ser_one_btn_text', array(
		'selector'            => '.service-one a',
		'settings'            => 'ser_one_btn_text',
		'render_callback'  => 'icycp_yoga_service_one_btn_render_callback',
	
	) );
	
	//Service two
	$wp_customize->selective_refresh->add_partial( 'service_two_title', array(
		'selector'            => '.service-two h3',
		'settings'            => 'service_two_title',
		'render_callback'  => 'icycp_yoga_service_two_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_two_description', array(
		'selector'            => '.service-two .ta-service.three p',
		'settings'            => 'service_two_description',
		'render_callback'  => 'icycp_yoga_service_two_desc_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'ser_two_btn_text', array(
		'selector'            => '.service-two a',
		'settings'            => 'ser_two_btn_text',
		'render_callback'  => 'icycp_yoga_service_two_btn_render_callback',
	
	) );
	
	
	//Service three
	$wp_customize->selective_refresh->add_partial( 'service_three_title', array(
		'selector'            => '.service-three h3',
		'settings'            => 'service_three_title',
		'render_callback'  => 'icycp_yoga_service_three_title_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'service_three_description', array(
		'selector'            => '.service-three .ta-service.three p',
		'settings'            => 'service_three_description',
		'render_callback'  => 'icycp_yoga_service_three_desc_render_callback',
	
	) );
	$wp_customize->selective_refresh->add_partial( 'ser_three_btn_text', array(
		'selector'            => '.service-three a',
		'settings'            => 'ser_three_btn_text',
		'render_callback'  => 'icycp_yoga_service_three_btn_render_callback',
	
	) );
	
	
	
}

add_action( 'customize_register', 'icycp_yoga_register_service_section_partials' );


function icycp_yoga_service_title_render_callback() {
	return get_theme_mod( 'yoga_service_title' );
}

function icycp_yoga_service_subtitle_render_callback() {
	return get_theme_mod( 'yoga_service_subtitle' );
}

//Service one

function icycp_yoga_service_one_title_render_callback() {
	return get_theme_mod( 'service_one_title' );
}

function icycp_yoga_service_one_desc_render_callback() {
	return get_theme_mod( 'service_one_description' );
}

function icycp_yoga_service_one_btn_render_callback() {
	return get_theme_mod( 'ser_one_btn_text' );
}


//Service two

function icycp_yoga_service_two_title_render_callback() {
	return get_theme_mod( 'service_two_title' );
}

function icycp_yoga_service_two_desc_render_callback() {
	return get_theme_mod( 'service_two_description' );
}

function icycp_yoga_service_two_btn_render_callback() {
	return get_theme_mod( 'ser_two_btn_text' );
}

//Service three

function icycp_yoga_service_three_title_render_callback() {
	return get_theme_mod( 'service_three_title' );
}

function icycp_yoga_service_three_desc_render_callback() {
	return get_theme_mod( 'service_three_description' );
}

function icycp_yoga_service_three_btn_render_callback() {
	return get_theme_mod( 'ser_three_btn_text' );
}


/* --------------------------------------
=========================================
Callout Section
=========================================
-----------------------------------------*/

if ( ! function_exists( 'icycp_yoga_callout_customize_register' ) ) :
function icycp_yoga_callout_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Callout Section */
	$wp_customize->add_section( 'yoga_callout_section_settings' , array(
		'title'      => __('Callout Secton','yoga'),
		'panel'  => 'homepage_setting',
		'priority'   => 4,
   	) );
		
		// Enable Callout
		$wp_customize->add_setting( 'yoga_callout_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_yoga_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Yoga_Toggle_Switch_Custom_control( $wp_customize, 'yoga_callout_enable',
		   array(
			  'label' => esc_html__( 'Enable/Disable Section' ),
			  'section' => 'yoga_callout_section_settings',
		   )
		) );

		//Callout background Image
		$wp_customize->add_setting( 'yoga_callout_background',array('default' => ICYCP_PLUGIN_URL .'inc/yoga/images/callout/callout-back.jpg',
		'sanitize_callback' => 'esc_url_raw'));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'yoga_callout_background',
				array(
					'type'        => 'upload',
					'label' => __('Choose Background Image','yoga'),
					'settings' =>'yoga_callout_background',
					'section' => 'yoga_callout_section_settings',
					
				)
			)
		);
		
		//Callout Background Overlay Color
		$wp_customize->add_setting( 'yoga_callout_overlay_color', array(
			'default' => '#070b2be0',
            ) );	
            
            $wp_customize->add_control(new Yoga_Customize_Alpha_Color_Control( $wp_customize,'yoga_callout_overlay_color', array(
               'label'      => __('Overlay Color','yoga'),
                'palette' => true,
                'section' => 'yoga_callout_section_settings')
            ) );

		//Callout Text Color setting
		$wp_customize->add_setting(
			'yoga_callout_text_color', array( 'sanitize_callback' => 'sanitize_hex_color',
			'default' => __('#fff'),
		) );
		
		$wp_customize->add_control(new Yoga_Customize_Alpha_Color_Control( $wp_customize,'yoga_callout_text_color', array(
		    'label'   => __('Text Color','yoga' ),
			'palette' => true,
			'section' => 'yoga_callout_section_settings')
		) );
		
		
		// callout title
		$wp_customize->add_setting( 'yoga_callout_title',array(
		'default' => __('Certified Yoga Professionals. Try Today!','yoga'),
		'sanitize_callback' => 'icycp_yoga_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'yoga_callout_title',array(
		'label'   => __('Title','yoga'),
		'section' => 'yoga_callout_section_settings',
		'type' => 'text',
		));	
		
		//callout description
		$wp_customize->add_setting( 'yoga_callout_description',array(
		'default' => 'We must explain to you how all this mistaken idea of denouncing pleasure',
		'sanitize_callback' => 'icycp_yoga_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'yoga_callout_description',array(
		'label'   => __('Description','yoga'),
		'section' => 'yoga_callout_section_settings',
		'type' => 'textarea',
		));
		
		
		// callout button text
		$wp_customize->add_setting( 'yoga_callout_button_one_label',array(
		'default' => __('Get Started Now!','yoga'),
		'sanitize_callback' => 'icycp_yoga_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'yoga_callout_button_one_label',array(
		'label'   => __('Button Text','yoga'),
		'section' => 'yoga_callout_section_settings',
		'type' => 'text',
		));
		
		// Callout button link
		$wp_customize->add_setting( 'yoga_callout_button_one_link',array(
		'default' => '#',
		'sanitize_callback' => 'icycp_yoga_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'yoga_callout_button_one_link',array(
		'label'   => __('Button Link','yoga'),
		'section' => 'yoga_callout_section_settings',
		'type' => 'text',
		));
		
		// Callout button target
		$wp_customize->add_setting(
		'yoga_callout_button_one_target', 
			array(
			'default'        => false,
			'sanitize_callback' => 'icycp_yoga_home_page_sanitize_text',
		));
		$wp_customize->add_control('yoga_callout_button_one_target', array(
			'label'   => __('Open link in new tab/window','yoga'),
			'section' => 'yoga_callout_section_settings',
			'type' => 'checkbox',
		));

}

add_action( 'customize_register', 'icycp_yoga_callout_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_yoga_register_callout_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'yoga_callout_title', array(
		'selector'            => '.yoga-callout .yoga-callout-inner h3',
		'settings'            => 'yoga_callout_title',
		'render_callback'  => 'icycp_yoga_callout_title_render_callback',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'yoga_callout_description', array(
		'selector'            => '.yoga-callout .yoga-callout-inner p',
		'settings'            => 'yoga_callout_description',
		'render_callback'  => 'icycp_yoga_callout_section_desc_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'yoga_callout_button_one_label', array(
		'selector'            => '.yoga-callout .yoga-callout-inner .btn-theme',
		'settings'            => 'yoga_callout_button_one_label',
		'render_callback'  => 'icycp_yoga_callout_button_one_label_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_yoga_register_callout_section_partials' );


function icycp_yoga_callout_title_render_callback() {
	return get_theme_mod( 'yoga_callout_title' );
}

function icycp_yoga_callout_section_desc_render_callback() {
	return get_theme_mod( 'yoga_callout_description' );
}

function icycp_yoga_callout_button_one_label_render_callback() {
	return get_theme_mod( 'yoga_callout_button_one_label' );
}

/* --------------------------------------
=========================================
Testimonial Section
=========================================
-----------------------------------------*/

if ( ! function_exists( 'icycp_yoga_testimonial_customize_register' ) ) :
	function icycp_yoga_testimonial_customize_register($wp_customize){
	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	
		/* Testimonial Section */
		$wp_customize->add_section( 'yoga_testimonial_section' , array(
				'title'      => __('Testimonial Section', 'icyclub'),
				'panel'  => 'homepage_setting',
				'priority'   => 5,
			) );
			
		// Enable testimonial section
		$wp_customize->add_setting( 'yoga_testimonial_section_enable',
			array(
				'default' => 1,
				'transport' => 'refresh',
				'sanitize_callback' => 'icycp_yoga_switch_sanitization'
			)
		);
			
		$wp_customize->add_control( new Icycp_Yoga_Toggle_Switch_Custom_control( $wp_customize, 'yoga_testimonial_section_enable',
			array(
				'label' => esc_html__( 'Testimonial Enable/Disable' ),
				'section' => 'yoga_testimonial_section',
				'choices'=>array( 1 =>true , 0 =>false),
			)
		) );
			
		//Testimonial Background Image
		$wp_customize->add_setting( 'yoga_testimonial_bg_img',array(
		//'sanitize_callback' => 'esc_url_raw', 
		'transport' => $selective_refresh ));
	
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'yoga_testimonial_bg_img', array(
			'type'        => 'upload',
			'label'    => __( 'Choose Background Image', 'icyclub' ),
			'section'  => 'yoga_testimonial_section',
			'settings' => 'yoga_testimonial_bg_img',
		) ) );
	
		//Testimonial Background Overlay Color
		$wp_customize->add_setting( 'yoga_testimonial_overlay_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => '#fafafa',
			) );	
			
		$wp_customize->add_control(new Yoga_Customize_Alpha_Color_Control( $wp_customize,'yoga_testimonial_overlay_color', array(
			'label'      => __('Overlay Color','icyclub' ),
			'palette' => true,
			'section' => 'yoga_testimonial_section')
		) );
	
		//Testimonial text Color
		$wp_customize->add_setting( 'yoga_testimonial_text_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			) );	
			
		$wp_customize->add_control(new Yoga_Customize_Alpha_Color_Control( $wp_customize,'yoga_testimonial_text_color', array(
			'label'      => __('Text Color','icyclub' ),
			'palette' => true,
			'section' => 'yoga_testimonial_section')
		) );
					
	
		// Testimonial section title
		$wp_customize->add_setting( 'yoga_testimonial_section_title',array(
			'capability'     => 'edit_theme_options',
			'default' => __('Happy Customers','yoga'),
			'sanitize_callback' => 'icycp_yoga_home_page_sanitize_text',
			'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'yoga_testimonial_section_title',array(
			'label'   => __('Title','yoga'),
			'section' => 'yoga_testimonial_section',
			'type' => 'text',
		));	
	
		//testimonial section discription
		$wp_customize->add_setting( 'yoga_testimonial_section_discription',array(
			'capability'     => 'edit_theme_options',
			'default'=> 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
			'sanitize_callback' => 'icycp_yoga_home_page_sanitize_text',
			'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'yoga_testimonial_section_discription',array(
			'label'   => __('Description','icyclub'),
			'section' => 'yoga_testimonial_section',
			'type' => 'textarea',
		));
	
		if ( class_exists( 'Yoga_Repeater_Control' ) ) {
			$wp_customize->add_setting( 'yoga_testimonial_content', array(
			) );
	
			$wp_customize->add_control( new Yoga_Repeater_Control( $wp_customize, 'yoga_testimonial_content', array(
				'label'                             => esc_html__( 'Testimonial content', 'yoga' ),
				'section'                           => 'yoga_testimonial_section',
				'add_field_label'                   => esc_html__( 'Add new Testimonial', 'yoga' ),
				'item_name'                         => esc_html__( 'Testimonial', 'yoga' ),
				'customizer_repeater_text_control' => true,
				'customizer_repeater_title_control'  => true,
				'customizer_repeater_designation_control' => true,
				'customizer_repeater_image_control' => true,
				'customizer_repeater_designation2_control' => true,
				
				) ) );
		}
		$yoga_testimonial_content_default_value_control = $wp_customize->get_setting( 'yoga_testimonial_content' );
		if ( ! empty( $yoga_testimonial_content_default_value_control ) ) 
		{	
			$defaults = array(
				array(
				'title' => 'Williams Moore',	
				'designation2'      => ' Company inc',
				'text'       => 'Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium',
				'designation' => __('Creative Designer','yoga'),
				'image_url'  => ICYCP_PLUGIN_URL .'inc/yoga/images/testimonial/testi1.jpg',
				'open_new_tab' => 'no',
				),
	
				array(
				'title' => 'Sara Williams',	
				'designation2'      => ' Company inc',
				'text'       => 'Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium',
				'designation' => __('Creative Designer','yoga'),
				'image_url'  => ICYCP_PLUGIN_URL .'inc/yoga/images/testimonial/testi3.jpg',
				'open_new_tab' => 'no',
				),
		
				array(
				'title' => 'Williams Moore',	
				'designation2'      => ' Company inc',
				'text'       => 'Vestibulum quis porttitor dui! viverra nunc mi, Aliquam condimentum mattis neque sed pretium Aliquam condimentum mattis neque sed pretiumAliquam condimentum mattis neque sed pretium',
				'designation' => __('Creative Designer','yoga'),
				'image_url'  => ICYCP_PLUGIN_URL .'inc/yoga/images/testimonial/testi2.jpg',
				'open_new_tab' => 'no',
				),		
			);
	
			$yoga_testimonial_content_default_value_control ->default = json_encode($defaults);
			
		}
	
		$wp_customize->add_setting( 'yoga_testimonial_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
		));
		$wp_customize->add_control(
			new yoga_upgrade_notice(
			$wp_customize,
			'yoga_testimonial_upgrade_to_pro',
				array(
					'section'				=> 'yoga_testimonial_section',
					'settings'				=> 'yoga_testimonial_upgrade_to_pro',
				)
			)
		);
	
	}
	
	add_action( 'customize_register', 'icycp_yoga_testimonial_customize_register' );
	endif;
	
	
	/**
	 * Selective refresh for testimonial section
	 */
	function icycp_yoga_register_testimonial_section_partials( $wp_customize ){
		
		//Testimonial
		$wp_customize->selective_refresh->add_partial( 'testimonial_callout_background', array(
			'selector'            => 'section.testimonial-section',
			'settings'            => 'testimonial_callout_background',
		
		) );
	
		$wp_customize->selective_refresh->add_partial( 'yoga_testimonial_section_title', array(
			'selector'            => '.testimonials-section .yoga-heading h3',
			'settings'            => 'yoga_testimonial_section_title',
			'render_callback'  => 'icycp_yoga_testimonial_section_title_render_callback',
		
		) );
		
		$wp_customize->selective_refresh->add_partial( 'yoga_testimonial_section_discription', array(
			'selector'            => '.testimonials-section .yoga-heading p',
			'settings'            => 'yoga_testimonial_section_discription',
			'render_callback'  => 'icycp_yoga_testimonial_section_discription_render_callback',
		
		) );
		
		
	}
	
	//Testimonial Section
	function icycp_yoga_testimonial_section_title_render_callback() {
		return get_theme_mod( 'yoga_testimonial_section_title' );
	}
	
	function icycp_yoga_testimonial_section_discription_render_callback() {
		return get_theme_mod( 'yoga_testimonial_section_discription' );
	}
	add_action( 'customize_register', 'icycp_yoga_register_testimonial_section_partials' );
	
/* --------------------------------------
=========================================
News Section
=========================================
-----------------------------------------*/

if ( ! function_exists( 'icycp_yoga_news_customize_register' ) ) :
	function icycp_yoga_news_customize_register($wp_customize){
	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';		
		
		$wp_customize->add_section(
			'yoga_news_section_settings', array(
			'title' => __('Latest News Section','yoga'),
			'description' => '',
			'panel'  => 'homepage_setting',
			'priority'   => 7,
		) );
		
		//Latest News Enable / Disable setting

		$wp_customize->add_setting( 'yoga_news_enable',
		   array(
			  'default' => 1 ,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_yoga_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Yoga_Toggle_Switch_Custom_control( $wp_customize, 'yoga_news_enable',
		   array(
			  'label' => __('Hide / Show Section','yoga'),
			  'section' => 'yoga_news_section_settings',
			  'choices'=>array( 1 =>true , 0 =>false),
		   )
		) );
		

		//Latest News Background Image
		$wp_customize->add_setting( 
			'yoga_news_background', array(
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 
			'yoga_news_background', array(
			'label'    => __( 'Choose Background Image','yoga' ),
			'section'  => 'yoga_news_section_settings',
			'settings' => 'yoga_news_background') ,
		) );
		
		//Latest News Overlay color
		$wp_customize->add_setting(
			'yoga_news_overlay_color', array( 
		) );
		
		$wp_customize->add_control(new Yoga_Customize_Alpha_Color_Control( $wp_customize,'yoga_news_overlay_color', array(
			'label' => __('Overlay Color','yoga' ),
			'palette' => true,
			'section' => 'yoga_news_section_settings')
		) );

		//Latest News text color
		$wp_customize->add_setting(
			'yoga_news_text_color', array( 'sanitize_callback' => 'sanitize_hex_color',
		) );
		
		$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'yoga_news_text_color', array(
			'label' => __('Text Color','yoga' ),
			'palette' => true,
			'section' => 'yoga_news_section_settings')
		) );
		
		
		// hide meta content
		$wp_customize->add_setting(
			'disable_news_meta', array(
			'default' => 'false',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'yoga_homepage_sanitize_checkbox',
		) );
		$wp_customize->add_control(
			'disable_news_meta', array(
			'label' => __('Hide post meta from blog pages, archive pages, categories, authors, etc.','yoga'),
			'section' => 'yoga_news_section_settings',
			'type' => 'checkbox',
		) );

		// Latest News Title Setting
		$wp_customize->add_setting(
			'yoga_news_title', array(
			'default' => __('Latest News','yoga'),
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'icycp_yoga_home_page_sanitize_text',
			'transport' => $selective_refresh,
		) );    
		$wp_customize->add_control( 
			'yoga_news_title',array(
			'label'   => __('Title','yoga'),
			'section' => 'yoga_news_section_settings',
			'type' => 'text',
		) );

		// Latest News Subtitle Setting
		$wp_customize->add_setting(
			'yoga_news_subtitle', array(
			'default' => __('laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.','yoga'),
			'capability' => 'edit_theme_options',
			'transport' => $selective_refresh,
		) );  
		$wp_customize->add_control( 
			'yoga_news_subtitle',array(
			'label'   => __('Description','yoga'),
			'section' => 'yoga_news_section_settings',
			'type' => 'textarea',
		) );  

		
	}
	
	add_action( 'customize_register', 'icycp_yoga_news_customize_register' );
	endif;
/**
 * Selective refresh for News section
 */

function icycp_yoga_register_yoga_section_partials( $wp_customize ){

	//News Section
	$wp_customize->selective_refresh->add_partial( 'yoga_news_title', array(
		'selector'            => '.yoga-blog-section .yoga-heading h3',
		'settings'            => 'yoga_news_title',
		'render_callback'  => 'icycp_yoga_news_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'yoga_news_subtitle', array(
		'selector'            => '.yoga-blog-section .yoga-heading p',
		'settings'            => 'yoga_news_subtitle',
		'render_callback'  => 'icycp_yoga_news_subtitle_render_callback',
	
	) );
}

//News Section
function icycp_yoga_news_title_render_callback() {
	return get_theme_mod( 'yoga_news_title' );
}

function icycp_yoga_news_subtitle_render_callback() {
	return get_theme_mod( 'yoga_news_subtitle' );
}

add_action( 'customize_register', 'icycp_yoga_register_yoga_section_partials' );


if ( ! function_exists( 'icycp_yoga_switch_sanitization' ) ) {
		function icycp_yoga_switch_sanitization( $input ) {
			if ( true == $input ) {
				return 1;
			} else {
				return 0;
			}
		}

		
}

//Sanatize text validation
function icycp_yoga_home_page_sanitize_text( $input ) {

		return wp_kses_post( force_balance_tags( $input ) );
}