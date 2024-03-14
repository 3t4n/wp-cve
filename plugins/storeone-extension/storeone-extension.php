<?php
/*
Plugin Name: StoreOne Extension
Description: Advance Extension For StoreOne Theme. enjoy full functionality of StoreOne theme by installing this plugin.
Author: ThemeFarmer
Author URI: https://www.themefarmer.com/
Domain Path: /lang/
Version: 2.1.1
Text Domain: storeone-extension

StoreOne Extension is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

StoreOne Extension is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with StoreOne Extension. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

define( 'STOREONE_EXTENSION_DIR', plugin_dir_path( __FILE__ ) );

$theme = wp_get_theme(); 
if ( 'StoreOne' === $theme->name || 'StoreOne' === $theme->parent_theme ) {

	add_action('customize_register', 'storeone_extension_customize_register');
	if(get_theme_mod( 'storeone_old_to_new_data_import', false ) === false){
		add_action('init', 'storeone_old_to_new_data_import');
	}
}

function storeone_old_to_new_data_import(){
		$sliders = array(
			'tf_slider' => 'themefarmer_home_slider',
			'tf_blog_slider' => 'themefarmer_blog_slider',
			'tf_shop_slider' => 'themefarmer_shop_slider',			
		);

		foreach ($sliders as $cpt_name => $mod_name) {
			$tf_slider_cpt = get_posts( array(
				'numberposts' => 99,
	  			'post_type'   => $cpt_name,
			    'orderby'    => 'menu_order',
			    // 'sort_order' => 'asc'
			));
			$new_slider_data = array();
			$slide_data_set = false;
			foreach ($tf_slider_cpt as $key => $a_slide) {
				// print_r($a_slide);
				$slider_data 	= get_post_meta($a_slide->ID, 'tf_slider_data', true);
				$slide_link     = isset($slider_data['button_one_link'])?$slider_data['button_one_link']:'';
				$new_slider_data[] = array(
					'heading'      => wp_kses_post( $a_slide->post_title ),
					'description'  => wp_kses_post( $a_slide->post_content ),
					'image'        => esc_url(wp_get_attachment_url( get_post_thumbnail_id( $a_slide->ID ))),
					'button1_text' => esc_attr__('Read More', 'storeone'),
					'button1_url'  => $slide_link,
				);
				$slide_data_set = true;
			}

			$mod_slider_data = get_theme_mod( $mod_name, false );
			
			if($mod_slider_data === false && !empty($new_slider_data) && is_array($new_slider_data) && $slide_data_set === true){
				set_theme_mod($mod_name, $new_slider_data);
			}
			
		}

		$tf_testi_cpt = get_posts( array(
			'numberposts' => 99,
  			'post_type'   => 'tf_testimonials',
		    'orderby'    => 'menu_order',
		));
		$new_testi_data = array();
		$testi_data_set = false;
		foreach ($tf_testi_cpt as $key => $a_testi) {
			$new_testi_data[] = array(
				'title'      => wp_kses_post( $a_testi->post_title ),
				'description'  => wp_kses_post( $a_testi->post_content ),
				'image'        => esc_url(wp_get_attachment_url( get_post_thumbnail_id( $a_testi->ID ))),
			);
			$testi_data_set = true;
		}

		$mod_testi_data = get_theme_mod( 'themefarmer_home_testimonials', false );
		
		if($mod_testi_data === false && !empty($new_testi_data) && is_array($new_testi_data) && $testi_data_set === true){
			set_theme_mod('themefarmer_home_testimonials', $new_testi_data);
		}

		$storeone_socials = array('facebook' => 'fa-facebook', 'google' => 'fa-google-plus', 'youtube' => 'fa-youtube', 'twitter' => 'fa-twitter', 'instagram' => 'fa-instagram', 'linkedin' => 'fa-linkedin');
		$storeone_socials_new = array();
		foreach($storeone_socials as $social => $icon) {
			if(!empty(get_theme_mod( 'storeone_social_link_'.$social))){
				$storeone_socials_new[] = array(
					'icon' => $icon,
					'link' => $social,
				);
			}
		}
		
		if(!empty($storeone_socials_new)){
			set_theme_mod( 'themefarmer_socials',  $storeone_socials_new);
		}

		set_theme_mod( 'storeone_old_to_new_data_import', true );
}

function storeone_extension_customize_register($wp_customize){
	if(class_exists('ThemeFarmer_Field_Repeater')){

		/*home slide start*/		
			$wp_customize->add_setting('themefarmer_home_slider', array(
				'sanitize_callback' => 'storeone_field_repeater_sanitize',
				'transport'         => 'refresh',
				'default'           => array(
					array(
						'heading'      => esc_attr__('Slide 1', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        =>  get_template_directory_uri() . '/images/slide1.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),
					array(
						'heading'      => esc_attr__('Slide 2', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        =>  get_template_directory_uri() . '/images/slide2.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),

					array(
						'heading'      => esc_attr__('Slide 3', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        =>  get_template_directory_uri() . '/images/slide3.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),
				),
			));

			$wp_customize->add_control(new ThemeFarmer_Field_Repeater($wp_customize, 'themefarmer_home_slider', array(
				'label'       => esc_html__('Slide', 'storeone'),
				'description' => __("Recommended Image Size <br>Desktop 1920x425px <br> Tablet 1200x930px in Pro<br>Mobile 550x575px In Pro<br><br>", 'storeone'),
				'section'     => 'storeone_home_slider_section',
				'responsive'  => false,
				'max_count'   => 3,
				'priority'    => 30,
				'row_label'   => esc_html__('Slide', 'storeone'),
				'fields'      => array(
					'heading'      => array(
						'type'    => 'text',
						'label'   => esc_attr__('Title', 'storeone'),
						'default' => esc_attr('Slide Heading', 'storeone'),
					),
					'description'  => array(
						'type'    => 'textarea',
						'label'   => esc_attr__('Description', 'storeone'),
						'default' => esc_attr('Awesome Slide Description', 'storeone'),
					),
					'image'        => array(
						'type'  => 'image',
						'label' => esc_attr__('Image', 'storeone'),
					),
					'button1_text' => array(
						'type'    => 'text',
						'label'   => esc_attr__('Button Text', 'storeone'),
						'default' => esc_attr__('Read More', 'storeone'),
					),
					'button1_url'  => array(
						'type'    => 'text',
						'label'   => esc_attr__('Button URL', 'storeone'),
						'default' => esc_url('#'),
					),
				),
			)));

			$wp_customize->selective_refresh->add_partial('themefarmer_home_slider', array(
				'selector'         => '.home-swiper .carousel-caption',
				'fallback_refresh' => false,
			));
		/*home slider end*/
		
		/*Blog slider*/
			$wp_customize->add_setting('themefarmer_blog_slider', array(
				'sanitize_callback' => 'storeone_field_repeater_sanitize',
				'transport'         => 'refresh',
				'default'           => array(
					array(
						'heading'      => esc_attr__('Slide 1', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        => get_template_directory_uri() . '/images/shop-slide1.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),
					array(
						'heading'      => esc_attr__('Slide 2', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        => get_template_directory_uri() . '/images/shop-slide2.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),

					array(
						'heading'      => esc_attr__('Slide 3', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        => get_template_directory_uri() . '/images/shop-slide3.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),
				),
			));

			$wp_customize->add_control(new ThemeFarmer_Field_Repeater($wp_customize, 'themefarmer_blog_slider', array(
				'label'       => esc_html__('Slides', 'storeone'),
				'description' => __("Recommended Image Size <br>Desktop 1920x1024px", 'storeone'),
				'section'     => 'storeone_blog_slider_section',
				'responsive'  => false,
				'priority'    => 30,
				'row_label'   => esc_html__('Slide', 'storeone'),
				'fields'      => array(
					'heading'      => array(
						'type'    => 'text',
						'label'   => esc_attr__('Title', 'storeone'),
						'default' => esc_attr('Slide Heading', 'storeone'),
					),
					'description'  => array(
						'type'    => 'textarea',
						'label'   => esc_attr__('Description', 'storeone'),
						'default' => esc_attr('Awesome Slide Description', 'storeone'),
					),
					'image'        => array(
						'type'  => 'image',
						'label' => esc_attr__('Image', 'storeone'),
					),
					'button1_text' => array(
						'type'    => 'text',
						'label'   => esc_attr__('Button Text', 'storeone'),
						'default' => esc_attr__('Read More', 'storeone'),
					),
					'button1_url'  => array(
						'type'    => 'text',
						'label'   => esc_attr__('Button URL', 'storeone'),
						'default' => esc_url('#'),
					),

				),
			)));

			$wp_customize->selective_refresh->add_partial('themefarmer_blog_slider', array(
				'selector'         => '.blog-swiper .carousel-caption',
				'fallback_refresh' => false,
			));
		/*Blog slider*/

		/*Shop slider*/
			$wp_customize->add_setting('themefarmer_shop_slider', array(
				'sanitize_callback' => 'storeone_field_repeater_sanitize',
				'transport'         => 'refresh',
				'default'           => array(
					array(
						'heading'      => esc_attr__('Slide 1', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        => get_template_directory_uri() . '/images/shop-slide1.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),
					array(
						'heading'      => esc_attr__('Slide 2', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        => get_template_directory_uri() . '/images/shop-slide2.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),
					array(
						'heading'      => esc_attr__('Slide 3', 'storeone'),
						'description'  => esc_attr__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut est tempus risus tempor ullamcorper vitae vitae ex.', 'storeone'),
						'image'        => get_template_directory_uri() . '/images/shop-slide3.jpg',
						'button1_text' => esc_attr__('View Details', 'storeone'),
						'button1_url'  => '#',
					),
				),
			));

			$wp_customize->add_control(new ThemeFarmer_Field_Repeater($wp_customize, 'themefarmer_shop_slider', array(
				'label'       => esc_html__('Slides', 'storeone'),
				'description' => __("Recommended Image Size <br>Desktop 1920x1024px", 'storeone'),
				'section'     => 'storeone_shop_slider_section',
				'responsive'  => false,
				'priority'    => 30,
				'row_label'   => esc_html__('Slide', 'storeone'),
				'fields'      => array(
					'heading'      => array(
						'type'    => 'text',
						'label'   => esc_attr__('Title', 'storeone'),
						'default' => esc_attr('Slide Heading', 'storeone'),
					),
					'description'  => array(
						'type'    => 'textarea',
						'label'   => esc_attr__('Description', 'storeone'),
						'default' => esc_attr('Awesome Slide Description', 'storeone'),
					),
					'image'        => array(
						'type'  => 'image',
						'label' => esc_attr__('Image', 'storeone'),
					),
					'button1_text' => array(
						'type'    => 'text',
						'label'   => esc_attr__('Button Text', 'storeone'),
						'default' => esc_attr__('Read More', 'storeone'),
					),
					'button1_url'  => array(
						'type'    => 'text',
						'label'   => esc_attr__('Button URL', 'storeone'),
						'default' => esc_url('#'),
					),
				),
			)));

			$wp_customize->selective_refresh->add_partial('themefarmer_shop_slider', array(
				'selector'         => '.shop-swiper .carousel-caption',
				'fallback_refresh' => false,
			));
		/*Shop slider*/

		/*Testimonials start*/
			$wp_customize->add_setting('themefarmer_home_testimonials', array(
				'sanitize_callback' => 'storeone_field_repeater_sanitize',
				'transport'         => 'refresh',
				'default'           => array(
					array(
						'image'       => get_template_directory_uri() . '/images/shop-slide1.jpg',
						'title'     => esc_attr__('Testimonial Heading', 'storeone'),
						'description' => esc_attr__('Testimonial Description', 'storeone'),
					),
					array(
						'image'       => get_template_directory_uri() . '/images/shop-slide2.jpg',
						'title'     => esc_attr__('Jhon Doe', 'storeone'),
						'description' => esc_attr__('Testimonial Description', 'storeone'),
					),
				),
			));

			$wp_customize->add_control(new ThemeFarmer_Field_Repeater($wp_customize, 'themefarmer_home_testimonials', array(
				'label'     => esc_html__('Testimonials', 'storeone'),
				'section'   => 'storeone_home_testimonial_section',
				'priority'  => 30,
				'row_label' => esc_html__('Testimonial', 'storeone'),
				'fields'    => array(
					'image'       => array(
						'type'    => 'image',
						'label'   => esc_attr__('Image', 'storeone'),
						'default' => esc_url(get_template_directory_uri() . '/images/slide1.jpg'),
					),
					'title'       => array(
						'type'  => 'text',
						'label' => esc_attr__('Title', 'storeone'),
					),
					'description' => array(
						'type'  => 'textarea',
						'label' => esc_attr__('Description', 'storeone'),
					),
				),
			)));

			$wp_customize->selective_refresh->add_partial('themefarmer_home_testimonials', array(
				'selector'         => '.section-testimonials .testimonial-item',
				'fallback_refresh' => false,
			));
		/*Testimonials end*/
	}
}


if (!function_exists('storeone_extension')) {
	function storeone_extension() {

	}
}

function storeone_extension_init() {
	load_plugin_textdomain('storeone-extension', false, dirname(plugin_basename(__FILE__)) . '/lang');
}

function storeone_extension_is_storeone_active(){
	$theme = wp_get_theme();
	if ($theme->name == 'StoreOne' || $theme->parent_theme == 'StoreOne') {
		return true;
	}else{
		return false;
	}
}

function storeone_extension_theme_update_notice(){
	$theme = wp_get_theme();
	$theme_notice = '';
	if ($theme->name == 'StoreOne' || $theme->parent_theme == 'StoreOne') {
		if(version_compare($theme->version, '2.0.0') >= 0){
			
		}else{
			?>
			<div class="error notice storeone-extension-notice-dissmiss is-dismissible" data-notice="theme_slider_moved_dissmiss">
				<p> <strong><?php esc_html_e( 'Storeon Theme Notice', 'default' ); ?>:</strong> <?php esc_html__( "Please update theme StoreOne to latest version or sliders and testimonial won't work", 'storeone-extension' ); ?> </p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'storeone-extension' ); ?></span>
				</button>
			</div>
			<?php
		}
	}

	?>
	<div class="error notice storeone-extension-notice-dissmiss is-dismissible" data-notice="theme_slider_moved_dissmiss">
		<p> <strong><?php esc_html_e( 'Storeon Extension Notice', 'default' ); ?>:</strong> <?php esc_html_e( 'Sliders and Testimonials are now moved to custmoizer.', 'storeone-extension' ); ?> </p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'storeone-extension' ); ?></span>
		</button>
	</div>
	<?php
}

if(!get_option('storeone_extension_theme_slider_moved_dissmiss', false)){
	add_action( 'admin_notices', 'storeone_extension_theme_update_notice');
}

function storeone_extension_ajax_dismissed_notice_handler(){
	update_option( 'storeone_extension_theme_slider_moved_dissmiss', true);
}
add_action( 'wp_ajax_storeone_extension_dismissed_notice_handler', 'storeone_extension_ajax_dismissed_notice_handler' );




function storeone_extension_enqueue_scripts(){
	wp_enqueue_script( 'storeone-extension-admin-script', plugins_url('/js/admin-script.js', __FILE__), array( 'jquery' ), false, false );
	wp_localize_script( 'storeone-extension-admin-script', 'storeone_extension', 
		array('ajax_url' => admin_url('admin-ajax.php'))
	);
}
add_action('admin_enqueue_scripts', 'storeone_extension_enqueue_scripts');


register_activation_hook( __FILE__, 'storeone_extension_activation' );
function storeone_extension_activation() {
	
	if (storeone_extension_is_storeone_active()) {

		$front_page = get_option('show_on_front');
		if ($front_page !== 'page') {

			$page_home    = get_page_by_path('home');
			$page_home_id = 0;
			if (empty($page_home)) {
				$page_home_id = wp_insert_post(array(
					'post_type'   => 'page',
					'post_title'  => esc_html__('Home', 'storeone-extension'),
					'post_status' => 'publish',
					'post_name'   => 'home',
				));
			} else if (absint($page_home->ID) > 0) {
				$page_home_id = $page_home->ID;
			}

			$page_blog    = get_page_by_path('blog');
			$page_blog_id = 0;
			if (empty($page_blog)) {
				$page_blog_id = wp_insert_post(array(
					'post_type'   => 'page',
					'post_title'  => esc_html__('Blog', 'storeone-extension'),
					'post_status' => 'publish',
					'post_name'   => 'blog',
				));
			} else if (absint($page_blog->ID) > 0) {
				$page_blog_id = $page_blog->ID;
			}

			if (absint($page_home_id) > 0 && absint($page_blog_id) > 0) {
				update_option('page_on_front', $page_home_id);
				update_option('page_for_posts', $page_blog_id);
				update_option('show_on_front', 'page');
			}
		}
	}

	flush_rewrite_rules();

}
