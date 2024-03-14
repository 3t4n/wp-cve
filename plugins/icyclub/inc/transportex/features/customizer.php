<?php if ( ! function_exists( 'icycp_transportex_slider_customize_register' ) ) :
function icycp_transportex_slider_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

class Icycp_transportex_Toggle_Switch_Custom_control extends WP_Customize_Control {
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

class transportex_upgrade_notice extends WP_Customize_Control {
	public function render_content() { ?>
		<h3 class="customizer_transportex_upgrade_section" style="display: none;">
<?php _e('To add More Feature? Then','icyclub'); ?><a href="<?php echo esc_url( 'https://themeansar.com/themes/transportex-pro' ); ?>" target="_blank">
			<?php _e('Upgrade to Pro','icyclub'); ?> </a>  
		</h3>
	<?php
	}
}

/* Slider Section */
	$wp_customize->add_section( 'slider_section' , array(
		'title'      => __('Slider Section', 'icyclub'),
		'panel'  => 'homepage_setting',
		'priority'   => 1,
   	) );
		
		$wp_customize->add_setting( 'transportex_slider_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_transportex_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_transportex_Toggle_Switch_Custom_control( $wp_customize, 'transportex_slider_enable',
		   array(
			  'label' => esc_html__( 'Slider Enable/Disable' ),
			  'section' => 'slider_section'
		   )
		) );
		
		//Slider Background Overlay Color
		$wp_customize->add_setting( 'slider_overlay_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => 'rgba(0,0,0,0.8)',
            ) );	
            
            $wp_customize->add_control(new transportex_Customize_Alpha_Color_Control( $wp_customize,'slider_overlay_color', array(
               'label'      => __('Image Overlay Color','transportex' ),
                'palette' => true,
                'section' => 'slider_section')
            ) );
		
		if ( class_exists( 'Transportex_Repeater_Control' ) ) {
			$wp_customize->add_setting( 'transportex_slider_content', array(
			) );
	
			$wp_customize->add_control( new Transportex_Repeater_Control( $wp_customize, 'transportex_slider_content', array(
				'label'                                    => esc_html__( 'Slider Content', 'transportex' ),
				'section'                                  => 'slider_section',
				'add_field_label'                          => esc_html__( 'Add new Slider', 'transportex' ),
				'item_name'                                => esc_html__( 'Slider', 'transportex' ),
				'customizer_repeater_title_control'        => true,
				'customizer_repeater_text_control'         => true,
				'customizer_repeater_button_text_control'  => true,
				'customizer_repeater_link_control'         => true,
				'customizer_repeater_image_control'        => true,
				'customizer_repeater_checkbox_control'     => true,
				) ) );
			}
		
			$transportex_slider_content_default_value_control = $wp_customize->get_setting('transportex_slider_content');
			if ( ! empty( $transportex_slider_content_default_value_control ) ) 
			{
				$widget_default = get_option('widget_transportex_slider-widget');

				$defaults = array(
				array(
				'slider_title'      => '  We take care of your goods deliver World Wide',
				'slider_desc'       => esc_html__( ' Global logistics and transportation services via sea, land and air. We will protect you from risk and liability.', 'transportex' ),
				'btnone'      => __('Read More','transportex'),
				'btnonelink'       => '#',
				'image_uri'  => ICYCP_PLUGIN_URL .'inc/transportex/images/slider/slide01.jpg',
				'open_btnone_new_window' => 'no',
				'id'         => 'customizer_repeater_56d7ea7f40b96',
				),
				array(
				'slider_title'      => 'Transport your goods Around the World',
				'slider_desc'       => esc_html__( ' Global logistics and transportation services via sea, land and air. We will protect you from risk and liability.', 'transportex' ),
				'btnone'      => __('Read More','transportex'),
				'btnonelink'       => '#',
				'image_uri'  => ICYCP_PLUGIN_URL .'inc/transportex/images/slider/slide02.jpg',
				'open_btnone_new_window' => 'no',
				'id'         => 'customizer_repeater_56d7ea7f40b97',
				),
				array(
				'slider_title'      => 'We help world Wide  from our fleet Send it anywhere',
				'slider_desc'       => esc_html__( ' Global logistics and transportation services via sea, land and air. We will protect you from risk and liability.', 'transportex' ),
				'btnone'      => __('Read More','transportex'),
				'btnonelink'       => '#',
				'image_uri'  => ICYCP_PLUGIN_URL .'inc/transportex/images/slider/slide03.jpg',
				'open_btnone_new_window' => 'no',
				'id'         => 'customizer_repeater_56d7ea7f40b98',
				),
	
				);

				
				$service_widget_data = get_option('widget_transportex_slider-widget', $defaults );

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
			$transportex_slider_content_default_value_control ->default = json_encode($arr);

		}else{ }

		$wp_customize->add_setting( 'transportex_slider_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
		));
		$wp_customize->add_control(
			new transportex_upgrade_notice(
			$wp_customize,
			'transportex_slider_upgrade_to_pro',
				array(
					'section'				=> 'slider_section',
					'settings'				=> 'transportex_slider_upgrade_to_pro',
				)
			)
		);
		
}

