<?php

if ( ! function_exists( 'slider_default_json' ) ) {

	function slider_default_json() {
		return json_encode(
			array(
				array(
					'image_url' => PL_PLUGIN_URL . 'assets/images/slider2.jpg',
					'title'     => __( 'Exceeding Your Expectations', 'pluglab' ),
					'subtitle'  => __( 'Business we operate in is like an intricate game of strategy and chess, where every move counts and you keep score with money', 'pluglab' ),
					'text'      => 'Curabitur',
					'text2'     => 'Phasellus',
					'link'      => '#',
					'link2'     => '#',
					'newtab'    => true,
					'id'        => 'customizer_repeater_57y7op1f70t00',
				),
				array(
					'image_url' => PL_PLUGIN_URL . 'assets/images/corposet/slide2.jpg',
					'title'     => __( 'Future Is Bright', 'pluglab' ),
					'subtitle'  => __( 'Business we operate in is like an intricate game of strategy and chess, where every move counts and you keep score with money.', 'pluglab' ),
					'text'      => 'Curabitur',
					'text2'     => 'Phasellus',
					'link'      => '#',
					'link2'     => '#',
					'newtab'    => true,
					'id'        => 'customizer_repeater_73y7op1z70b01',
				),
				array(
					'image_url' => PL_PLUGIN_URL . 'assets/images/corposet/slide3.jpg',
					'title'     => __( 'A collection of textile samples', 'pluglab' ),
					'subtitle'  => __( 'He lay on his armour-like back, and if he lifted his head a little he could see his brown belly.', 'pluglab' ),
					'text'      => 'Curabitur',
					'text2'     => 'Phasellus',
					'link'      => '#',
					'link2'     => '#',
					'newtab'    => true,
					'id'        => 'customizer_repeater_73y7op1z75b01',
				),
			)
		);
	}
}

if ( ! function_exists( 'service_default_json' ) ) {

	function service_default_json() {
		return json_encode(
			array(
				array(
					'image_url'  => PL_PLUGIN_URL . 'assets/images/corposet/img.jpg',
					'title'      => 'Business Consulting',
					'subtitle'   => 'Aenean ut turpis blandit eros convallis congue sit amet a libero. Mauris sed tempor felis. Nunc nisi massa, imperdiet ac metus quis, pharetra pulvinar sapien.',
					'text'       => 'Read More',
					'link'       => '#',
					'choice'     => 'customizer_repeater_icon',
					'icon_value' => 'fas fa-snowflake',
					'newtab'     => true,
					'id'         => 'customizer_repeater_97y7op8f70b00',
				),
				array(
					'image_url'  => PL_PLUGIN_URL . 'assets/images/corposet/img02.jpg',
					'title'      => 'Human Resource',
					'subtitle'   => 'Aenean ut turpis blandit eros convallis congue sit amet a libero. Mauris sed tempor felis. Nunc nisi massa, imperdiet ac metus quis, pharetra pulvinar sapien.',
					'text'       => 'Read More',
					'link'       => '#',
					'choice'     => 'customizer_repeater_icon',
					'icon_value' => 'fas fa-american-sign-language-interpreting',
					'newtab'     => true,
					'id'         => 'customizer_repeater_99y7op8f70b00',
				),
				array(
					'image_url'  => PL_PLUGIN_URL . 'assets/images/corposet/img03.jpg',
					'title'      => 'Market Reserch',
					'subtitle'   => 'Aenean ut turpis blandit eros convallis congue sit amet a libero. Mauris sed tempor felis. Nunc nisi massa, imperdiet ac metus quis, pharetra pulvinar sapien.',
					'text'       => 'Read More',
					'link'       => '#',
					'choice'     => 'customizer_repeater_icon',
					'icon_value' => 'fas fa-piggy-bank',
					'newtab'     => true,
					'id'         => 'customizer_repeater_90y7op8f70b00',
				),
			)
		);
	}
}

