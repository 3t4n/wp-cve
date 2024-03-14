<?php
if ( ! function_exists( 'spiceb_cloudpress_service_customize_register' ) ) :
function spiceb_cloudpress_service_customize_register($wp_customize){
$selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

		/* Services section */
		$wp_customize->add_section( 'services_section' , array(
			'title'      => __('Service settings', 'spicebox'),
			'panel'  => 'section_settings',
			'priority'   => 3,
		) );

		// Enable service
		$wp_customize->add_setting( 'home_service_section_enabled' , array( 'default' => 'on') );
		$wp_customize->add_control(	'home_service_section_enabled' , array(
				'label'    => __( 'Enable Services on homepage', 'spicebox' ),
				'section'  => 'services_section',
				'type'     => 'radio',
				'choices' => array(
					'on'=>__('ON', 'spicebox'),
					'off'=>__('OFF', 'spicebox')
				)
		));

		// Service section title
		$wp_customize->add_setting( 'home_service_section_title',array(
		'capability'     => 'edit_theme_options',
		'default' =>  __('Etiam et Urna?','spicebox'),
		'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));
		$wp_customize->add_control( 'home_service_section_title',array(
		'label'   => __('Title','spicebox'),
		'section' => 'services_section',
		'type' => 'text',
		));

		//room section discription
		$wp_customize->add_setting( 'home_service_section_discription',array(
		'capability'     => 'edit_theme_options',
		'default' => __('Fusce Sed Massa','spicebox'),
		'sanitize_callback' => 'spiceb_cloudpress_home_page_sanitize_text',
		'transport'         => $selective_refresh,
		));
		$wp_customize->add_control( 'home_service_section_discription',array(
		'label'   => __('Description','spicebox'),
		'section' => 'services_section',
		'type' => 'textarea',
		));

		if ( class_exists( 'Cloudpress_Repeater' ) ) {
			$wp_customize->add_setting( 'cloudpress_service_content', array(
			) );

			$wp_customize->add_control( new Cloudpress_Repeater( $wp_customize, 'cloudpress_service_content', array(
				'label'                             => esc_html__( 'Service content', 'spicebox' ),
				'section'                           => 'services_section',
				'priority'                          => 10,
				'add_field_label'                   => esc_html__( 'Add new Service', 'spicebox' ),
				'item_name'                         => esc_html__( 'Service', 'spicebox' ),
				'customizer_repeater_icon_control'  => true,
				'customizer_repeater_title_control' => true,
				'customizer_repeater_text_control'  => true,
				'customizer_repeater_link_control'  => true,
				'customizer_repeater_checkbox_control' => true,
				'customizer_repeater_image_control' => true,
				) ) );
		}

		//plus Button
		class Honyepress_services__section_upgrade extends WP_Customize_Control {
			public function render_content() { ?>
				<h3 class="customizer_cloudpressservice_upgrade_section" style="display: none;">		<?php _e('To add More Service? Then','spicebox'); ?><a href="<?php echo esc_url( 'https://spicethemes.com/cloudpress-pro' ); ?>" target="_blank">
					<?php _e('Upgrade to Plus','spicebox'); ?> </a>
				</h3>
			<?php
			}
		}

		$wp_customize->add_setting( 'cloudpress_service_upgrade_to_pro', array(
			'capability'			=> 'edit_theme_options',
		));
		$wp_customize->add_control(
			new Honyepress_services__section_upgrade(
			$wp_customize,
			'cloudpress_service_upgrade_to_pro',
				array(
					'section'				=> 'services_section',
					'settings'				=> 'cloudpress_service_upgrade_to_pro',
				)
			)
		);

		// limit
		class Honyepress_hidden_service_content extends WP_Customize_Control {
			public function render_content() { ?>
				<input type="hidden" value="3" id="cloudpress_service_limit"/>
			<?php
			}
		}
		$wp_customize->add_setting( 'cloudpress_hidden_service_to_pro', array(
			'capability'			=> 'edit_theme_options',
		));
		$wp_customize->add_control(
			new Honyepress_hidden_service_content(
			$wp_customize,
			'cloudpress_hidden_service_to_pro',
				array(
					'section'				=> 'services_section',
				)
			)
		);
}
add_action( 'customize_register', 'spiceb_cloudpress_service_customize_register' );
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_cloudpress_register_home_service_section_partials( $wp_customize ){

	//Slider section
	$wp_customize->selective_refresh->add_partial( 'cloudpress_service_content', array(
		'selector'            => '.service-section #service_content_section',
		'settings'            => 'cloudpress_service_content',

	) );

	//Slider section
	$wp_customize->selective_refresh->add_partial( 'home_service_section_title', array(
		'selector'            => '.section-module.services .section-subtitle',
		'settings'            => 'home_service_section_title',
		'render_callback'  => 'spiceb_cloudpress_service_section_title_render_callback',

	) );

	$wp_customize->selective_refresh->add_partial( 'home_service_section_discription', array(
		'selector'            => '.section-module.services  .section-title',
		'settings'            => 'home_service_section_discription',
		'render_callback'  => 'spiceb_cloudpress_service_section_discription_render_callback',

	) );

}
add_action( 'customize_register', 'spiceb_cloudpress_register_home_service_section_partials' );

function spiceb_cloudpress_service_section_title_render_callback() {
	return get_theme_mod( 'home_service_section_title' );
}

function spiceb_cloudpress_service_section_discription_render_callback() {
	return get_theme_mod( 'home_service_section_discription' );
}
?>