add_action( 'customize_register', 'icycp_transportex_slider_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_transportex_register_slider_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'slider_image', array(
		'selector'            => '.transportex-slider-warraper .item figure',
		'settings'            => 'slider_image',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'transportex_slider_title', array(
		'selector'            => '#ta-slider .slide-caption h1',
		'settings'            => 'transportex_slider_title',
		'render_callback'  => 'icycp_transportex_slider_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'transportex_slider_discription', array(
		'selector'            => '#ta-slider .slide-caption .description p',
		'settings'            => 'transportex_slider_discription',
		'render_callback'  => 'icycp_transportex_slider_iscription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'transportex_slider_btn_txt', array(
		'selector'            => '.slide-caption .btn-tislider',
		'settings'            => 'transportex_slider_btn_txt',
		'render_callback'  => 'icycp_transportex_slider_btn_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_transportex_register_slider_section_partials' );


function icycp_transportex_slider_title_render_callback() {
	return get_theme_mod( 'transportex_slider_title' );
}

function icycp_transportex_slider_iscription_render_callback() {
	return get_theme_mod( 'transportex_slider_discription' );
}

function icycp_transportex_slider_btn_render_callback() {
	return get_theme_mod( 'transportex_slider_btn_txt' );
}


if ( ! function_exists( 'icycp_transportex_service_customize_register' ) ) :
function icycp_transportex_service_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Services section */
	$wp_customize->add_section( 'services_section' , array(
		'title'      => __('Service Section', 'icyclub'),
		'panel'  => 'homepage_setting',
		'priority'   => 3,
	) );
		
		$wp_customize->add_setting( 'transportex_service_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_transportex_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_transportex_Toggle_Switch_Custom_control( $wp_customize, 'transportex_service_enable',
		   array(
			  'label' => esc_html__( 'Service Enable/Disable' ),
			  'section' => 'services_section'
		   )
		) );

		 //Service Background image
		 $wp_customize->add_setting( 
			'transportex_service_background', array(
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'transportex_service_background', array(
			'label'    => __( 'Background Image', 'transportex' ),
			'section'  => 'services_section',
			'settings' => 'transportex_service_background',
		) ) );

			
		 //Service Overlay Setting
		 $wp_customize->add_setting(
			'transportex_service_overlay_color', array( 'sanitize_callback' => 'sanitize_text_field',
					
		) );

		$wp_customize->add_control( new Transportex_Customize_Alpha_Color_Control( $wp_customize,'transportex_service_overlay_color', array(
			'label'      => __('Overlay Color', 'transportex' ),
			'section'    => 'services_section',
			'palette' => true,
			'settings'   => 'transportex_service_overlay_color',) 
		) );
		
		

		//Service text color setting
		$wp_customize->add_setting(
			'transportex_service_text_color', array( 'sanitize_callback' => 'sanitize_text_field',
			
		) );
		
		$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'transportex_service_text_color', array(
		   'label'      => __('Text Color', 'transportex' ),
			'palette' => true,
			'section' => 'services_section')
		) );
		
		// Service section title
		$wp_customize->add_setting( 'transportex_service_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Why We Best in Business Services','icyclub'),
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_service_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'services_section',
		'type' => 'text',
		));	
		
		//Service section discription
		$wp_customize->add_setting( 'transportex_service_subtitle',array(
		'capability'     => 'edit_theme_options',
		'default' => 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_service_subtitle',array(
		'label'   => __('Description','icyclub'),
		'section' => 'services_section',
		'type' => 'textarea',
		));

		
		if ( class_exists( 'Transportex_Repeater_Control' ) ) {
			$wp_customize->add_setting( 'transportex_service_content', array(
			) );

			$wp_customize->add_control( new Transportex_Repeater_Control( $wp_customize, 'transportex_service_content', array(
				'label'                             => esc_html__( 'Service Content', 'transportex' ),
				'section'                           => 'services_section',
				'add_field_label'                   => esc_html__( 'Add new Service', 'transportex' ),
				'item_name'                         => esc_html__( 'Service', 'transportex' ),
				'customizer_repeater_icon_control' => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_button_text_control' => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_checkbox_control' => true,
				) ) );
		}

		$transportex_service_content_default_value_control = $wp_customize->get_setting( 'transportex_service_content' );
				if ( ! empty( $transportex_service_content_default_value_control ) ) 
				{

					$defaults = array(
						array(
							'fa_icon' => 'fa fa-plane ',
							'service_title'      => esc_html__( 'Air Freight', 'transportex' ),
							'service_desc'       => "looks there isn't anything embarrassing hidden in the middle of text",
							'btnmore'      => __('Read More','transportex'),
							'btnlink'       => '#',
							'open_new_window' => 'no',
							'id'         => 'customizer_repeater_56d7ea7f40b56',
						),
						array(	
							'fa_icon' => 'fa fa-truck',
							'service_title'      => esc_html__( 'Groung Shipping', 'transportex' ),
							'service_desc'       => "looks there isn't anything embarrassing hidden in the middle of text",
							'btnmore'      => __('Read More','transportex'),
							'btnlink'       => '#',
							'open_new_window' => 'no',
							'id'         => 'customizer_repeater_56d7ea7f40b86',
						),
						array(	
							'fa_icon' => 'fa fa-ship',
							'service_title'      => esc_html__( 'Sea Delivery', 'transportex' ),
							'service_desc'       => "looks there isn't anything embarrassing hidden in the middle of text",
							'btnmore'      => __('Read More','transportex'),
							'btnlink'       => '#',
							'open_new_window' => 'no',
							'id'         => 'customizer_repeater_56d7ea7f40b86',
							),
		
					);


					$service_widget_data = get_option('widget_transportex_service_widget', $defaults );

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
								'open_new_tab' => isset($widget_data['open_new_window']) ? $widget_data['open_new_window'] : 0,
							);

						}
						
						$i++;
						
					} 
				$transportex_service_content_default_value_control ->default = json_encode( $arr);

				}

				$wp_customize->add_setting( 'transportex_service_upgrade_to_pro', array(
					'capability'			=> 'edit_theme_options',
				));
				$wp_customize->add_control(
					new transportex_upgrade_notice(
					$wp_customize,
					'transportex_service_upgrade_to_pro',
						array(
							'section'				=> 'services_section',
							'settings'				=> 'transportex_service_upgrade_to_pro',
						)
					)
				);
	
}

