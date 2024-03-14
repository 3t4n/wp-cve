<?php if ( ! function_exists( 'icycp_industryup_slider_customize_register' ) ) :
function icycp_industryup_slider_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

class Icycp_Industryup_Toggle_Switch_Custom_control extends WP_Customize_Control {
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


/**
 * Custom Controls of theme
 *
 * @since 1.0.0
 *
 * @see WP_Customize_Control
 */

class Icycp_Section_Title extends WP_Customize_Control {
    public $type = 'section-title';
    public $label = '';
    public $description = '';

	public function render_content() {
        ?>
        <h3><?php echo esc_html( $this->label ); ?></h3>
        <?php if (!empty($this->description)) { ?>
            <span class="customize-control-description"><?php echo esc_html($this->description); ?></span>
        <?php } ?>
        <?php
    }
}


/* Slider Section */
	$wp_customize->add_section( 'slider_section' , array(
		'title'      => __('Slider settings', 'icyclub'),
		'panel'  => 'homepage_sections',
		'priority'   => 1,
   	) );
		
		// Enable slider
		$wp_customize->add_setting( 'home_page_slider_enabled',
		   array(
			  'default' => 1,
			  'transport' => 'refresh',
			  'sanitize_callback' => 'icycp_industryup_switch_sanitization'
		   )
		);
		 
		$wp_customize->add_control( new Icycp_Industryup_Toggle_Switch_Custom_control( $wp_customize, 'home_page_slider_enabled',
		   array(
			  'label' => esc_html__( 'Slider Enable/Disable' ),
			  'section' => 'slider_section'
		   )
		) );

		
		//Slider Image
		$wp_customize->add_setting( 'slider_image',array('default' => ICYCP_PLUGIN_URL .'inc/industryup/images/slider/banner.jpg',
		'sanitize_callback' => 'esc_url_raw', 
		//'transport' => $selective_refresh,
	));
 
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'slider_image',
				array(
					'type'        => 'upload',
					'label' => __('Image','icyclub'),
					'settings' =>'slider_image',
					'section' => 'slider_section',
					
				)
			)
		);
		
		// Slider title
		$wp_customize->add_setting( 'slider_title',array(
		'default' => __('We are','icyclub'),
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'slider_title',array(
		'label'   => __('Title','icyclub'),
		'section' => 'slider_section',
		'type' => 'text',
		));

		$wp_customize->add_setting( 'slider_subtitle',array(
		'default' => __('We are Best in Premium Consulting Services','icyclub'),
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'slider_subtitle',array(
		'label'   => __('SubTitle','icyclub'),
		'section' => 'slider_section',
		'type' => 'text',
		));	
		
		//Slider discription
		$wp_customize->add_setting( 'slider_discription',array(
		'default' => 'One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin..',
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'slider_discription',array(
		'label'   => __('Description','icyclub'),
		'section' => 'slider_section',
		'type' => 'textarea',
		));
		
		
		// Slider button text
		$wp_customize->add_setting( 'slider_btn_txt',array(
		'default' => __('Read more','icyclub'),
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'slider_btn_txt',array(
		'label'   => __('Button Text','icyclub'),
		'section' => 'slider_section',
		'type' => 'text',
		));
		
		// Slider button link
		$wp_customize->add_setting( 'slider_btn_link',array(
		'default' => '#',
		'sanitize_callback' => 'icycp_industryup_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));	
		$wp_customize->add_control( 'slider_btn_link',array(
		'label'   => __('Button Link','icyclub'),
		'section' => 'slider_section',
		'type' => 'text',
		));
		
		// Slider button target
		$wp_customize->add_setting(
		'slider_btn_target', 
			array(
			'default'        => false));
		$wp_customize->add_control('slider_btn_target', array(
			'label'   => __('Open link in new tab', 'icyclub'),
			'section' => 'slider_section',
			'type' => 'checkbox',
		));
		
}

add_action( 'customize_register', 'icycp_industryup_slider_customize_register' );
endif;


/**
 * Add selective refresh for Front page section section controls.
 */
function icycp_industryup_register_slider_section_partials( $wp_customize ){

	
	
	$wp_customize->selective_refresh->add_partial( 'slider_image', array(
		'selector'            => '.consultup-slider-warraper .item figure',
		'settings'            => 'slider_image',
	
	) );
	
	//Slider section
	$wp_customize->selective_refresh->add_partial( 'slider_title', array(
		'selector'            => '.slide-caption h6',
		'settings'            => 'slider_title',
		'render_callback'  => 'icycp_consultco_slider_title_render_callback',
	
	) );

	$wp_customize->selective_refresh->add_partial( 'slider_subtitle', array(
		'selector'            => '.slide-caption h2',
		'settings'            => 'slider_subtitle',
		'render_callback'  => 'icycp_consultco_slider_subtitle_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'slider_discription', array(
		'selector'            => '.slide-caption div p',
		'settings'            => 'slider_discription',
		'render_callback'  => 'icycp_industryup_slider_iscription_render_callback',
	
	) );
	
	$wp_customize->selective_refresh->add_partial( 'slider_btn_txt', array(
		'selector'            => '.btn-tislider',
		'settings'            => 'slider_btn_txt',
		'render_callback'  => 'icycp_industryup_slider_btn_render_callback',
	
	) );
}

add_action( 'customize_register', 'icycp_industryup_register_slider_section_partials' );


function icycp_consultco_slider_title_render_callback() {
	return get_theme_mod( 'slider_title' );
}

function icycp_consultco_slider_subtitle_render_callback() {
	return get_theme_mod( 'slider_subtitle' );
}

function icycp_industryup_slider_iscription_render_callback() {
	return get_theme_mod( 'slider_discription' );
}

function icycp_industryup_slider_btn_render_callback() {
	return get_theme_mod( 'slider_btn_txt' );
}
