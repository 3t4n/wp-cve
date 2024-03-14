<?php
/*
Plugin Name: Mega Menu Plugin for WordPress
Plugin URI: https://www.famethemes.com
Description: The EasyMega plugin helps you create mega menu easily, beautifully in any themes. Using the lightweight live Customizer system.
Author: famethemes
Author URI: https://www.famethemes.com
Version: 1.0.9
Text Domain: megamenu-wp
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/




if ( ! class_exists( 'MegaMenu_WP' ) ) {
	class MegaMenu_WP {

		function __construct() {
			include MAGAZINE_MEGA_MENU_PATH . 'inc/admin.php';
			include MAGAZINE_MEGA_MENU_PATH . 'inc/class-mega-item.php';
			include MAGAZINE_MEGA_MENU_PATH . 'inc/menu.php';
			include MAGAZINE_MEGA_MENU_PATH . 'inc/settings.php';
			include MAGAZINE_MEGA_MENU_PATH . 'inc/theme-supports.php';

			if ( is_admin() ) {
				include MAGAZINE_MEGA_MENU_PATH . 'inc/dashboard.php';
			}
			if ( ! is_admin() ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 3 );
			}

			add_action( 'wp_ajax_megamneu_wp_load_posts', array( __CLASS__, 'ajax_load_posts' ) );
			add_action( 'wp_ajax_nopriv_megamneu_wp_load_posts', array( __CLASS__, 'ajax_load_posts' ) );
			add_filter( 'widget_text', 'do_shortcode' );
		}

		static function get_theme_support( $feature = null, $default = null ) {
			$options = array(
				'mobile_mod'        => 0, // Break point when toggle mobile mod
				'disable_auto_css'  => 0, // Do not apply auto css
				'disable_css'       => 0, // Do not load plugin css
				'parent_level'      => 0, // Default parent Level
				'content_right'     => 0, // Content right
				'content_left'      => 0,  // Content left
				'margin_top'        => 0,  // Content left
				'animation'         => '',  // Animation
				'child_li'          => '',  // Use ul li for menu item children
				'ul_css'            => '',  // Css of child mega `ul.mega-content` element
				'li_css'            => '',  // Css of child mega `ul.mega-conten li.mega-content-li` element
			);

			$support = apply_filters( 'megamenu_wp_get_theme_support_args', get_theme_support( 'megamenu-wp' ) );

			if ( is_array( $support ) && ! empty( $support ) ) {
				$sp = current( $support );
				if ( ! $feature ) {
					$sp = wp_parse_args( $sp, $default );
					return apply_filters( 'megamenu_wp_get_theme_support', $sp, $feature, $sp );
				}

				if ( is_array( $sp ) ) {
					if ( isset( $sp[ $feature ] ) ) {
						return apply_filters( 'megamenu_wp_get_theme_support', $sp[ $feature ], $feature, $sp );
					} else {
						return apply_filters( 'megamenu_wp_get_theme_support', false, $feature, $options );
					}
				}
			}

			if ( $feature ) {
				return apply_filters( 'megamenu_wp_get_theme_support', false, $feature, $options );
			}
			return apply_filters( 'megamenu_wp_get_theme_support', wp_parse_args( $default, $options ), $feature, $options );
		}

		static function get_pro_url() {
			return apply_filters( 'megamenuwp-pro-url', 'https://www.famethemes.com/plugins/easymega-pro/' );
		}

		function scripts() {
			$support = $this->get_theme_support();
			if ( ! isset( $support['disable_css'] ) || ! $support['disable_css'] ) {
				wp_enqueue_style( 'megamenu-wp', MAGAZINE_MEGA_MENU_URL . 'style.css' );
			}
			wp_enqueue_script( 'megamenu-wp', MAGAZINE_MEGA_MENU_URL . 'assets/js/megamenu-wp.js', array( 'jquery' ), '1.0.1', true );

			$args = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'loading_icon' => apply_filters( 'megamenu_wp_loading_icon', '<div class="mega-spinner"><div class="uil-squares-css" style="transform:scale(0.4);"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div></div>' ),
				'theme_support' => $support,
			);

			if ( ! $args['theme_support']['mobile_mod'] ) {
				$args['theme_support']['mobile_mod'] = absint( get_theme_mod( 'mega_mobile_break_points', 720 ) );
			}

			if ( ! isset( $args['theme_support']['disable_auto_css'] ) || ! $args['theme_support']['disable_auto_css'] ) {
				$args['theme_support']['disable_auto_css'] = absint( get_theme_mod( 'mega_disable_css', false ) );
			}

			if ( isset( $support['parent_level'] ) && $support['parent_level'] ) {
				$args['mega_parent_level'] = absint( $support['parent_level'] );
			} else {
				$args['mega_parent_level'] = absint( get_theme_mod( 'mega_parent_level' ) );
			}

			if ( isset( $support['content_left'] ) && $support['content_left'] ) {
				$args['mega_content_left'] = floatval( $support['content_left'] );
			} else {
				$args['mega_content_left'] = floatval( get_theme_mod( 'mega_content_left' ) );
			}

			if ( isset( $support['content_right'] ) && $support['content_right'] ) {
				$args['mega_content_right'] = floatval( $support['content_right'] );
			} else {
				$args['mega_content_right'] = floatval( get_theme_mod( 'mega_content_right' ) );
			}

			$args['mega_content_right'] = floatval( get_theme_mod( 'mega_content_right' ) );

			if ( ! isset( $support['animation'] ) || ! $support['animation'] ) {
				$args['animation'] = get_theme_mod( 'mega_animation' );
				if ( ! $args['animation'] ) {  // shift-up,  shift-down, shift-left, shift-right, fade, flip, animation-none
					$args['animation'] = 'shift-up';
				}
			} else {
				$args['animation'] = $support['animation'];
			}

			wp_localize_script( 'megamenu-wp', 'MegamenuWp', apply_filters( 'megamenu_wp_localize_script_args', $args ) );

			if ( $support['margin_top'] ) {
				$margin_top = $support['margin_top'];
			} else {
				$margin_top = get_theme_mod( 'mega_content_margin_top' );
			}

			$margin_top = floatval( $margin_top );
			$css = '.megamenu-wp-desktop #megamenu-wp-page .megamenu-wp .mega-item .mega-content li.mega-content-li { margin-top: ' . $margin_top . 'px; }';
			if ( isset( $support['ul_css'] ) && $support['ul_css'] ) {
				$css .= '.megamenu-wp-desktop #megamenu-wp-page .megamenu-wp .mega-item .mega-content li.mega-content-li{ ' . $support['ul_css'] . ' }';
			}

			if ( isset( $support['li_css'] ) && $support['li_css'] ) {
				$css .= '.megamenu-wp-desktop  .megamenu-wp .mega-item .mega-content li.mega-content-li{ ' . $support['li_css'] . ' }';
			}

			if ( isset( $support['custom_css'] ) ) {
				$css .= $support['custom_css'];
			}

			wp_add_inline_style( 'megamenu-wp', $css );

		}

		static function get_template( $template ) {
			$template_folders = array(
				get_stylesheet_directory() . '/', // Child theme
				get_stylesheet_directory() . '/templates/', // child theme
				get_template_directory() . '/', // Parent theme
				get_template_directory() . '/templates/', // Parent theme
				MAGAZINE_MEGA_MENU_PATH . 'templates/', // Plugin
			);

		

			foreach ( $template_folders as $folder ) {
				$file = $folder . $template;
				if ( file_exists( $file ) ) {
					return apply_filters( 'megamenuwp_get_nav_template', $file, $template );
				}
			}

			return apply_filters( 'megamenuwp_get_nav_template', false, $template );
		}

		static function get_previewing_data( $key, $default = null ) {
			if ( ! isset( $GLOBALS['_customized_decode'] ) || ! is_array( $GLOBALS['_customized_decode'] ) ) {
				return $default;
			}
			if ( is_array( $key ) ) {
				if ( isset( $GLOBALS['_customized_decode'][ $key[0] ] ) && is_array( $GLOBALS['_customized_decode'][ $key[0] ] ) ) {
					if ( $GLOBALS['_customized_decode'][ $key[0] ][ $key[1] ] ) {
						return $GLOBALS['_customized_decode'][ $key[0] ][ $key[1] ];
					}
				}
			} else {
				if ( isset( $GLOBALS['_customized_decode'][ $key ] ) ) {
					return $GLOBALS['_customized_decode'][ $key ];
				}
			}

			return $default;
		}

		static function is_mega_nav_active( $nav_id ) {
			if ( isset( $GLOBALS[ '_mega_menu_enable_' . $nav_id ] ) ) {
				return $GLOBALS[ '_mega_menu_enable_' . $nav_id ];
			}

			$key = 'nav_menu[' . $nav_id . ']';
			if ( self::is_preview( array( $key, 'mega_enable' ) ) ) {
				$mega_enable = self::get_previewing_data( array( $key, 'mega_enable' ) );
			} else {
				$mega_enable = get_term_meta( $nav_id, '_mega_enable', true );
			}

			$GLOBALS[ '_mega_menu_enable_' . $nav_id ] = $mega_enable;
			return $GLOBALS[ '_mega_menu_enable_' . $nav_id ];
		}

		static function is_preview( $key_check = false ) {
			if ( is_customize_preview() ) {
				if ( isset( $_POST['wp_customize'] ) && $_POST['wp_customize'] == 'on' ) {
					if ( ! isset( $GLOBALS['_customized_decode'] ) ) {
						if ( isset( $_POST['customized'] ) ) {
							$GLOBALS['_customized_decode'] = json_decode( wp_unslash( $_POST['customized'] ), true );
						} else {
							$GLOBALS['_customized_decode'] = array();
						}
					}

					if ( $key_check ) {
						if ( is_array( $key_check ) ) {
							if ( isset( $GLOBALS['_customized_decode'] [ $key_check[0] ] ) ) {
								return isset( $GLOBALS['_customized_decode'][ $key_check[0] ] [ $key_check[1] ] );
							}
						} else {
							return isset( $GLOBALS['_customized_decode'][ $key_check ] );
						}
					}
				}
			}
			return false;
		}

		static function ajax_load_posts() {
			 $args = MegaMenu_WP_Menu_Item::get_post_query_args( $_REQUEST );
			$content = MegaMenu_WP_Menu_Item::posts_content( $args );
			wp_send_json_success( $content );
			die();
		}
	}
}



function MegaMenu_WP__Init() {
	if ( ! defined( 'MAGAZINE_MEGA_MENU_PATH' ) ) {
		define( 'MAGAZINE_MEGA_MENU_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
		define( 'MAGAZINE_MEGA_MENU_PATH', trailingslashit( dirname( __FILE__ ) ) );
		$GLOBALS['MegaMenu_WP'] = new MegaMenu_WP();
	}
}
add_action( 'init', 'MegaMenu_WP__Init', 35 );


function megamenuwp_activation_redirect( $plugin ) {
	if ( $plugin == plugin_basename( __FILE__ ) ) {
		exit( wp_redirect( admin_url( 'options-general.php?page=megamenu-wp' ) ) );
	}
}
add_action( 'activated_plugin', 'megamenuwp_activation_redirect' );


