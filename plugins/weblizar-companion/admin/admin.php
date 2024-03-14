<?php
defined('ABSPATH') or die();
require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/helpers/wl-companion-helper.php');
require('wl-wc-menu.php');

$theme_name = wl_companion_helper::wl_get_theme_name();

if ($theme_name == 'Nineteen') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/nineteen-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/portfolio-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/team-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/client-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/footer-section.php');
	//require_once( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/header-footer-scripts.php' );

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('nineteen_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_nineteen_general_customizer'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_nineteen_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_nineteen_service_customizer'));

	/* Portfolio Customizer Settings */
	add_action('customize_register', array('wl_portfolio_customizer', 'wl_nineteen_portfolio_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_nineteen_blog_customizer'));

	/* Team Customizer Settings */
	add_action('customize_register', array('wl_team_customizer', 'wl_nineteen_team_customizer'));

	/* Client Customizer Settings */
	add_action('customize_register', array('wl_client_customizer', 'wl_nineteen_client_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_nineteen_footer_customizer'));

	/* Header Footer Scripts */
	//add_action( 'customize_register', array( 'wl_header_footer_scripts_customizer', 'wl_hfs_customizer' ) );

} elseif ($theme_name == 'ProBizz') {
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/nineteen-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/contact-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/details-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/team-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/client-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/footer-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('nineteen_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_nineteen_general_customizer'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_nineteen_slider_customizer'));

	/* Contact Customizer Settings */
	add_action('customize_register', array('wl_contact_customizer', 'wl_nineteen_contact_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_nineteen_service_customizer'));

	/* Details Customizer Settings */
	add_action('customize_register', array('wl_details_customizer', 'wl_nineteen_details_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_nineteen_blog_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_nineteen_footer_customizer'));
} elseif ($theme_name == 'GrowBizz') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/nineteen-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/contact-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/team-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/client-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/portfolio-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/nineteen/features/about-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('nineteen_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_nineteen_general_customizer'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_nineteen_slider_customizer'));

	/* Contact Customizer Settings */
	add_action('customize_register', array('wl_contact_customizer', 'wl_nineteen_contact_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_nineteen_service_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_nineteen_blog_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_nineteen_footer_customizer'));

	/* Portfolio Customizer Settings */
	add_action('customize_register', array('wl_portfolio_customizer', 'wl_nineteen_portfolio_customizer'));

	/* about section Customizer settings */
	add_action('customize_register', array('wl_about_customizer', 'wl_nineteen_about_customizer'));
} elseif ($theme_name == 'Travelogged') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/travelogged-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/features/team-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/features/destination-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/travelogged/features/subscribe-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('travelogged_Customizer_scripts', 'wl_customizer_enqueue'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_travelogged_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_travelogged_service_customizer'));

	/* Team Customizer Settings */
	add_action('customize_register', array('wl_team_customizer_new', 'wl_travelogged_team_customizer_new'));

	/* Destination Customizer Settings */
	add_action('customize_register', array('wl_destination_customizer', 'wl_travelogged_destination_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_travelogged_blog_customizer'));

	/* Subscribe Customizer Settings */
	add_action('customize_register', array('wl_subscribe_customizer', 'wl_travelogged_subscribe_customizer'));
} elseif ($theme_name == 'Bitstream') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/bitstream-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/features/top-header-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/features/portfolio-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/bitstream/features/footer-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('bitstream_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_bitstream_general_customizer'));

	/* Top Header Customizer Settings */
	add_action('customize_register', array('wl_topheader_customizer', 'wl_bitstream_topheader_customizer'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_bitstream_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_bitstream_service_customizer'));

	/* Portfolio Customizer Settings */
	add_action('customize_register', array('wl_portfolio_customizer', 'wl_bitstream_portfolio_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_bitstream_blog_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_bitstream_footer_customizer'));
} elseif ($theme_name == 'Enigma' || $theme_name->template == 'enigma') {

	wl_companion_helper::wl_add_import_menu();

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/enigma-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/testimonial-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/portfolio-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/extra-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/social-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/footer-callout-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/layout-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma/features/typography-section.php');
    
	if( wp_get_theme() == 'Swiftly') :

		require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/swiftly/features/team-section.php');
		require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/swiftly/features/testimonial-section.php');

		/* Team Customizer Settings */
		add_action('customize_register', array('wl_team_customizer_new', 'wl_swiftly_team_customizer_new'));

		/* testimonial Customizer Settings */
		add_action('customize_register', array('swiftly_testimonial_customizer', 'wl_swiftly_testimonial_customizer'));

	endif;

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('enigma_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_enigma_general_customizer'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_enigma_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_enigma_service_customizer'));

	/* Portfolio Customizer Settings */
	add_action('customize_register', array('wl_portfolio_customizer', 'wl_enigma_portfolio_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_enigma_blog_customizer'));
	
	/* testimonail Customizer Settings */
	add_action('customize_register', array('wl_testimonial_customizer', 'wl_enigma_testimonial_customizer'));

	/* Extra Settings */
	add_action('customize_register', array('wl_extra_customizer', 'wl_enigma_extra_customizer'));

	/* Social Settings */
	add_action('customize_register', array('wl_social_customizer', 'wl_enigma_social_customizer'));

	/* Footer Callout Settings */
	add_action('customize_register', array('wl_footer_callout_customizer', 'wl_enigma_footer_callout_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_enigma_footer_customizer'));

	/* Layout Customizer Settings */
	add_action('customize_register', array('wl_layout_customizer', 'wl_enigma_layout_customizer'));
	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography_customizer', 'wl_enigma_typography_customizer'));

} elseif ($theme_name == 'Enigma Parallax' || $theme_name->template == 'enigma-parallax') {

	wl_companion_helper::wl_add_import_menu();

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/enigma-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/portfolio-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/blog-section.php');

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/social-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/footer-callout-section.php');

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/layout-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/team-section.php');

	/* Add Fonts Customizer Features */
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/font-section.php');
 
	/* Fonts Customizer Settings */
	add_action('customize_register', array('wl_Fonts_customizer', 'wl_enigma_parallax_Fonts_customizer'));

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('enigma_parallax_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_enigma_parallax_general_customizer'));
	/* Slider require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/enigma-parallax/features/team-section.php'); Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_enigma_parallax_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_enigma_parallax_service_customizer'));

	/* Portfolio Customizer Settings */
	add_action('customize_register', array('wl_portfolio_customizer', 'wl_enigma_parallax_portfolio_customizer'));
	/* Team Customizer Settings */
	add_action('customize_register', array('wl_team_customizer', 'wl_enigma_team_customizer'));
	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_enigma_parallax_blog_customizer'));
	/* Social Settings */
	add_action('customize_register', array('wl_social_customizer', 'wl_enigma_parallax_social_customizer'));

	/* Footer Callout Settings */
	add_action('customize_register', array('wl_footer_callout_customizer', 'wl_enigma_parallax_footer_callout_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_enigma_parallax_footer_customizer'));
	/* Layout Customizer Settings */
	add_action('customize_register', array('wl_layout_customizer', 'wl_enigma_parallax_layout_customizer'));
} elseif ($theme_name == 'Weblizar') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/weblizar-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/features/social-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/weblizar/features/footer-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('weblizar_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_wl_general_customizer'));
	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_wl_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_wl_service_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_wl_blog_customizer'));

	/* Social Settings */
	add_action('customize_register', array('wl_social_customizer', 'wl_wl_social_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_wl_footer_customizer'));
} elseif ($theme_name == 'Guardian' || $theme_name->template == 'guardian') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/guardian-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/extra-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/social-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/layout-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/about-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/team-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/footer-callout-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/guardian/features/typography-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('guardian_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_guardian_general_customizer'));
	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_guardian_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_guardian_service_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_guardian_blog_customizer'));
	/* Extra Settings */
	add_action('customize_register', array('wl_extra_customizer', 'wl_guardian_extra_customizer'));

	/* Social Settings */
	add_action('customize_register', array('wl_social_customizer', 'wl_guardian_social_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_guardian_footer_customizer'));
	/* Layout Customizer Settings */
	add_action('customize_register', array('wl_layout_customizer', 'wl_guardian_layout_customizer'));
	
	/* About Customizer Settings */
	add_action('customize_register', array('wl_about_customizer', 'wl_guardian_about_customizer'));
	
	/* Team Customizer Settings */
	add_action('customize_register', array('wl_team_customizer', 'wl_guardian_team_customizer'));
	
	/* Team Customizer Settings */
	add_action('customize_register', array('wl_footer_callout_customizer', 'wl_guardian_footer_callout_customizer'));
	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography2_customizer', 'wl_guardian_typography2_customizer'));
	
} elseif ($theme_name == 'Creative' || $theme_name->template == 'creative') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/creative-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/portfolio-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/social-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/footer-callout-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/creative/features/layout-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('creative_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_creative_general_customizer'));
	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_creative_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_creative_service_customizer'));

	/* Portfolio Customizer Settings */
	add_action('customize_register', array('wl_portfolio_customizer', 'wl_creative_portfolio_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_creative_blog_customizer'));

	/* Social Settings */
	add_action('customize_register', array('wl_social_customizer', 'wl_creative_social_customizer'));

	/* Footer Callout Settings */
	add_action('customize_register', array('wl_footer_callout_customizer', 'wl_creative_footer_callout_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_creative_footer_customizer'));
	/* Layout Customizer Settings */
	add_action('customize_register', array('wl_layout_customizer', 'wl_creative_layout_customizer'));
} elseif ($theme_name == 'Explora') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/explora-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/features/portfolio-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/features/social-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/explora/features/layout-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('explora_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_explora_general_customizer'));
	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_explora_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_explora_service_customizer'));

	/* Portfolio Customizer Settings */
	add_action('customize_register', array('wl_portfolio_customizer', 'wl_explora_portfolio_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_explora_blog_customizer'));

	/* Social Settings */
	add_action('customize_register', array('wl_social_customizer', 'wl_explora_social_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_explora_footer_customizer'));
	/* Layout Customizer Settings */
	add_action('customize_register', array('wl_layout_customizer', 'wl_explora_layout_customizer'));
} elseif ($theme_name == 'scoreline') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/scoreline-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/extra-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/social-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/footer-callout-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/scoreline/features/layout-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('scoreline_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_scoreline_general_customizer'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_scoreline_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_scoreline_service_customizer'));

	/* Extra Settings */
	add_action('customize_register', array('wl_extra_customizer', 'wl_scoreline_extra_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_scoreline_blog_customizer'));

	/* Social Settings */
	add_action('customize_register', array('wl_social_customizer', 'wl_scoreline_social_customizer'));

	/* Footer Callout Settings */
	add_action('customize_register', array('wl_footer_callout_customizer', 'wl_scoreline_footer_callout_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_scoreline_footer_customizer'));

	/* Layout Customizer Settings */
	add_action('customize_register', array('wl_layout_customizer', 'wl_scoreline_layout_customizer'));
} elseif ($theme_name == 'Green-Lantern') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/green-lantern-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/features/social-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/green-lantern/features/layout-section.php');

	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('green_lantern_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_green_lantern_general_customizer'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_green_lantern_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_green_lantern_service_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_green_lantern_blog_customizer'));

	/* Social Settings */
	add_action('customize_register', array('wl_social_customizer', 'wl_green_lantern_social_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_green_lantern_footer_customizer'));

	/* Layout Customizer Settings */
	add_action('customize_register', array('wl_layout_customizer', 'wl_green_lantern_layout_customizer'));
} elseif ($theme_name == 'Digicrew' || $theme_name->template == 'digicrew') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/digicrew-customizer-scripts.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/general-settings.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/slider-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/service-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/blog-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/footer-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/layout-section.php');
	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/typography-section.php');

	if ($theme_name == 'Digitrails') {
		require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/color-section.php');
		require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/team-section.php');
		require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/digicrew/features/font-section.php');

		/* Font Customizer Settings */
		add_action('customize_register', array('wl_fontfamily_customizer', 'wl_digicrew_fontfamily_customizer'));

		/* Team Customizer Settings */
		add_action('customize_register', array('wl_team_customizer_new', 'wl_digicrew_team_customizer_new'));

		/* Color Customizer Settings */
		add_action('customize_register', array('wl_color_customizer', 'wl_digitrails_color_customizer'));
	}
	/* Customizer scripts */
	add_action('customize_controls_enqueue_scripts', array('digicrew_Customizer_scripts', 'wl_customizer_enqueue'));

	/* General Customizer Settings */
	add_action('customize_register', array('wl_general_customizer', 'wl_digicrew_general_customizer'));

	/* Slider Customizer Settings */
	add_action('customize_register', array('wl_slider_customizer', 'wl_digicrew_slider_customizer'));

	/* Service Customizer Settings */
	add_action('customize_register', array('wl_service_customizer', 'wl_digicrew_service_customizer'));

	/* Blog Customizer Settings */
	add_action('customize_register', array('wl_blog_customizer', 'wl_digicrew_blog_customizer'));

	/* Footer Customizer Settings */
	add_action('customize_register', array('wl_footer_customizer', 'wl_digicrew_footer_customizer'));

	/* Layout Customizer Settings */
	add_action('customize_register', array('wl_layout_customizer', 'wl_digicrew_layout_customizer'));
	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography3_customizer', 'wl_digicrew_typography3_customizer'));
	
}

