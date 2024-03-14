<?php
namespace A3Rev\RSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Hook_Filter
{

	public static function include_frontend_script() {
		global $wp_scripts;

		$_upload_dir = wp_upload_dir();

		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( 'a3_responsive_slider_styles', A3_RESPONSIVE_SLIDER_CSS_URL . '/cycle.css', array(), A3_RESPONSIVE_SLIDER_VERSION );

		if ( file_exists( $_upload_dir['basedir'] . '/sass/a3_responsive_slider'.$suffix.'.css' ) ) {
			wp_register_style( 'a3_rslider_template1', str_replace(array('http:','https:'), '', $_upload_dir['baseurl'] ) . '/sass/a3_responsive_slider'.$suffix.'.css', array( 'a3_responsive_slider_styles' ), $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'less']->get_css_file_version() );
		}

		wp_register_script( 'a3-cycle2-script', A3_RESPONSIVE_SLIDER_JS_URL . '/jquery.cycle2'. $suffix .'.js', array( 'jquery' ), '2.1.6' );
		wp_register_script( 'a3-cycle2-center-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.center'. $suffix .'.js', array( 'jquery', 'a3-cycle2-script' ), '2.1.6' );
		wp_register_script( 'a3-cycle2-caption2-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.caption2'. $suffix .'.js', array( 'jquery', 'a3-cycle2-script' ), '2.1.6' );
		wp_register_script( 'a3-cycle2-swipe-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.swipe'. $suffix .'.js', array( 'jquery', 'a3-cycle2-script' ), '2.1.6' );

		// For Desktop
		wp_register_script( 'a3-cycle2-flip-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.flip'. $suffix .'.js', array( 'jquery', 'a3-cycle2-script' ), '2.1.6' );
		wp_register_script( 'a3-cycle2-scrollVert-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.scrollVert'. $suffix .'.js', array( 'jquery', 'a3-cycle2-script' ), '2.1.6' );
		wp_register_script( 'a3-cycle2-shuffle-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.shuffle'. $suffix .'.js', array( 'jquery', 'a3-cycle2-script' ), '2.1.6' );
		wp_register_script( 'a3-cycle2-tile-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.tile'. $suffix .'.js', array( 'jquery', 'a3-cycle2-script' ), '2.1.6' );
		wp_register_script( 'a3-cycle2-ie-fade-script', A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL . '/jquery.cycle2.ie-fade'. $suffix .'.js', array( 'jquery', 'a3-cycle2-script' ), '2.1.6' );
		$wp_scripts->add_data( 'a3-cycle2-ie-fade-script', 'conditional', 'IE' );

		wp_register_script( 'a3-rslider-frontend', A3_RESPONSIVE_SLIDER_JS_URL . '/a3-rslider-frontend.js', array( 'jquery', 'a3-cycle2-script' ), A3_RESPONSIVE_SLIDER_VERSION );

		// For Mobile
		wp_register_script( 'a3-rslider-frontend-mobile', A3_RESPONSIVE_SLIDER_JS_URL . '/a3-rslider-frontend-mobile.js', array( 'jquery', 'a3-cycle2-script' ), A3_RESPONSIVE_SLIDER_VERSION );

		if ( is_admin() ) return;

