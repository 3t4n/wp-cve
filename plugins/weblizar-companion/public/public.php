<?php
/**
 * Themes Files Included
 * 
 * @package Weblizarcompanion
 * 
 * @category ${1:description}
 * 
 * 
*/

defined('ABSPATH') or die();
require_once WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/helpers/wl-companion-helper.php';

$theme_name = wl_companion_helper::wl_get_theme_name();

if ($theme_name == 'Nineteen') {

    /* Custom scripts*/
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/nineteen/wl-scripts.php';
    add_action('wp_footer', ['wl_companion_scripts', 'wl_companion_scripts_frontend']);

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/nineteen/slider-section.php';
    add_action('wl_companion_slider', ['wl_companion_sliders', 'wl_companion_sliders_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/nineteen/service-section.php';
    add_action('wl_companion_service', ['wl_companion_services', 'wl_companion_services_html']);

    /* Portfolio Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/nineteen/portfolio-section.php';
    add_action('wl_companion_portfolio', ['wl_companion_portfolios', 'wl_companion_portfolios_html']);

    /* Client Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/nineteen/client-section.php';
    add_action('wl_companion_client', ['wl_companion_clients', 'wl_companion_clients_html']);

    /* Team Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/nineteen/team-section.php';
    add_action('wl_companion_team', ['wl_companion_teams', 'wl_companion_teams_html']);

} elseif ($theme_name == 'Travelogged') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/travelogged/slider-section.php';
    add_action('wl_companion_slider_travel', ['wl_companion_slider_travel', 'wl_companion_slider_travel_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/travelogged/service-section.php';
    add_action('wl_companion_services_travel', ['wl_companion_services_travel', 'wl_companion_services_travel_html']);

    /* Destination Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/travelogged/destination-section.php';
    add_action('wl_companion_destination_travel', ['wl_companion_destination_travel', 'wl_companion_destination_travel_html']);

    /* Team Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/travelogged/team-section.php';
    add_action('wl_companion_team_travel', ['wl_companion_team_travel', 'wl_companion_team_travel_html']);

    /* Subscribe Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/travelogged/subscribe-section.php';
    add_action('wl_companion_subscribe_travel', ['wl_companion_subscribe_travel', 'wl_companion_subscribe_travel_html']);

    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/travelogged/front-end-scripts.php';
    add_action('wp_enqueue_scripts', ['wlcm_frontend_scripts', 'frontend_enqueue_assets']);

    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/travelogged/subscribe-ajax-action.php';
    add_action('wp_ajax_nopriv_wlc_subscribe_form', ['SubscribeFormAjax', 'subscribe_form_action']);
    add_action('wp_ajax_wlc_subscribe_form', ['SubscribeFormAjax', 'subscribe_form_action']);

} elseif ($theme_name == 'Bitstream') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/bitstream/slider-section.php';
    add_action('wl_companion_slider_bitstream', ['wl_companion_sliders_bitstream', 'wl_companion_sliders_bitstream_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/bitstream/service-section.php';
    add_action('wl_companion_service_bitstream', ['wl_companion_services_bitstream', 'wl_companion_services_bitstream_html']);

    /* Portfolio Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/bitstream/portfolio-section.php';
    add_action('wl_companion_portfolio_bitstream', ['wl_companion_portfolios_bitstream', 'wl_companion_portfolios_bitstream_html']);

} elseif ($theme_name == 'Enigma' || $theme_name->template == 'enigma') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/enigma/slider-section.php';
    add_action('wl_companion_slider_enigma', ['wl_companion_sliders_enigma', 'wl_companion_sliders_enigma_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/enigma/service-section.php';
    add_action('wl_companion_service_enigma', ['wl_companion_services_enigma', 'wl_companion_services_enigma_html']);

    /* Portfolio Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/enigma/portfolio-section.php';
    add_action('wl_companion_portfolio_enigma', ['wl_companion_portfolios_enigma', 'wl_companion_portfolios_enigma_html']);
	
	/* Testimonial Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/enigma/testimonial-section.php';
    add_action('wl_companion_testimonial_enigma', ['wl_companion_testimonial_enigma', 'wl_companion_testimonial_enigma_html']);

} elseif ($theme_name == 'Enigma Parallax' || $theme_name->template == 'enigma-parallax') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/enigma-parallax/slider-section.php';
    add_action('wl_companion_slider_enigma_parallax', ['wl_companion_sliders_enigma_parallax', 'wl_companion_sliders_enigma_parallax_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/enigma-parallax/service-section.php';
    add_action('wl_companion_service_enigma_parallax', ['wl_companion_services_enigma_parallax', 'wl_companion_services_enigma_parallax_html']);

    /* Portfolio Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/enigma-parallax/portfolio-section.php';
    add_action('wl_companion_portfolio_enigma_parallax', ['wl_companion_portfolios_enigma_parallax', 'wl_companion_portfolios_enigma_parallax_html']);

    /* Team Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/enigma-parallax/team-section.php';
    add_action('wl_companion_team', ['wl_companion_teams', 'wl_companion_teams_html']);

} elseif ($theme_name == 'Weblizar') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/weblizar/slider-section.php';
    add_action('wl_companion_slider_wl', ['wl_companion_sliders_wl', 'wl_companion_sliders_wl_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/weblizar/service-section.php';
    add_action('wl_companion_service_wl', ['wl_companion_services_wl', 'wl_companion_services_wl_html']);

} elseif ($theme_name == 'Guardian' || $theme_name->template == 'guardian') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/guardian/slider-section.php';
    add_action('wl_companion_slider_guardian', ['wl_companion_sliders_guardian', 'wl_companion_sliders_guardian_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/guardian/service-section.php';
    add_action('wl_companion_service_guardian', ['wl_companion_services_guardian', 'wl_companion_services_guardian_html']);
	
	/* about Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/guardian/about-section.php';
    add_action('wl_companion_aboutsection_guardian', ['wl_companion_about_guardian', 'wl_companion_about_guardian_html']);
	
	/* team HTML */
	include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/guardian/team-section.php';
    add_action('wl_companion_teamsection_guardian', ['wl_companion_team_guardian', 'wl_companion_team_guardian_html']);

} elseif ($theme_name == 'Creative' || $theme_name->template == 'creative') {
    /* Slider Html */

    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/creative/slider-section.php';
    add_action('wl_companion_slider_creative', ['wl_companion_sliders_creative', 'wl_companion_sliders_creative_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/creative/service-section.php';
    add_action('wl_companion_service_creative', ['wl_companion_services_creative', 'wl_companion_services_creative_html']);

    /* Portfolio Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/creative/portfolio-section.php';
    add_action('wl_companion_portfolio_creative', ['wl_companion_portfolios_creative', 'wl_companion_portfolios_creative_html']);

} elseif ($theme_name == 'Explora') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/explora/slider-section.php';
    add_action('wl_companion_slider_explora', ['wl_companion_sliders_explora', 'wl_companion_sliders_explora_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/explora/service-section.php';
    add_action('wl_companion_service_explora', ['wl_companion_services_explora', 'wl_companion_services_explora_html']);

    /* Portfolio Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/explora/portfolio-section.php';
    add_action('wl_companion_portfolio_explora', ['wl_companion_portfolios_explora', 'wl_companion_portfolios_explora_html']);
    
} elseif ($theme_name == 'scoreline') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/scoreline/slider-section.php';
    add_action('wl_companion_slider_scoreline', ['wl_companion_sliders_scoreline', 'wl_companion_sliders_scoreline_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/scoreline/service-section.php';
    add_action('wl_companion_service_scoreline', ['wl_companion_services_scoreline', 'wl_companion_services_scoreline_html']);

} elseif ($theme_name == 'Green-Lantern') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/green-lantern/slider-section.php';
    add_action('wl_companion_slider_green_lantern', ['wl_companion_sliders_green_lantern', 'wl_companion_sliders_green_lantern_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/green-lantern/service-section.php';
    add_action(
        'wl_companion_service_green_lantern',
        [
            'wl_companion_services_green_lantern',
            'wl_companion_services_green_lantern_html',
        ]
    );

} elseif ($theme_name == 'Digicrew' || $theme_name->template == 'digicrew') {

    /* Slider Html */
    include_once(WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/digicrew/slider-section.php');
    add_action('wl_companion_slider_digicrew', ['wl_companion_sliders_digicrew', 'wl_companion_sliders_digicrew_html']);

    /* Service Html */
    include_once(WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/digicrew/service-section.php');
    add_action('wl_companion_service_digicrew', ['wl_companion_services_digicrew', 'wl_companion_services_digicrew_html']);

    if ($theme_name == 'Digitrails') {
        /* Team Html */
        include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/digicrew/team-section.php';
        add_action('wl_companion_team', ['wl_companion_teams', 'wl_companion_teams_html']);
    }

}


if ($theme_name == 'Enigma' || $theme_name->template == 'enigma') {

    /* Slider Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/swiftly/slider-section.php';
    add_action('wl_companion_slider_swiftly', ['wl_companion_sliders_swiftly', 'wl_companion_sliders_swiftly_html']);

    /* Service Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/swiftly/service-section.php';
    add_action('wl_companion_service_swiftly', ['wl_companion_services_swiftly', 'wl_companion_services_swiftly_html']);

    /* Portfolio Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/swiftly/portfolio-section.php';
    add_action('wl_companion_portfolio_swiftly', ['wl_companion_portfolios_swiftly', 'wl_companion_portfolios_swiftly_html']);

    /* Team Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/swiftly/team-section.php';
    add_action('wl_companion_team_swiftly', ['wl_companion_teams_swiftly', 'wl_companion_team_swiftly_html']);

    /* Testimonial Html */
    include_once WL_COMPANION_PLUGIN_DIR_PATH . 'public/inc/swiftly/testimonial-section.php';
    add_action('wl_companion_testimonial_swiftly', ['wl_companion_testimonials_swiftly', 'wl_companion_testimonial_swiftly_html']);

}