if ( ! function_exists( 'testimonial_default_json' ) ) {

	function testimonial_default_json() {
		return json_encode(
			array(
				array(
					'subtitle'  => __( 'As a app web crawler expert, I help organizations adjust to the expanding significace of internet promoting. placeholder text for use in your graphic, print.', 'pluglab' ),
					'text'      => 'Curabitur',
					'text2'     => 'Curabitur',
					'link'      => '#',
					'link2'     => '#',
					'choice'    => 'customizer_repeater_icon',
					'image_url' => PL_PLUGIN_URL . 'assets/images/circle.png',
					'id'        => 'customizer_repeater_90y7op8f70b40',
				),
				array(
					'subtitle'  => __( 'As a app web crawler expert, I help organizations adjust to the expanding significace of internet promoting. placeholder text for use in your graphic, print.', 'pluglab' ),
					'text'      => 'Curabitur',
					'text2'     => 'Curabitur',
					'link'      => '#',
					'choice'    => 'customizer_repeater_icon',
					'image_url' => PL_PLUGIN_URL . 'assets/images/circle1.png',
					'id'        => 'customizer_repeater_91y7op8f70b00',
				),
			)
		);
	}
}

if ( ! function_exists( 'pluglab_get_social_icon_default' ) ) {

	function pluglab_get_social_icon_default() {
		return apply_filters(
			'bizstrait_get_social_icon_default',
			json_encode(
				array(
					array(
						'icon_value' => esc_html__( 'fa-facebook', 'bizstrait' ),
						'link'       => esc_html__( '#', 'bizstrait' ),
						'id'         => 'customizer_repeater_header_social_001',
					),
					array(
						'icon_value' => esc_html__( 'fa-twitter', 'bizstrait' ),
						'link'       => esc_html__( '#', 'bizstrait' ),
						'id'         => 'customizer_repeater_header_social_003',
					),
					array(
						'icon_value' => esc_html__( 'fa-youtube', 'bizstrait' ),
						'link'       => esc_html__( '#', 'bizstrait' ),
						'id'         => 'customizer_repeater_header_social_005',
					),
					array(
						'icon_value' => esc_html__( 'fa-instagram', 'bizstrait' ),
						'link'       => esc_html__( '#', 'bizstrait' ),
						'id'         => 'customizer_repeater_header_social_005',
					),
				)
			)
		);
	}
}


if ( ! function_exists( 'pluglab_contact_info_default' ) ) {

	function pluglab_contact_info_default() {
		return apply_filters(
			'pluglab_contact_info_default',
			json_encode(
				array(
					array(
						'icon_value' => esc_html__( 'fas fa-phone-alt', 'bizstrait' ),
						'title'      => esc_html__( 'Contact Us', 'bizstrait' ),
						'text'   => __( '134-566-7680', 'bizstrait' ),
						'text2'   => __( '134-566-7680, USA 00202', 'bizstrait' ),
						'id'         => 'customizer_repeater_header_social_011',
					),
					array(
						'icon_value' => esc_html__( 'fa fa-envelope', 'bizstrait' ),
						'title'      => esc_html__( 'Email Us', 'bizstrait' ),
						'text'   => __( 'info@yoursite.com', 'bizstrait' ),
						'text2'   => __( 'info@yoursite.com', 'bizstrait' ),
						'id'         => 'customizer_repeater_header_social_0233',
					),
					array(
						'icon_value' => esc_html__( 'fa fa-globe', 'bizstrait' ),
						'title'      => esc_html__( 'Office Address', 'bizstrait' ),
						'text'   => __( 'New York Brooklyn Bridge South St', 'bizstrait' ),
						'text2'   => '',
						'id'         => 'customizer_repeater_header_social_0344',
					),
				)
			)
		);
	}
}

/**
 * @todo Remove it
 */