		global $post;
		$our_shortcode = 'a3_responsive_slider';
		// Check if a3_responsive_slider shortcode is in the content
		if ( $post && has_shortcode( $post->post_content, $our_shortcode ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER );
			if ( ! empty( $matches ) && is_array( $matches ) && count( $matches ) > 0 ) {
				foreach ( $matches as $shortcode ) {
					if ( $our_shortcode === $shortcode[2] ) {
						$attr = shortcode_parse_atts( $shortcode[3] );
						$my_attr = shortcode_atts( array(
			 							'id' 				=> 0
									), $attr );
						$slider_id = $my_attr['id'];
						if ( $slider_id > 0 ) {
							$slider_data = get_post( $slider_id );
							if ( $slider_data == NULL ) return '';
							$have_slider_id = get_post_meta( $slider_id, '_a3_slider_id' , true );
							if ( $have_slider_id < 1 ) return '';

							$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
							$slider_template = 'template-1';

							extract( $slider_settings );
							$slider_transition_data 		= Functions::get_slider_transition( $slider_transition_effect, $slider_settings );
							$fx 							= $slider_transition_data['fx'];

							$templateid = 'template1';

							$script_settings = array(
								'fx'       => $fx,
								'caption2' => false,
								'swipe'    => true,
								'video'    => false,
					    	);

					    	self::enqueue_frontend_script( $script_settings );
						}
					}
				}
			}
		}

	}

	public static function enqueue_frontend_script( $script_settings = array() ) {

		if ( ! is_array( $script_settings ) || count( $script_settings ) <= 0 || is_admin() ){
			$script_settings = array(
				'fx'       => 'fade',
				'caption2' => true,
				'swipe'    => true,
				'video'    => false,
	    	);
		}

		wp_enqueue_style( 'a3_rslider_template1' );

		$device_detect = new Mobile_Detect();
		if ( ! $device_detect->isMobile() ) {
			if ( in_array( $script_settings['fx'], array( 'random', 'flipHorz', 'flipVert' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-flip-script' );
			}
			if ( in_array( $script_settings['fx'], array( 'random', 'scrollHorz', 'scrollVert' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-scrollVert-script' );
			}
			if ( in_array( $script_settings['fx'], array( 'random', 'shuffle' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-shuffle-script' );
			}
			if ( in_array( $script_settings['fx'], array( 'random', 'tileSlide', 'tileBlind' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-tile-script' );
			}
			if ( in_array( $script_settings['fx'], array( 'random', 'fade', 'fadeout' ) ) ) {
				wp_enqueue_script( 'a3-cycle2-ie-fade-script' );
			}

			wp_enqueue_script( 'a3-rslider-frontend' );
		} else {
			wp_enqueue_script( 'a3-rslider-frontend-mobile' );
		}

		$a3_rslider_frontend_params = array();
		if ( function_exists( 'a3_lazy_load_enable' ) && ! class_exists( 'A3_Portfolio' ) && ! class_exists( '\A3Rev\Portfolio' ) ) {
			$a3_rslider_frontend_params['enable_lazyload'] = 1;
		} else {
			$a3_rslider_frontend_params['enable_lazyload'] = 0;
		}

		wp_localize_script( 'a3-rslider-frontend', 'a3_rslider_frontend_params', $a3_rslider_frontend_params );
		wp_localize_script( 'a3-rslider-frontend-mobile', 'a3_rslider_frontend_params', $a3_rslider_frontend_params );

		wp_enqueue_script( 'a3-cycle2-center-script' );

		if ( $script_settings['caption2'] ) {
			wp_enqueue_script( 'a3-cycle2-caption2-script' );
		}
		if ( $device_detect->isMobile() && $script_settings['swipe'] ){
			wp_enqueue_script( 'a3-cycle2-swipe-script' );
		}
	}

	public static function add_google_fonts() {
		$google_fonts = array( );

		$templateid = 'template1';

			global ${'a3_rslider_'.$templateid.'_title_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
			global ${'a3_rslider_'.$templateid.'_caption_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
			global ${'a3_rslider_'.$templateid.'_readmore_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
			global ${'a3_rslider_'.$templateid.'_shortcode_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore

			extract( ${'a3_rslider_'.$templateid.'_title_settings'} );
			extract( ${'a3_rslider_'.$templateid.'_caption_settings'} );

			$google_fonts[] = $title_font['face'];
			$google_fonts[] = $caption_font['face'];

				extract( ${'a3_rslider_'.$templateid.'_readmore_settings'} );
				$google_fonts[] = $readmore_link_font['face'];
				$google_fonts[] = $readmore_bt_font['face'];

				extract( ${'a3_rslider_'.$templateid.'_shortcode_settings'} );
				$google_fonts[] = $shortcode_description_font['face'];


		if ( count( $google_fonts ) > 0 ) $GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'fonts_face']->generate_google_webfonts( $google_fonts );
	}

	public static function include_customized_style() {
		include( A3_RESPONSIVE_SLIDER_DIR. '/includes/customized_style.php' );
	}

	public static function include_admin_add_script() {

		wp_enqueue_style( 'galleries-style', A3_RESPONSIVE_SLIDER_CSS_URL.'/admin_slider.css', array( 'thickbox' ), A3_RESPONSIVE_SLIDER_VERSION );
		wp_enqueue_script( 'galleries-script', A3_RESPONSIVE_SLIDER_JS_URL.'/admin_slider.js', array( 'jquery', 'thickbox', 'jquery-ui-sortable', 'jquery-ui-draggable' ), A3_RESPONSIVE_SLIDER_VERSION );
	}

	public static function a3_wp_admin() {
		wp_enqueue_style( 'a3rev-wp-admin-style', A3_RESPONSIVE_SLIDER_CSS_URL . '/a3_wp_admin.css' );
	}

	public static function admin_sidebar_menu_css() {
		wp_enqueue_style( 'a3rev-responsive-slider-admin-sidebar-menu-style', A3_RESPONSIVE_SLIDER_CSS_URL . '/admin_sidebar_menu.css' );
	}

	public static function plugin_extension() {
		$html = '';
		$html .= '<a href="http://a3rev.com/shop/" target="_blank" style="float:right;margin-top:5px; margin-left:10px; clear:right;" ><div class="a3-plugin-ui-icon a3-plugin-ui-a3-rev-logo"></div></a>';
		$html .= '<h3>'.__('Thanks for choosing to install the a3 Responsive Slider Lite.', 'a3-responsive-slider' ).'</h3>';
		$html .= '<h3>'.__('What is the Yellow border sections about?', 'a3-responsive-slider' ).'</h3>';
		$html .= '<p>'.__('Inside the Yellow border you will see the settings for the a3 Responsive Slider Pro version plugin. You can see the settings but they are not active.', 'a3-responsive-slider' ).'</p>';

		$html .= '<h3 style="margin-bottom:5px;">* <a href="'.A3_RESPONSIVE_SLIDER_PRO_VERSION_URI.'" target="_blank">'.__('a3 Responsive Slider Pro', 'a3-responsive-slider' ).'</a></h3>';
		$html .= '<p>';
		$html .= '* '.__('Activates Youtube Video Slides.', 'a3-responsive-slider' ).'<br />';
		$html .= '* '.__('Activates Ken Burns transition Effect.', 'a3-responsive-slider' ).'<br />';
		$html .= '* '.__('Activates the 2nd custom Slider Skin.', 'a3-responsive-slider' ).'<br />';
		$html .= '* '.__('Activates the custom Card Skin.', 'a3-responsive-slider' ).'<br />';
		$html .= '* '.__('Activates the custom Widget Skin.', 'a3-responsive-slider' ).'<br />';
		$html .= '* '.__('Activates the custom Touch Mobile Skin.', 'a3-responsive-slider' ).'<br />';
		$html .= '* '.__('Access to the plugins a3rev support forum.', 'a3-responsive-slider' );
		$html .= '</p>';

		$html .= '<p>'.__("If you are trailing the Pro version must:", 'a3-responsive-slider' ).'<br />';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>1. '.__('DEACTIVATE the Lite BEFORE installing and activating another version.', 'a3-responsive-slider' ).'</li>';
		$html .= '<li>2. '.__("If you don't you will get a FATAL ERROR.", 'a3-responsive-slider' ).'</li>';
		$html .= '<li>3. '.__('All data - sliders, settings and activations will be present in the newly activated version.', 'a3-responsive-slider' ).'</li>';
		$html .= '<li>4. '.__('WARNING - If you DELETE this plugin BEFORE you activate another version of the slider, all slider settings will be lost.', 'a3-responsive-slider' ).'</li>';
		$html .= '</ul>';
		$html .= '</p>';

		$html .= '<h3>'.__('More a3rev Free WordPress plugins', 'a3-responsive-slider' ).'</h3>';
		$html .= '<p>';
		$html .= '<ul style="padding-left:10px;">';
		$html .= '<li>* <a href="https://wordpress.org/plugins/a3-lazy-load/" target="_blank">'.__('a3 Lazy Load', 'a3-responsive-slider' ).'</a> &nbsp;&nbsp;&nbsp; <sup>*</sup>'.__( 'New Plugin' , 'a3-responsive-slider' ).'</li>';
		$html .= '<li>* <a href="https://wordpress.org/plugins/a3-portfolio/" target="_blank">'.__('a3 Portfolio', 'a3-responsive-slider' ).'</a> &nbsp;&nbsp;&nbsp; <sup>*</sup>'.__( 'New Plugin' , 'a3-responsive-slider' ).'</li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/wp-email-template/" target="_blank">'.__('WP Email Template', 'a3-responsive-slider' ).'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/plugins/contact-us-page-contact-people/" target="_blank">'.__('Contact Us Page - Contact People', 'a3-responsive-slider' ).'</a></li>';
		$html .= '<li>* <a href="http://wordpress.org/extend/plugins/page-views-count/" target="_blank">'.__('Page View Count', 'a3-responsive-slider' ).'</a></li>';
		$html .= '</ul>';
		$html .= '</p>';


		return $html;
	}

	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != A3_RESPONSIVE_SLIDER_NAME) {
			return $links;
		}

		$links[] = '<a href="http://docs.a3rev.com/user-guides/plugins-extensions/wordpress/a3-responsive-slider/" target="_blank">'.__('Documentation', 'a3-responsive-slider' ).'</a>';
		$links[] = '<a href="'.$GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_init']->support_url.'" target="_blank">'.__('Support', 'a3-responsive-slider' ).'</a>';
		return $links;
	}

	public static function plugin_extension_box( $boxes = array() ) {

		$support_box = '<a href="'.$GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_init']->support_url.'" target="_blank" alt="'.__('Go to Support Forum', 'a3-responsive-slider' ).'"><img src="'.A3_RESPONSIVE_SLIDER_IMAGES_URL.'/go-to-support-forum.png" /></a>';
		$boxes[] = array(
			'content' => $support_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$free_wordpress_box = '<a href="https://profiles.wordpress.org/a3rev/#content-plugins" target="_blank" alt="'.__('Free WordPress Plugins', 'a3-responsive-slider' ).'"><img src="'.A3_RESPONSIVE_SLIDER_IMAGES_URL.'/free-wordpress-plugins.png" /></a>';

		$boxes[] = array(
			'content' => $free_wordpress_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

		$free_woocommerce_box = '<a href="https://profiles.wordpress.org/a3rev/#content-plugins" target="_blank" alt="'.__('Free WooCommerce Plugins', 'a3-responsive-slider' ).'"><img src="'.A3_RESPONSIVE_SLIDER_IMAGES_URL.'/free-woocommerce-plugins.png" /></a>';

		$boxes[] = array(
			'content' => $free_woocommerce_box,
			'css' => 'border: none; padding: 0; background: none;'
		);

        $review_box = '<div style="margin-bottom: 5px; font-size: 12px;"><strong>' . __('Is this plugin is just what you needed? If so', 'a3-responsive-slider' ) . '</strong></div>';
        $review_box .= '<a href="https://wordpress.org/support/plugin/a3-responsive-slider/reviews/?filter=5" target="_blank" alt="'.__('Submit Review for Plugin on WordPress', 'a3-responsive-slider' ).'"><img src="'.A3_RESPONSIVE_SLIDER_IMAGES_URL.'/a-5-star-rating-would-be-appreciated.png" /></a>';

        $boxes[] = array(
            'content' => $review_box,
            'css' => 'border: none; padding: 0; background: none;'
        );

        $connect_box = '<div style="margin-bottom: 5px;">' . __('Connect with us via','a3-responsive-slider' ) . '</div>';
		$connect_box .= '<a href="https://www.facebook.com/a3rev" target="_blank" alt="'.__('a3rev Facebook', 'a3-responsive-slider' ).'" style="margin-right: 5px;"><img src="'.A3_RESPONSIVE_SLIDER_IMAGES_URL.'/follow-facebook.png" /></a> ';
		$connect_box .= '<a href="https://twitter.com/a3rev" target="_blank" alt="'.__('a3rev Twitter', 'a3-responsive-slider' ).'"><img src="'.A3_RESPONSIVE_SLIDER_IMAGES_URL.'/follow-twitter.png" /></a>';

		$boxes[] = array(
			'content' => $connect_box,
			'css' => 'border-color: #3a5795;'
		);

		return $boxes;
	}

	public static function settings_plugin_links($actions) {
		$actions = array_merge( array( 'settings' => '<a href="edit.php?post_type=a3_slider">' . __( 'Sliders', 'a3-responsive-slider' ) . '</a>' ), $actions );

		return $actions;
	}
}
