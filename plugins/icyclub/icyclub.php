<?php
/*
 * Plugin Name:       Icyclub
 * Plugin URI:        
 * Description:       Icyclub plugin is comptible for Themeansar theme.
 * Version:           2.1
 * Author:            themeicy
 * Author URI:        https://themeansar.com/
 * License:           GPL-2.0+
 * Tested up to: 	  6.3
 * Requires: 		  5.6 or higher
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       icyclub
 * Domain Path:       /languages
 */
 
define( 'ICYCP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ICYCP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function icycp_activate() {
	$theme = wp_get_theme();
	if ( ('Consultup' == $theme->name) || ('Busiup' == $theme->name) || ('Busiway' == $theme->name) || ( 'Listing' == $theme->name) ||
		('Consultup Child' == $theme->name) || ('Consultup Child theme of consultup' == $theme->name)){
		require_once('inc/consultup/features/customizer.php');
		require_once('inc/consultup/features/customizer-header.php');
		require_once('inc/consultup/sections/homepage.php');
		require_once('inc/consultup/sections/header.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
	}

	if ('Consultly' == $theme->name){
		require_once('inc/consultup/features/customizer.php');
		require_once('inc/consultup/features/customizer-header-consultly.php');
		require_once('inc/consultup/sections/homepage.php');
		require_once('inc/consultup/sections/header-consultly.php');
		require_once('inc/consultup/sections/header.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
	}

	
	if (( 'Shopbiz Lite' == $theme->name) || ('Spabeauty' == $theme->name)){
		require_once('inc/shopbiz/features/customizer.php');
		require_once('inc/shopbiz/sections/homepage.php');
	}

	if (( 'Businessup' == $theme->name) || ( 'Awesome Business' == $theme->name) || ( 'BusiMax' == $theme->name) || ( 'Bugency' == $theme->name)){
		require_once('inc/businessup/features/customizer.php');
		require_once('inc/businessup/sections/homepage.php');
	}

	if (( 'Yoga' == $theme->name)){
		require_once('inc/yoga/features/customizer.php');
		require_once('inc/yoga/sections/homepage.php');
	}

	if (('Transportex' == $theme->name ) || ('Cargoex' == $theme->name) || ('Deliverex' == $theme->name) || ('Supplier' == $theme->name) || ('Movershub' == $theme->name)){
		require_once('inc/transportex/features/customizer.php');
		require_once('inc/transportex/sections/homepage.php');
	}

	if ( 'Agencyup' == $theme->name){
		require_once('inc/agencyup/features/customizer.php');
		require_once('inc/agencyup/features/customizer-header.php');
		require_once('inc/agencyup/sections/homepage.php');
		require_once('inc/agencyup/sections/header.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
		require_once('inc/custom-radio-image-control/custom-radio-image-control.php');
	}

	if ( ('Busiage' == $theme->name) || ('Business Perk' == $theme->name)) {
		require_once('inc/agencyup/features/customizer.php');
		require_once('inc/agencyup/features/customizer-header.php');
		require_once('inc/agencyup/sections/homepage.php');
		require_once('inc/agencyup/sections/busiage-header.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
		require_once('inc/custom-radio-image-control/custom-radio-image-control.php');
	}

	if ( 'Financey' == $theme->name){
		require_once('inc/agencyup/features/customizer.php');
		require_once('inc/agencyup/features/customizer-header-financey.php');
		require_once('inc/agencyup/sections/homepage.php');
		require_once('inc/agencyup/sections/financey-header.php');
		require_once('inc/agencyup/features/customizer-fianancy-header-info.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
		require_once('inc/custom-radio-image-control/custom-radio-image-control.php');
	}

	if ( 'Reckon' == $theme->name){
		require_once('inc/agencyup/features/customizer.php');
		require_once('inc/agencyup/features/customizer-header-reckon.php');
		require_once('inc/agencyup/sections/homepage.php');
		require_once('inc/agencyup/sections/reckon-header.php');
		require_once('inc/agencyup/features/customizer-reckon-header-info.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
		require_once('inc/custom-radio-image-control/custom-radio-image-control.php');
	}
	
	if ( 'Agencyup Dark' == $theme->name){
		require_once('inc/agencyup/features/customizer.php');
		require_once('inc/agencyup/features/customizer-header.php');
		require_once('inc/agencyup/sections/homepage.php');
		require_once('inc/agencyup/sections/header.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
		require_once('inc/custom-radio-image-control/custom-radio-image-control.php');
	}

	if ( ('Consultco' == $theme->name) || ('Consultco Dark' == $theme->name)){
		
		require_once('inc/consultco/features/feature-header-section.php');
		require_once('inc/consultco/features/feature-slider-section.php');
		require_once('inc/consultco/features/feature-contact-section.php');
		require_once('inc/consultco/features/feature-service-section.php');
		require_once('inc/consultco/features/feature-features-section.php');
		require_once('inc/consultco/features/feature-cta-section.php');
		require_once('inc/consultco/features/feature-news-section.php');
		
		require_once('inc/consultco/sections/consultco-header-section.php');
		require_once('inc/consultco/sections/consultco-service-section.php');
		require_once('inc/consultco/sections/consultco-slider-section.php');
		require_once('inc/consultco/sections/consultco-contact-section.php');
		require_once('inc/consultco/sections/consultco-feature-section.php');
		require_once('inc/consultco/sections/consultco-news-section.php');
		require_once('inc/consultco/sections/consultco-callout-section.php');

		
		require_once('inc/consultco/customizer.php');
		require_once('inc/consultco/default-data.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');

		
	}

	if ( ('Industryup' == $theme->name) || ('Cargoup' == $theme->name) || ('Greenry' == $theme->name)){
		require_once('inc/industryup/features/indusup-header-section.php');
		require_once('inc/industryup/features/indusup-slider-section.php');
		require_once('inc/industryup/features/indusup-contact-section.php');
		require_once('inc/industryup/features/indusup-service-section.php');
		require_once('inc/industryup/features/indusup-portfolio-section.php');
		require_once('inc/industryup/features/indusup-cta-section.php');
		require_once('inc/industryup/features/indusup-news-section.php');
		require_once('inc/industryup/features/indusup-feature-section.php');
		require_once('inc/industryup/features/cargoup-header-section.php');

		require_once('inc/industryup/sections/indusup-header-section.php');
		require_once('inc/industryup/sections/indusup-service-section.php');
		require_once('inc/industryup/sections/indusup-slider-section.php');
		require_once('inc/industryup/sections/indusup-contact-section.php');
		require_once('inc/industryup/sections/indusup-portfolio-section.php');
		require_once('inc/industryup/sections/indusup-news-section.php');
		require_once('inc/industryup/sections/indusup-callout-section.php');
		require_once('inc/industryup/sections/indusup-feature-section.php');

		//Cargoup
		require_once('inc/industryup/sections/cargoup-header-section.php');
		
		require_once('inc/industryup/customizer.php');
		require_once('inc/industryup/default-data.php');
		require_once('inc/class-alpha-color-control/class-alpha-color-control.php');
	}

}
add_action( 'init', 'icycp_activate' );

require_once('inc/consultup/control/control.php');

function icycp_enqueue(){
	wp_enqueue_style('icycp-custom-controls-css', plugin_dir_url(__FILE__) . 'assets/css/customizer.css', false, '1.0.0');

}
add_action('admin_enqueue_scripts', 'icycp_enqueue');

function icycp_customizer_script() {

	 wp_enqueue_style( 'icycp-customize', plugin_dir_url(__FILE__) .'assets/js/customize.css', 'screen' );
	 wp_enqueue_script( 'icycp-customizer-script', plugin_dir_url(__FILE__) .'assets/js/customizer-section.js', array("jquery"),'', true  );	
}
add_action( 'customize_controls_enqueue_scripts', 'icycp_customizer_script' );


$theme = wp_get_theme();
if (( 'Consultup' == $theme->name) || ( 'Busiup' == $theme->name) || ( 'Busiway' == $theme->name) || ( 'Listing' == $theme->name) || ( 'consultly' == $theme->name) || ('Consultup Child' == $theme->name) || ('Consultup Child theme of consultup' == $theme->name)){
		
	
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/consultup/pages/home.php');
	require_once('inc/consultup/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}

if (( 'Shopbiz Lite' == $theme->name) || ( 'Spabeauty' == $theme->name)){
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/shopbiz/pages/home.php');
	require_once('inc/shopbiz/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}

if (( 'Businessup' == $theme->name) || ( 'BusiMax' == $theme->name) || ( 'Awesome Business' == $theme->name) || ( 'Bugency' == $theme->name)) {
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/businessup/pages/home.php');
	require_once('inc/businessup/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}

if (( 'Yoga' == $theme->name) || ( 'Spabeauty' == $theme->name)){
	register_activation_hook( __FILE__, 'icycp_page_installation_function');
	function icycp_page_installation_function()
	{	
	$item_details_page = get_option('item_details_page'); 
		if(!$item_details_page){
		require_once('inc/yoga/pages/home.php');
		require_once('inc/yoga/pages/blog.php');
		update_option( 'item_details_page', 'Done' );
	   }
	}
	}

if ('Transportex' == $theme->name){
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/transportex/pages/home.php');
	require_once('inc/transportex/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}


if (( 'Agencyup' == $theme->name) || ( 'Busiage' == $theme->name) || ( 'Financey' == $theme->name) || ( 'Agencyup Dark' == $theme->name)) {
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/agencyup/pages/home.php');
	require_once('inc/agencyup/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}



if (( 'Consultco' == $theme->name)) {
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/consultco/pages/home.php');
	require_once('inc/consultco/pages/blog.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}


if (( 'Industryup' == $theme->name)) {
register_activation_hook( __FILE__, 'icycp_page_installation_function');
function icycp_page_installation_function()
{	
$item_details_page = get_option('item_details_page'); 
    if(!$item_details_page){
	require_once('inc/industryup/pages/home.php');
	require_once('inc/industryup/pages/blog.php');
	require_once('inc/industryup/pages/about.php');
	require_once('inc/industryup/widgets/widget.php');
	update_option( 'item_details_page', 'Done' );
   }
}
}

// tn Limit Excerpt Length by number of Words
function excerpt( $limit ) {
$excerpt = explode(' ', get_the_excerpt(), $limit);
if (count($excerpt)>=$limit) {
array_pop($excerpt);
$excerpt = implode(" ",$excerpt).'';
} else {
$excerpt = implode(" ",$excerpt);
}
$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
return $excerpt;
}


function icyclub_news_excerpt() {
    global $post;
    $excerpt = get_the_content();
    $excerpt = strip_tags(preg_replace(" (\[.*?\])", '', $excerpt));
    $excerpt = strip_shortcodes($excerpt);
    $original_len = strlen($excerpt);
    $consultup_excerpt_length = get_theme_mod('consultup_excerpt_length',180);
    $excerpt = substr($excerpt, 0, $consultup_excerpt_length);
    $len = strlen($excerpt);
    if ($original_len > 275) {
        $excerpt = $excerpt;
        return $excerpt . '<div class="news-excerpt-btn"><a href="' . esc_url(get_permalink()) . '" class="more-link">' . esc_html__("Read More", "icyclub") . '</a></div>';
    } else {
        return $excerpt;
    }
}

if ( ! function_exists( 'icycp_switch_sanitization' ) ) {
		function icycp_switch_sanitization( $input ) {
			if ( true === $input ) {
				return 1;
			} else {
				return 0;
			}
		}
}

?>