add_action( 'customize_register', 'icycp_transportex_service_customize_register' );
endif;


/**
 * Selective refresh for service section
 */
function icycp_transportex_register_service_section_partials( $wp_customize ){

	//Service
	$wp_customize->selective_refresh->add_partial( 'transportex_service_title', array(
		'selector'            => '.ta-service-section .ta-heading h3',
		'settings'            => 'transportex_service_title',
		'render_callback'  => 'icycp_transportex_service_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'transportex_service_subtitle', array(
		'selector'            => '.ta-service-section .ta-heading p',
		'settings'            => 'transportex_service_subtitle',
		'render_callback'  => 'icycp_transportex_service_discription_render_callback',
	
	) );
	
	
}

add_action( 'customize_register', 'icycp_transportex_register_service_section_partials' );


function icycp_transportex_service_title_render_callback() {
	return get_theme_mod( 'transportex_service_title' );
}

function icycp_transportex_service_discription_render_callback() {
	return get_theme_mod( 'transportex_service_subtitle' );
}

//Callout
if ( ! function_exists( 'icycp_transportex_callout_customize_register' ) ) :
function icycp_transportex_callout_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

/* Slider Section */
	$wp_customize->add_section( 'home_callout_section' , array(
		'title'      => __('Callout Section', 'transportex'),
		'panel'  => 'homepage_setting',
		'priority'   => 3,
   	) );
		
		// Enable slider
		
		
		
		$wp_customize->add_setting( 'transportex_callout_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_transportex_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_transportex_Toggle_Switch_Custom_control( $wp_customize, 'transportex_callout_enable',
		   array(
			  'label' => esc_html__( 'Callout Enable/Disable' ),
			  'section' => 'home_callout_section'
		   )
		) );

		//Callout background Image
		$wp_customize->add_setting( 'transportex_callout_background',array('default' => ICYCP_PLUGIN_URL .'inc/transportex/images/callout/callout-back.jpg',
		'sanitize_callback' => 'esc_url_raw'));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'transportex_callout_background',
				array(
					'type'        => 'upload',
					'label' => __('Image','transportex'),
					'settings' =>'transportex_callout_background',
					'section' => 'home_callout_section',
					
				)
			)
		);
		
		
		
		
		//Callout Overlay Color
		$wp_customize->add_setting(
			'transportex_callout_overlay_color', array( 'sanitize_callback' => 'sanitize_text_field','default' => 'rgba(33,177,206,0.5)'
		) );

		$wp_customize->add_control(new Transportex_Customize_Alpha_Color_Control( $wp_customize,'transportex_callout_overlay_color', array(
			'label' => __('Overlay Color','transportex'),
			'palette' => true,
			'section' => 'home_callout_section')
		) );

		//Callout Text Color setting
		$wp_customize->add_setting(
			'transportex_callout_text_color', array( 'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'transportex_callout_text_color', array(
		   'label'      => __('Text Color', 'transportex' ),
			'palette' => true,
			'section' => 'home_callout_section')
		) );
		
		
		// callout title
		$wp_customize->add_setting( 'transportex_callout_title',array(
		'default' => __('Reach Your Place Sure & Safe','transportex'),
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_callout_title',array(
		'label'   => __('Title','transportex'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));	
		
		//callout description
		$wp_customize->add_setting( 'transportex_callout_description',array(
		'default' => 'We take care with merchandise and deliver your order where you are on time',
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_callout_description',array(
		'label'   => __('Description','transportex'),
		'section' => 'home_callout_section',
		'type' => 'textarea',
		));
		
		
		// callout button text
		$wp_customize->add_setting( 'transportex_callout_button_one_label',array(
		'default' => __('Explore Now','transportex'),
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_callout_button_one_label',array(
		'label'   => __('Button Text','transportex'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));
		
		// Callout button link
		$wp_customize->add_setting( 'transportex_callout_button_one_link',array(
		'default' => '#',
		'sanitize_callback' => 'esc_url_raw',
		// 'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_callout_button_one_link',array(
		'label'   => __('Button Link','transportex'),
		'section' => 'home_callout_section',
		'type' => 'text',
		));
		
		// Callout button target
		$wp_customize->add_setting(
		'transportex_callout_button_one_target', 
			array(
			'default'        => false,
			'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		));
		$wp_customize->add_control('transportex_callout_button_one_target', array(
			'label'   => __('Open link in new tab', 'transportex'),
			'section' => 'home_callout_section',
			'type' => 'checkbox',
		));

		//Callout Button Two Label Setting	
		$wp_customize->add_setting(
			'transportex_callout_button_two_label', array(
			'default' => __('Buy Now!','transportex'),
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'transportex_template_sanitize_html',
			'transport'         => $selective_refresh,
		) );	
		$wp_customize->add_control( 
			'transportex_callout_button_two_label', array(
			'label' => __('Button Text','transportex'),
			'section' => 'home_callout_section',
			'type' => 'text',
		) );	

		//Callout Button Two Link Setting
		$wp_customize->add_setting(
			'transportex_callout_button_two_link', array(
			'default' => '#',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		) );	
		$wp_customize->add_control( 
			'transportex_callout_button_two_link', array(
			'label' => __('Button Link','transportex'),
			'type' => 'text',
			'section' => 'home_callout_section',
		) );	

		//Callout Button Two Target Setting
		$wp_customize->add_setting(
			'transportex_callout_button_two_target', array(
			'default' => 'true',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		) );	
		$wp_customize->add_control( 
			'transportex_callout_button_two_target', array(
			'label' => __('Open link in a new tab','transportex'),
			'section' => 'home_callout_section',
			'type' => 'checkbox',
		) );
		
		
}

add_action( 'customize_register', 'icycp_transportex_callout_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_transportex_register_callout_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'transportex_callout_title', array(
		'selector'            => '.ta-callout .ta-callout-inner h3',
		'settings'            => 'transportex_callout_title',
		'render_callback'  => 'icycp_transportex_callout_section_title_render_callback',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'transportex_callout_description', array(
		'selector'            => '.ta-callout p',
		'settings'            => 'transportex_callout_description',
		'render_callback'  => 'icycp_transportex_callout_section_desc_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'transportex_callout_button_one_label', array(
		'selector'            => '.ta-callout .btn-theme-two',
		'settings'            => 'transportex_callout_button_one_label',
		'render_callback'  => 'icycp_transportex_callout_btn_txt_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'transportex_callout_button_two_label', array(
		'selector'            => '.ta-callout .btn-theme',
		'settings'            => 'transportex_callout_button_two_label',
		'render_callback'  => 'icycp_transportex_callout_btn_txt_render_callback2',
	
	) );
}

add_action( 'customize_register', 'icycp_transportex_register_callout_section_partials' );


function icycp_transportex_callout_section_title_render_callback() {
	return get_theme_mod( 'transportex_callout_title' );
}

function icycp_transportex_callout_section_desc_render_callback() {
	return get_theme_mod( 'transportex_callout_description' );
}

function icycp_transportex_callout_btn_txt_render_callback() {
	return get_theme_mod( 'transportex_callout_button_one_label' );
}

function icycp_transportex_callout_btn_txt_render_callback2() {
	return get_theme_mod( 'transportex_callout_button_two_label' );
}

//Project Section
if ( ! function_exists( 'icycp_transportex_calltoaction_customizer' ) ) :
function icycp_transportex_calltoaction_customizer( $wp_customize ) {
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	/* project Section */
	$wp_customize->add_section( 'calltoaction_section' , array(
			'title'      => __('Call To Action Section', 'icyclub'),
			'panel'  => 'homepage_setting',
			'priority'   => 2,
		) );
		
		$wp_customize->add_setting( 'calltoaction_section_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_transportex_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_transportex_Toggle_Switch_Custom_control( $wp_customize, 'calltoaction_section_enable',
		   array(
			  'label' => esc_html__( 'Calltoaction Enable/Disable' ),
			  'section' => 'calltoaction_section'
		   )
		) );

		//Call to action Background image
		$wp_customize->add_setting( 'transportex_calltoaction_background', array(
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 
			'transportex_calltoaction_background', array(
			'label'    => __( 'Background Image', 'transportex' ),
			'section'  => 'calltoaction_section',
			'settings' => 'transportex_calltoaction_background',) 
		) );
	   
		//Callto-action overlay color
		$wp_customize->add_setting(
			'transportex_calltoaction_overlay_color', array( 
				'sanitize_callback' => 'sanitize_text_field',
				'default' => '#50b9ce',
		) );
		
		$wp_customize->add_control(new Transportex_Customize_Alpha_Color_Control( $wp_customize,'transportex_calltoaction_overlay_color', array(
			'label'      => __('Overlay Color', 'transportex' ),
			'palette' => true,
			'section' => 'calltoaction_section')
		) );

		//product Text Color setting
		$wp_customize->add_setting(
			'transportex_calltoaction_text_color', array( 'sanitize_callback' => 'sanitize_text_field',
			
		) );
		
		$wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'transportex_calltoaction_text_color', array(
		   'label'      => __('Text Color', 'transportex' ),
			'palette' => true,
			'section' => 'calltoaction_section')
		) );
		
		// project section title
		$wp_customize->add_setting( 'transportex_calltoaction_title',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'default' => __('We help world Wide From our fleet Send it anywhere','icyclub'),
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_calltoaction_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'calltoaction_section',
		'type' => 'text',
		));	
		
		//project section discription
		$wp_customize->add_setting( 'transportex_calltoaction_subtitle',array(
		'capability'     => 'edit_theme_options',
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'default' => '',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_calltoaction_subtitle',array(
		'label'   => __('Description','icyclub'),
		'section' => 'calltoaction_section',
		'type' => 'textarea',
		));
		
		
		// callout button text
		$wp_customize->add_setting( 'transportex_calltoaction_button_one_label',array(
		'default' => __('Lets Start','transportex'),
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_calltoaction_button_one_label',array(
		'label'   => __('Button Text','transportex'),
		'section' => 'calltoaction_section',
		'type' => 'text',
		));
		
		// Callout button link
		$wp_customize->add_setting( 'transportex_calltoaction_button_one_link',array(
		'default' => '#',
		'sanitize_callback' => 'esc_url_raw',
		//'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'transportex_calltoaction_button_one_link',array(
		'label'   => __('Button Link','transportex'),
		'section' => 'calltoaction_section',
		'type' => 'text',
		));
		
		// Callout button target
		$wp_customize->add_setting(
		'transportex_calltoaction_button_one_target', 
			array(
			'default'        => false,
			'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		));
		$wp_customize->add_control('transportex_calltoaction_button_one_target', array(
			'label'   => __('Open link in new tab', 'transportex'),
			'section' => 'calltoaction_section',
			'type' => 'checkbox',
		));
		
		

}		
add_action( 'customize_register', 'icycp_transportex_calltoaction_customizer' );
endif;

/**
 * Add selective refresh for project section.
 */
function icycp_transportex_register_calltoaction_section_partials( $wp_customize ){

	
	//calltoaction section
	$wp_customize->selective_refresh->add_partial( 'transportex_calltoaction_title', array(
		'selector'            => '.ta-calltoaction-box-info h5',
		'settings'            => 'transportex_calltoaction_title',
		'render_callback'  => 'icycp_transportex_transportex_calltoaction_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'transportex_calltoaction_subtitle', array(
		'selector'            => '.ta-calltoaction-box-info p',
		'settings'            => 'transportex_calltoaction_subtitle',
		'render_callback'  => 'icycp_transportex_transportex_calltoaction_subtitle_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'transportex_calltoaction_button_one_label', array(
		'selector'            => '.ta-calltoaction .btn-theme',
		'settings'            => 'transportex_calltoaction_button_one_label',
		'render_callback'  => 'icycp_transportex_calltoaction_section_button_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_transportex_register_calltoaction_section_partials' );

//Project Section
function icycp_transportex_transportex_calltoaction_title_render_callback() {
	return get_theme_mod( 'transportex_calltoaction_title' );
}

function icycp_transportex_transportex_calltoaction_subtitle_render_callback() {
	return get_theme_mod( 'transportex_calltoaction_subtitle' );
}

function icycp_transportex_calltoaction_section_button_render_callback() {
	return get_theme_mod( 'transportex_calltoaction_button_one_label' );
}




if ( ! function_exists( 'icycp_transportex_testimonial_customize_register' ) ) :
function icycp_transportex_testimonial_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	

/* Testimonial Section */
	$wp_customize->add_section( 'testimonial_section' , array(
			'title'      => __('Testimonial Section', 'icyclub'),
			'panel'  => 'homepage_setting',
			'priority'   => 7,
		) );
		
		// Enable testimonial section
		$wp_customize->add_setting( 'testimonial_section_enable',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_transportex_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_transportex_Toggle_Switch_Custom_control( $wp_customize, 'testimonial_section_enable',
		   array(
			  'label' => esc_html__( 'Testimonial Enable/Disable' ),
			  'section' => 'testimonial_section'
		   )
		) );
		
		
		
		//Testimonial Background Image
			$wp_customize->add_setting( 'testimonial_callout_bg',array(
			'sanitize_callback' => 'esc_url_raw', 
			// 'transport' => $selective_refresh,
		 ));
			
			
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'testimonial_callout_bg', array(
			  'label'    => __( 'Background Image', 'icyclub' ),
			  'section'  => 'testimonial_section',
			  'settings' => 'testimonial_callout_bg',
			) ) );
			
			// Image overlay
		$wp_customize->add_setting( 'testimonial_bg_overlay_enable', array(
			'default' => true,
			'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control('testimonial_bg_overlay_enable', array(
			'label'    => __('Enable/disable testimonial background overlay', 'icyclub' ),
			'section'  => 'testimonial_section',
			'type' => 'checkbox',
		) );
		
		
		//Testimonial Background Overlay Color
		$wp_customize->add_setting( 'testimonial_overlay_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => '',
            ) );	
            
		$wp_customize->add_control(new transportex_Customize_Alpha_Color_Control( $wp_customize,'testimonial_overlay_color', array(
			'label'      => __('Overlay Color','icyclub' ),
			'palette' => true,
			'section' => 'testimonial_section')
		) );
			
		//Testimonial Background Overlay Color
		$wp_customize->add_setting( 'testimonial_text_color', array(
			'sanitize_callback' => 'sanitize_text_field',
			'default' => '',
            ) );	
            
		$wp_customize->add_control(new transportex_Customize_Alpha_Color_Control( $wp_customize,'testimonial_text_color', array(
			'label'      => __('Text Color','icyclub' ),
			'palette' => true,
			'section' => 'testimonial_section')
		) );	
		
		// testimonial section title
		$wp_customize->add_setting( 'testimonial_section_title',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Our Clients Says','transportex'),
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_section_title',array(
		'label'   => __('Title','transportex'),
		'section' => 'testimonial_section',
		'type' => 'text',
		));	
		
		//testimonial section discription
		$wp_customize->add_setting( 'testimonial_section_discription',array(
		'capability'     => 'edit_theme_options',
		'default'=> '',
		'sanitize_callback' => 'icycp_transportex_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'testimonial_section_discription',array(
		'label'   => __('Description','icyclub'),
		'section' => 'testimonial_section',
		'type' => 'textarea',
		));
		
		if ( class_exists( 'Transportex_Repeater_Control' ) ) {
			$wp_customize->add_setting( 'transportex_testimonial_content', array(
			) );
	
			$wp_customize->add_control( new Transportex_Repeater_Control( $wp_customize, 'transportex_testimonial_content', array(
				'label'                                => esc_html__( 'Testimonial Content', 'bussinessup' ),
				'section'                              => 'testimonial_section',
				'add_field_label'                      => esc_html__( 'Add new Testimonial', 'bussinessup' ),
				'item_name'                            => esc_html__( 'Testimonial', 'bussinessup' ),
				'customizer_repeater_test_title_control' => true,
				'customizer_repeater_subtitle_control' =>true,
				'customizer_repeater_text_control'     => true,
				'customizer_repeater_designation_control' => true,
				'customizer_repeater_image_control'    => true,
				) ) );
			}
	
			$bussinessup_service_content_default_value_control = $wp_customize->get_setting( 'transportex_testimonial_content' );
					if ( ! empty( $bussinessup_service_content_default_value_control ) ) 
					{
	
						$defaults = array(
							array(
					'subtitle'      => 'Linda Guthrie',
					'text'       => 'We have put the apim bol, temporarily so that we looking quick do your web search manager caught you and you are fured eat our own dog food golden goose',
					'designation' => __('UI Developer','transportex'),
					'test_title'      => esc_html__( 'Professional Team', 'transportex' ),
					'link'       => '#',
					'image_url'  => ICYCP_PLUGIN_URL .'inc/transportex/images/testimonial/testi1.jpg',
					'open_new_tab' => 'no',
					'id'         => 'customizer_repeater_56d7ea7f40b96',
					
					),
					array(
					'subtitle'      => 'Matt John',
					'text'       => 'but if you want to motivate these clowns, try less carrot and more stick you better eat a reality sandwich before you walk back in that boardroom.',
					'designation' => __('Manager','transportex'),
					'test_title'      => esc_html__( 'Professional Team', 'transportex' ),
					'link'       => '#',
					'image_url'  => ICYCP_PLUGIN_URL .'inc/transportex/images/testimonial/testi2.jpg',
					'open_new_tab' => 'no',
					'id'         => 'customizer_repeater_56d7ea7f40b97',
					),
							

						);
	
						
					$bussinessup_service_content_default_value_control ->default = json_encode( $defaults);
	
					}

					$wp_customize->add_setting( 'transportex_testimonial_upgrade_to_pro', array(
						'capability'			=> 'edit_theme_options',
					));
					$wp_customize->add_control(
						new transportex_upgrade_notice(
						$wp_customize,
						'transportex_testimonial_upgrade_to_pro',
							array(
								'section'				=> 'testimonial_section',
								'settings'				=> 'transportex_testimonial_upgrade_to_pro',
							)
						)
					);	
		
		
		
}

