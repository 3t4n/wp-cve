<?php
if ( ! function_exists( 'spiceb_cloudpress_funfact_customize_register' ) ) :
function spiceb_cloudpress_funfact_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';	
	
		/* funfacts section */
		$wp_customize->add_section( 'funfacts_section' , array(
			'title'      => __('Funfact Settings', 'spicebox'),
			'panel'  => 'section_settings',
			'priority'   => 5,
		) );
		
		// Enable funfact
		$wp_customize->add_setting( 'home_funfact_section_enabled' , array( 'default' => 'on') );
		$wp_customize->add_control(	'home_funfact_section_enabled' , array(
				'label'    => __( 'Enable funfacts on homepage', 'spicebox' ),
				'section'  => 'funfacts_section',
				'type'     => 'radio',
				'choices' => array(
					'on'=>__('ON', 'spicebox'),
					'off'=>__('OFF', 'spicebox')
				)
		));	
		
		if ( class_exists( 'Cloudpress_Repeater' ) ) {
			$wp_customize->add_setting( 'cloudpress_funfact_content', array(
			) );

			$wp_customize->add_control( new Cloudpress_Repeater( $wp_customize, 'cloudpress_funfact_content', array(
				'label'                             => esc_html__( 'Funfact content', 'spicebox' ),
				'section'                           => 'funfacts_section',
			'priority'                          => 10,
			'add_field_label'                   => esc_html__( 'Add new Funfact', 'cloudpress' ),
			'item_name'                         => esc_html__( 'Funfact', 'cloudpress' ),
			'customizer_repeater_icon_control'  => true,
			'customizer_repeater_title_control' => true,
			'customizer_repeater_text_control'  => true,
				) ) );
		}	

		//plus Button
		class Honyepress_funfacts__section_upgrade extends WP_Customize_Control {
			public function render_content() { ?>
				<h3 class="customizer_cloudpressfunfact_upgrade_section" style="display: none;">		<?php _e('To add More funfact? Then','spicebox'); ?><a href="<?php echo esc_url( 'https://spicethemes.com/cloudpress-pro' ); ?>" target="_blank">
					<?php _e('Upgrade to Plus','spicebox'); ?> </a>  
				</h3>
			<?php
			}
		}
		
		$wp_customize->add_setting( 'cloudpress_funfact_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
		));
		$wp_customize->add_control(
			new Honyepress_funfacts__section_upgrade(
			$wp_customize,
			'cloudpress_funfact_upgrade_to_pro',
				array(
					'section'				=> 'funfacts_section',
					'settings'				=> 'cloudpress_funfact_upgrade_to_pro',
				)
			)
		);
		//limit
		class Honyepress_hidden_funfact_content extends WP_Customize_Control {
			public function render_content() { ?>
				<input type="hidden" value="4" id="cloudpress_funfact_limit"/>
			<?php
			}
		}
		$wp_customize->add_setting( 'cloudpress_hidden_funfact_to_pro', array(
			'capability'			=> 'edit_theme_options',
		));
		$wp_customize->add_control(
			new Honyepress_hidden_funfact_content(
			$wp_customize,
			'cloudpress_hidden_to_pro',
				array(
					'section'				=> 'funfacts_section',
					'settings'				=> 'cloudpress_hidden_funfact_to_pro',
				)
			)
		);
}
add_action( 'customize_register', 'spiceb_cloudpress_funfact_customize_register' );
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_cloudpress_register_home_funfact_section_partials( $wp_customize ){

	//Slider section
	$wp_customize->selective_refresh->add_partial( 'cloudpress_funfact_content', array(
		'selector'            => '.section-module.funfact #funfacts',
		'settings'            => 'cloudpress_funfact_content',
	
	) );
	
	//Slider section
	// $wp_customize->selective_refresh->add_partial( 'home_funfact_section_title', array(
	// 	'selector'            => '.section-module.funfacts .section-subtitle',
	// 	'settings'            => 'home_funfact_section_title',
	// 	'render_callback'  => 'spiceb_cloudpress_funfact_section_title_render_callback',
	
	// ) );
	
	// $wp_customize->selective_refresh->add_partial( 'home_funfact_section_discription', array(
	// 	'selector'            => '.section-module.funfacts  .section-title',
	// 	'settings'            => 'home_funfact_section_discription',
	// 	'render_callback'  => 'spiceb_cloudpress_funfact_section_discription_render_callback',
	
	// ) );
	
}
add_action( 'customize_register', 'spiceb_cloudpress_register_home_funfact_section_partials' );

// function spiceb_cloudpress_funfact_section_title_render_callback() {
// 	return get_theme_mod( 'home_funfact_section_title' );
// }

// function spiceb_cloudpress_funfact_section_discription_render_callback() {
// 	return get_theme_mod( 'home_funfact_section_discription' );
// }
?>