if ( ! function_exists( 'pluglab_get_social_icon_default1' ) ) {

	function pluglab_get_social_icon_default1() {
		return apply_filters(
			'pluglab_get_social_icon_default',
			json_encode(
				array(
					array(
						'icon_value' => esc_html__( 'fa-facebook', 'pluglab' ),
						'link'       => esc_html__( '#', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_001',
					),
					array(
						'icon_value' => esc_html__( 'fa-twitter', 'pluglab' ),
						'link'       => esc_html__( '#', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_003',
					),
					array(
						'icon_value' => esc_html__( 'fa-linkedin', 'pluglab' ),
						'link'       => esc_html__( '#', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_004',
					),
					array(
						'icon_value' => esc_html__( 'fa-instagram', 'pluglab' ),
						'link'       => esc_html__( '#', 'pluglab' ),
						'id'         => 'customizer_repeater_header_social_005',
					),
				)
			)
		);
	}
}

if ( ! function_exists( 'bizstrait_about_section_brief_default_content' ) ) {

	function bizstrait_about_section_brief_default_content() {
		return __(
			'<p>Distinctively exploit optimal alignments for intuitive. Quickly coordinate business applications through revolutionary catalysts for chang the Seamlessly optimal testing procedures</p>
              <p>Distinctively exploit optimal alignments for intuitive. Quickly coordinate business applications through revolutionary catalysts for chang.</p>
			  <blockquote>
			  <p>We work all the time with our customers and together we are able to create
				beautifull and amazing things that surely brings positive results and complete
				satisfaction.</p>
			</blockquote> 
			  <p> whereas processes. Synerg stically evolve 2.0 technologies rather than just in web & apps development optimal alignments for intuitive
              </p>',
			'bizstrait'
		);
	}
}

function plugLab_customizer_repeater_defaut_json( $wp_customize ) {

	$slider_default_data = $wp_customize->get_setting( 'slider_repeater' );
	if ( ! empty( $slider_default_data ) ) {
		$slider_default_data->default = slider_default_json();
	}

	$service_default_data = $wp_customize->get_setting( 'service_repeater' );
	if ( ! empty( $service_default_data ) ) {
		$service_default_data->default = service_default_json();
	}

	$testimonial_default_data = $wp_customize->get_setting( 'testimonial_repeater' );
	if ( ! empty( $testimonial_default_data ) ) {
		$testimonial_default_data->default = testimonial_default_json();
	}
}

add_action( 'customize_register', 'plugLab_customizer_repeater_defaut_json', 12 );

function plugLab_activeCallback( $wp_customize ) {

	// Slider
	function plugLab_slider_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'slider_display' )->value() ) {
			return true;
		}
		return false;
	}

	// callout
	function plugLab_callout_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'callout_display' )->value() ) {
			return true;
		}
		return false;
	}

	// portfolio
	function plugLab_portfolio_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'portfolio_display' )->value() ) {
			return true;
		}
		return false;
	}

	// service
	function plugLab_service_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'service_display' )->value() ) {
			return true;
		}
		return false;
	}

	// cta
	function plugLab_cta_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'cta_display' )->value() ) {
			return true;
		}
		return false;
	}

	// testimonial
	function plugLab_testimonial_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'testimonial_display' )->value() ) {
			return true;
		}
		return false;
	}

	// blog
	function plugLab_blog_fnback( $control ) {
		if ( (bool) $control->manager->get_setting( 'blog_display' )->value() ) {
			return true;
		}
		return false;
	}

}

add_action( 'customize_register', 'plugLab_activeCallback' );


if ( ! function_exists( 'bizstrait_customizer_editor' ) ) {

	/**
	 * Display editor for page editor control.
	 */
	function bizstrait_customizer_editor() {
		?>
		<div id="wp-editor-widget-container" style="display: none;">
			<a class="close" href="javascript:WPEditorWidget.hideEditor();"><span class="icon"></span></a>
			<div class="editor">
				<?php
				$settings = array(
					'textarea_rows' => 55,
					'editor_height' => 260,
				);
				wp_editor( '', 'wpeditorwidget', $settings );
				?>
				<p><a href="javascript:WPEditorWidget.updateWidgetAndCloseEditor(true);" class="button button-primary"><?php _e( 'Save and close', 'bizstrait' ); ?></a></p>
			</div>
		</div>
		<?php
	}

	add_action( 'customize_controls_print_footer_scripts', 'bizstrait_customizer_editor', 1 );
}