add_action( 'customize_register', 'icycp_transportex_testimonial_customize_register' );
endif;

/**
 * Selective refresh for testimonial section
 */
function icycp_transportex_register_testimonial_section_partials( $wp_customize ){


	
	//Testimonial
	$wp_customize->selective_refresh->add_partial( 'testimonial_callout_background', array(
		'selector'            => 'section.testimonial-section',
		'settings'            => 'testimonial_callout_background',
	
	) );
	
	//Testimonial one
	$wp_customize->selective_refresh->add_partial( 'testimonial_section_title', array(
		'selector'            => '.testimonials-section .ta-heading h3',
		'settings'            => 'testimonial_section_title',
		'render_callback'  => 'icycp_transportex_testimonial_section_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'testimonial_section_discription', array(
		'selector'            => '.testimonials-section .ta-heading p',
		'settings'            => 'testimonial_section_discription',
		'render_callback'  => 'icycp_transportex_testimonial_section_discription_render_callback',
	
	) );
	
	
	
	
	
}

add_action( 'customize_register', 'icycp_transportex_register_testimonial_section_partials' );

if ( ! function_exists( 'icycp_transportex_latest_news_customize_register' ) ) :
	function icycp_transportex_latest_news_customize_register($wp_customize){
	$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	
	
	/* --------------------------------------
            =========================================
            Latest News Section
            =========================================
            -----------------------------------------*/
            // add section to manage Latest News
            $wp_customize->add_section(
                'transportex_news_section_settings', array(
                'title' => __('Latest News settings','transportex'),
                'description' => '',
                'panel'  => 'homepage_setting'
            ) );
            
				// Enable testimonial section
			$wp_customize->add_setting( 'transportex_news_enable',
				array(
					'default' => 1,
					'transport' => 'refresh',
					'sanitize_callback' => 'icycp_transportex_switch_sanitization'
				)
			);
				
			$wp_customize->add_control( new Icycp_transportex_Toggle_Switch_Custom_control( $wp_customize, 'transportex_news_enable',
				array(
					'label' => esc_html__( 'Latest News Enable/Disable' ),
					'section' => 'transportex_news_section_settings'
				)
			) );

            //Latest News Background Image
            $wp_customize->add_setting( 
                'transportex_news_background', array(
                'sanitize_callback' => 'esc_url_raw',
            ) );
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 
                'transportex_news_background', array(
                'label'    => __( 'Background Image', 'transportex' ),
                'section'  => 'transportex_news_section_settings',
                'settings' => 'transportex_news_background', ) 
            ) );
            
            //Latest News Overlay color
            $wp_customize->add_setting(
                'transportex_news_overlay_color', array( 'sanitize_callback' => 'sanitize_text_field',
            ) );
            
            $wp_customize->add_control(new Transportex_Customize_Alpha_Color_Control( $wp_customize,'transportex_news_overlay_color', array(
                'label' => __('Overlay Color', 'transportex' ),
                'palette' => true,
                'section' => 'transportex_news_section_settings')
            ) );

            //Latest News text color
            $wp_customize->add_setting(
                'transportex_news_text_color', array( 'sanitize_callback' => 'sanitize_text_field',
            ) );
            
            $wp_customize->add_control(new WP_Customize_Color_Control( $wp_customize,'transportex_news_text_color', array(
                'label' => __('Text Color', 'transportex' ),
                'palette' => true,
                'section' => 'transportex_news_section_settings')
            ) );

            // hide meta content
            $wp_customize->add_setting(
                'disable_news_meta', array(
                'default' => false,
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ) );
            $wp_customize->add_control(
                'disable_news_meta', array(
                'label' => __('Hide post meta from News section','transportex'),
                'section' => 'transportex_news_section_settings',
                'type' => 'checkbox',
            ) );

            // Latest News Title Setting
            $wp_customize->add_setting(
                'transportex_news_title', array(
                'default' => __('Latest News','transportex'),
                'capability'     => 'edit_theme_options',
                'sanitize_callback' => 'transportex_template_sanitize_html',
				'transport'         => $selective_refresh,
            ) );    
            $wp_customize->add_control( 
                'transportex_news_title',array(
                'label'   => __('Title','transportex'),
                'section' => 'transportex_news_section_settings',
                'type' => 'text',
            ) );

            // Latest News Subtitle Setting
            $wp_customize->add_setting(
                'transportex_news_subtitle', array(
                'default' => 'laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'transportex_template_sanitize_html',
				'transport'         => $selective_refresh,
            ) );  
            $wp_customize->add_control( 
                'transportex_news_subtitle',array(
                'label'   => __('Description','transportex'),
                'section' => 'transportex_news_section_settings',
                'type' => 'textarea',
            ) );    

            //Select number of latest news on front page
            $wp_customize->add_setting(
                'news_select', array(
                'default' =>'3',
                'sanitize_callback' => 'sanitize_text_field',
            ) );

            $wp_customize->add_control(
                'news_select', array(
                'type' => 'select',
                'label' => __('Select Number of Post','transportex'),
                'section' => 'transportex_news_section_settings',
                'choices' => array(3=>3, 6=>6, 9=>9, 12=>12, 15=>15, 18=>18, 21=>21),
            ) );

			
			function transportex_template_sanitize_text( $input ) {

			return wp_kses_post( force_balance_tags( $input ) );

			}
	
			function transportex_template_sanitize_html( $input ) {

			return force_balance_tags( $input );

			}	
			
			
	}
	
	add_action( 'customize_register', 'icycp_transportex_latest_news_customize_register' );
	endif;

	/**
 * Selective refresh for Latest News section
 */