elseif ($theme_name == 'fabstar' || $theme_name->template == 'fabstar') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/fabstar/features/typography-section.php');	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography5_customizer', 'wl_fabstar_typography5_customizer'));
}

elseif ($theme_name == 'is-medify' || $theme_name->template == 'is-medify') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/is-medify/features/typography-section.php');	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography7_customizer', 'wl_is_medify_typography7_customizer'));
}
elseif ($theme_name == 'tripify' || $theme_name->template == 'tripify') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/tripify/features/typography-section.php');	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography8_customizer', 'wl_tripify_typography8_customizer'));
}

elseif ($theme_name == 'ismoderna' || $theme_name->template == 'ismoderna') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/ismoderna/features/typography-section.php');	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography9_customizer', 'wl_ismoderna_typography7_customizer'));
}

elseif ($theme_name == 'wheelify' || $theme_name->template == 'wheelify') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/wheelify/features/typography-section.php');	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography4_customizer', 'wl_wheelify_typography4_customizer'));
}
elseif ($theme_name == 'markito' || $theme_name->template == 'markito') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/markito/features/typography-section.php');	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typographys_customizer', 'wl_markito_typographys_customizer'));
}
elseif ($theme_name == 'mediapulse' || $theme_name->template == 'mediapulse') {

	require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/controllers/mediapulse/features/typography-section.php');	
	/* Typography Customizer Settings */
	add_action('customize_register', array('wl_typography10_customizer', 'wl_mediapulse_typography10_customizer'));
}
elseif ($theme_name == 'enigma-parallax' || $theme_name == 'Enigma' || $theme_name == 'Weblizar' || $theme_name == 'Creative' || $theme_name == 'Explora' || $theme_name == 'Guardian' || $theme_name == 'HealthCare') {
	wl_companion_helper::wl_add_import_menu();
} elseif ($theme_name == 'Enigma Premium') {
	add_action('admin_menu', array('WL_WC_ImportExportMenu', 'pro_theme_menu'));
} elseif ($theme_name == 'Enigma-Premium-Advance') {
	add_action('admin_menu', array('WL_WC_ImportExportMenu', 'pro_theme_menu'));
} elseif ($theme_name == 'Enigma-Pro-Parallax') {
	add_action('admin_menu', array('WL_WC_ImportExportMenu', 'pro_theme_menu'));
} else {
	wl_companion_helper::wl_add_import_menu_child();
}