function icycp_transportex_register_latest_news_section_partials( $wp_customize ){

	$wp_customize->selective_refresh->add_partial( 'transportex_news_title', array(
		'selector'            => '.ta-blog-section .ta-heading h3',
		'settings'            => 'transportex_news_title',
		'render_callback'  => 'icycp_transportex_transportex_news_title_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'transportex_news_subtitle', array(
		'selector'            => '.ta-blog-section .ta-heading p',
		'settings'            => 'transportex_news_subtitle',
		'render_callback'  => 'icycp_transportex_transportex_news_subtitle_render_callback',
	
	) );
	
	
	
}

add_action( 'customize_register', 'icycp_transportex_register_latest_news_section_partials' );

//Testimonial Section
function icycp_transportex_testimonial_section_title_render_callback() {
	return get_theme_mod( 'testimonial_section_title' );
}

function icycp_transportex_testimonial_section_discription_render_callback() {
	return get_theme_mod( 'testimonial_section_discription' );
}

//Latest News Section
function icycp_transportex_transportex_news_title_render_callback() {
	return get_theme_mod( 'transportex_news_title' );
}

function icycp_transportex_transportex_news_subtitle_render_callback() {
	return get_theme_mod( 'transportex_news_subtitle' );
}



if ( ! function_exists( 'icycp_transportex_switch_sanitization' ) ) {
		function icycp_transportex_switch_sanitization( $input ) {
			if ( true === $input ) {
				return 1;
			} else {
				return 0;
			}
		}
}

//Sanatize text validation
function icycp_transportex_home_page_sanitize_text( $input ) {

		return wp_kses_post( force_balance_tags( $input ) );
}