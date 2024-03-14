<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket {

    
	public $background_processor = null;
	
	public $cache = array();
	
	public $data = array();
	
	public $networks = array();
	
	public $settings = array();
	
	
	protected $cron = null;
	
	
	protected static $instance = null;

    
	public static function get_instance() {
		
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	
	public function __construct() {
		
		$this->networks = array(
			'buffer'      => 'Buffer',
			'facebook'    => 'Facebook',
			'linkedin'    => 'LinkedIn',
			'mix'         => 'Mix',
			'pinterest'   => 'Pinterest',
			'reddit'      => 'Reddit',
			'twitter'     => 'Twitter',
			'email'       => 'Email',
			'print'       => 'Print',
		);
		
		$this->settings = get_option( 'social_rocket_settings' );
		
		$this->background_processor = new Social_Rocket_Background_Process();
		
		$this->cron = new Social_Rocket_Cron();
		
		$this->load_available_networks();
		
		load_plugin_textdomain(
			'social-rocket',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
		
		// Ajax actions
		add_action( 'wp_ajax_social_rocket_get_inline_buttons', array( $this, 'ajax_get_inline_buttons' ) );
		add_action( 'wp_ajax_nopriv_social_rocket_get_inline_buttons', array( $this, 'ajax_get_inline_buttons' ) );
		add_action( 'wp_ajax_social_rocket_get_floating_buttons', array( $this, 'ajax_get_floating_buttons' ) );
		add_action( 'wp_ajax_nopriv_social_rocket_get_floating_buttons', array( $this, 'ajax_get_floating_buttons' ) );
		
		// assets
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		
		// automatically insert buttons
		add_filter( 'get_the_excerpt', array( $this, 'excerpt_before_get' ), 0 );
		add_filter( 'get_the_excerpt', array( $this, 'excerpt_after_get' ), 99 );
		add_action( 'template_redirect', array( $this, 'maybe_insert_inline_buttons' ), 99 );
		add_action( 'template_redirect', array( $this, 'maybe_insert_floating_buttons' ), 99 );
		
		// Gutenberg
		$this->register_blocks();
		
		// og tags
		add_action( 'wp_loaded', array( $this, 'maybe_disable_conflicting_og_tags' ) );
		add_action( 'wp_head', array( $this, 'maybe_add_og_tags' ), 1 );
	
	}
	
	
	// caution: passing by reference here creates the array key you're looking for if it doesn't exist --DG
	public static function _isset( &$var, $default = null ) {
		return isset( $var ) ? $var : $default;
	}
	
	
	public static function _return_false() {
		return false;
	}
	
	
	public static function ajax_get_floating_buttons() {
		social_rocket_floating( $_REQUEST );
		wp_die();
	}
	
	
	public static function ajax_get_inline_buttons() {
		social_rocket( $_REQUEST );
		wp_die();
	}
	
	
	
	/**
	 * Enqueues all necessary frontend Social Rocket JS code.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script(
			'social-rocket',
			plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/script.js',
			array( 'jquery' ),
			SOCIAL_ROCKET_VERSION,
			true
		);
		
		wp_localize_script(
			'social-rocket',
			'socialRocket',
			array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'where_we_at' => $this->where_we_at(),
			)
		);
		
	}
	
	
	/**
	 * Enqueues all necessary frontend Social Rocket CSS code.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style(
			'social_rocket',
			plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/style.css',
			array(),
			SOCIAL_ROCKET_VERSION,
			'all'
		);
		
		if ( ! $this->settings['disable_fontawesome'] ) {
			wp_enqueue_style(
				'fontawesome_all',
				plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/all.min.css',
				array(),
				SOCIAL_ROCKET_VERSION,
				'all'
			);
		}
		
		$css = '';
		
		// inline buttons
		$css .= $this->get_css_inline( $this->settings['inline_buttons'], $this->_isset( $this->data['inline_css_extra_selector'], null ) );
		
		// floating buttons
		$css .= $this->get_css_floating( $this->settings['floating_buttons'], $this->_isset( $this->data['floating_css_extra_selector'], null ) );
		
		// click to tweet
		$css .= $this->get_css_tweet();
		
		// desktop/mobile breakpoint (inline)
		$inline_mobile_breakpoint = $this->_isset( $this->settings['inline_mobile_breakpoint'], '' );
		if ( $inline_mobile_breakpoint > '' ) {
			$css .= "
				@media screen and (max-width: {$inline_mobile_breakpoint}px) {
					.social-rocket-inline-buttons.social-rocket-desktop-only {
						display: none !important;
					}
					.social-rocket-inline-buttons.social-rocket-mobile-only {
						display: block;
					}
				}
				@media screen and (min-width: " . ( $inline_mobile_breakpoint + 1 ) . "px) {
					.social-rocket-inline-buttons.social-rocket-mobile-only {
						display: none !important;
					}
					.social-rocket-inline-buttons.social-rocket-desktop-only {
						display: block;
					}
				}
			";
		}
		
		// desktop/mobile breakpoint (floating)
		$floating_mobile_breakpoint = $this->_isset( $this->settings['floating_mobile_breakpoint'], '' );
		if ( $floating_mobile_breakpoint > '' ) {
			$css .= "
				@media screen and (max-width: {$floating_mobile_breakpoint}px) {
					.social-rocket-floating-buttons.social-rocket-desktop-only {
						display: none !important;
					}
					.social-rocket-floating-buttons.social-rocket-mobile-only {
						display: block;
					}
				}
				@media screen and (min-width: " . ( $floating_mobile_breakpoint + 1 ) . "px) {
					.social-rocket-floating-buttons.social-rocket-mobile-only {
						display: none !important;
					}
					.social-rocket-floating-buttons.social-rocket-desktop-only {
						display: block;
					}
				}
			";
		}
		
		// filter
		$css = apply_filters( 'social_rocket_css', $css );
		
		// do this last so custom css has a chance to override anything previous
		$css .= $this->_isset( $this->settings['custom_css'], '' );
		
		wp_add_inline_style( 'social_rocket', $css );
		
	}
	
	
	/**
	 * Helps us prevent our share buttons from showing up in the_excerpt.
	 *
	 * Using the filter 'get_the_excerpt' both before and after, we set a flag for
	 * ourselves so we know later on whether or not to insert our buttons.
	 *
	 * @since 1.1.1
	 *
	 * @param string $excerpt The excerpt. Will not be modified.
	 *
	 * @return string The excerpt.
	 */
	public function excerpt_after_get( $excerpt ) {
		$this->data['doing_excerpt'] = false;
		return $excerpt;
	}
	
	
	/**
	 * Helps us prevent our share buttons from showing up in the_excerpt.
	 *
	 * Using the filter 'get_the_excerpt' both before and after, we set a flag for
	 * ourselves so we know later on whether or not to insert our buttons.
	 *
	 * @since 1.1.1
	 *
	 * @param string $excerpt The excerpt. Will not be modified.
	 *
	 * @return string The excerpt.
	 */
	public function excerpt_before_get( $excerpt ) {
		$this->data['doing_excerpt'] = true;
		return $excerpt;
	}
	
	
	/**
	 * Gets any data elements for button anchor, if needed (i.e. Pinterest).
	 *
	 * @since 1.0.0
	 *
	 * @param string $network  The network key to get anchor data for.
	 * @param string $scope    Optional. Default 'inline'. Accepts 'inline',
	 *                         'floating'.
	 *
	 * @return string The CSS classes.
	 */
	public function get_anchor_data( $network, $scope = 'inline' ) {
		
		$output = false;
		
		if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
			$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			$output = $this->_isset( $SRN->anchor_data, '' );
		}
		
		return apply_filters( 'social_rocket_get_anchor_data', $output, $network, $scope );
	}
	
	
	public static function get_archive_types() {
	
		$cpts = get_post_types(
			array(
				'public'      => true,
				'has_archive' => true,
				'_builtin'    => false,
			),
			'names'
		);
		
		$taxonomies = get_taxonomies(
			array(
				'public'      => true,
				'_builtin'    => false,
			),
			'names'
		);
		
		$types = array(
			'WP_home'     => array( 'type' => 'core', 'display_name' => __( 'Blog Index', 'social-rocket' ) ),
			'WP_author'   => array( 'type' => 'core', 'display_name' => __( 'Author', 'social-rocket' ) ),
			'WP_category' => array( 'type' => 'core', 'display_name' => __( 'Categories', 'social-rocket' ) ),
			'WP_date'     => array( 'type' => 'core', 'display_name' => __( 'Date-based Archives', 'social-rocket' ) ),
			'WP_tag'      => array( 'type' => 'core', 'display_name' => __( 'Tags', 'social-rocket' ) ),
		);
		
		foreach ( $cpts as $cpt ) {
			$types[ 'CPT_'.$cpt ] = array( 'type' => 'cpt', 'display_name' => ucfirst( $cpt ) );
		}
		
		foreach ( $taxonomies as $taxonomy ) {
			$types[ 'TAX_'.$taxonomy ] = array( 'type' => 'tax', 'display_name' => ucfirst( $taxonomy ) );
		}
		
		return apply_filters( 'social_rocket_archive_types', $types );
	}
	
	
	/**
	 * Gets CSS classes for wrapper div.
	 *
	 * @since 1.0.0
	 *
	 * @param string $network  The network key to get wrapper class(es) for.
	 * @param string $scope    Optional. Default 'inline'. Accepts 'inline',
	 *                         'floating'.
	 *
	 * @return string The CSS classes.
	 */
	public function get_button_wrapper_class( $network, $scope = 'inline' ) {
		
		$output = false;
		
		if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
			$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			$output = $this->_isset( $SRN->wrapper_class, '' );
		}
		
		return apply_filters( 'social_rocket_get_button_wrapper_class', $output, $network, $scope );
	}
	
	
	public function get_count_data( $id = 0, $type = 'post', $bypass_cache = false ) {
	
		global $wpdb;
		
		if ( ! $id ) {
			$loc  = Social_Rocket::where_we_at();
			$id = $loc['id'];
			$type = $loc['type'];
		}
		
		if ( $type === 'post' ) {
			$where = 'post_id';
		} elseif ( $type === 'term' ) {
			$where = 'term_id';
		} elseif ( $type === 'user' ) {
			$where = 'user_id';
		} elseif ( $type === 'url' ) {
			$where = 'url';
		}
		
		if ( ! $bypass_cache && isset( $this->cache[$where][$id] ) ) {
			return $this->cache[$where][$id];
		}
		
		$table_prefix = $wpdb->prefix;
		$data = $wpdb->get_row(
			$wpdb->prepare( 
				"SELECT * FROM {$table_prefix}social_rocket_count_data WHERE {$where} = %s",
				$id
			),
			ARRAY_A 
		);
		
		if ( ! is_array( $data ) || ! isset( $data['data'] ) ) {
			$return = array();
			$return['last_updated'] = 0;
		} else {
			$return = unserialize( $data['data'] );
			$return['last_updated'] = $data['last_updated'];
		}
		
		$this->cache[$where][$id] = $return;
		
		return $return;
		
	}
	
	
	/**
	 * Gets inline CSS code for Inline Buttons.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $settings        The settings group.
	 * @param string $extra_selector  Optional. An extra CSS selector to prepend each
	 *                                CSS code block.
	 *
	 * @return string The generated CSS code.
	 */
	public function get_css_inline( $settings, $extra_selector = '' ) {
		
		$css = '';
		
		// color stuff
		$color_scheme              = $this->_isset( $settings['button_color_scheme'], '' );
		$custom_icon               = $this->_isset( $settings['button_color_scheme_custom_icon'], '' );
		$custom_icon_color         = $this->_isset( $settings['button_color_scheme_custom_icon_color'], '' );
		$custom_background         = $this->_isset( $settings['button_color_scheme_custom_background'], '' );
		$custom_background_color   = $this->_isset( $settings['button_color_scheme_custom_background_color'], '' );
		$custom_border             = $this->_isset( $settings['button_color_scheme_custom_border'], '' );
		$custom_border_color       = $this->_isset( $settings['button_color_scheme_custom_border_color'], '' );
		$custom_hover              = $this->_isset( $settings['button_color_scheme_custom_hover'], '' );
		$custom_hover_color        = $this->_isset( $settings['button_color_scheme_custom_hover_color'], '' );
		$custom_hover_bg           = $this->_isset( $settings['button_color_scheme_custom_hover_bg'], '' );
		$custom_hover_bg_color     = $this->_isset( $settings['button_color_scheme_custom_hover_bg_color'], '' );
		$custom_hover_border       = $this->_isset( $settings['button_color_scheme_custom_hover_border'], '' );
		$custom_hover_border_color = $this->_isset( $settings['button_color_scheme_custom_hover_border_color'], '' );
		
		foreach ( $this->networks as $network => $network_name ) {
		
			if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
				$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			} else {
				continue;
			}
			
			$network_settings = $this->_isset( $settings['networks'][$network]['settings'], array() );
		
			$final_icon_color           = false;
			$final_background_color     = false;
			$final_border_color         = false;
			$final_hover_color          = false;
			$final_hover_bg_color       = false;
			$final_hover_border_color   = false;
			$network_color_override     = $this->_isset( $network_settings['color_override'] );
			$network_color_value        = $network_color_override && $this->_isset( $network_settings['color'] ) > '' ?
			                                  $network_settings['color'] : $this->_isset( $SRN->color, '#ffffff' );
			$network_color_bg_value     = $network_color_override && $this->_isset( $network_settings['color_bg'] ) > '' ?
			                                  $network_settings['color_bg'] : $this->_isset( $SRN->color_bg, '#787878' );
			$network_color_border_value = $network_color_override && $this->_isset( $network_settings['color_border'] ) > '' ?
			                                  $network_settings['color_border'] : $this->_isset( $SRN->color_border, '#666666' );
			$network_color_hover_value  = $network_color_override && $this->_isset( $network_settings['color_hover'] ) > '' ?
			                                  $network_settings['color_hover'] : $this->_isset( $SRN->color_hover, '#ffffff' );
			$network_color_hover_bg_value = $network_color_override && $this->_isset( $network_settings['color_bg_hover'] ) > '' ?
			                                  $network_settings['color_bg_hover'] : $this->_isset( $SRN->color_bg_hover, '#666666' );
			$network_color_hover_border_value = $network_color_override && $this->_isset( $network_settings['color_border_hover'] ) > '' ?
			                                  $network_settings['color_border_hover'] : $this->_isset( $SRN->color_border_hover, '#666666' );
			
			// icon color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_icon_color = $network_color_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_icon === 'custom' && $custom_icon_color > '' ) {
					$final_icon_color = $custom_icon_color;
				} else if ( $custom_icon === 'network_icon' ) {
					$final_icon_color = $network_color_value;
				} else if ( $custom_icon === 'network_background' ) {
					$final_icon_color = $network_color_bg_value;
				} else if ( $custom_icon === 'network_border' ) {
					$final_icon_color = $network_color_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_icon_color = $network_color_bg_value;
			}
			if ( $final_icon_color ) {
				$css .= "
					$extra_selector .social-rocket-{$network} .social-rocket-button-icon,
					$extra_selector .social-rocket-{$network} .social-rocket-button-cta,
					$extra_selector .social-rocket-{$network} .social-rocket-button-count {
						color: {$final_icon_color};
					}
				";
			}
			
			// background color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_background_color = $network_color_bg_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_background === 'custom' ) {
					$final_background_color = $custom_background_color;
				} else if ( $custom_background === 'none' ) {
					$final_background_color = 'transparent';
				} else if ( $custom_background === 'network_icon' ) {
					$final_background_color = $network_color_value;
				} else if ( $custom_background === 'network_background' ) {
					$final_background_color = $network_color_bg_value;
				} else if ( $custom_background === 'network_border' ) {
					$final_background_color = $network_color_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_background_color = 'transparent';
			}
			if ( $final_background_color ) {
				$css .= "
					$extra_selector .social-rocket-button.social-rocket-{$network} {
						background-color: {$final_background_color};
					}
				";
			}
			
			// border color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_border_color = $network_color_border_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_border === 'custom' ) {
					$final_border_color = $custom_border_color;
				} else if ( $custom_border === 'none' ) {
					$final_border_color = 'transparent';
				} else if ( $custom_border === 'network_icon' ) {
					$final_border_color = $network_color_value;
				} else if ( $custom_border === 'network_background' ) {
					$final_border_color = $network_color_bg_value;
				} else if ( $custom_border === 'network_border' ) {
					$final_border_color = $network_color_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_border_color = 'transparent';
			}
			if ( $final_border_color ) {
				$css .= "
					$extra_selector .social-rocket-button.social-rocket-{$network} {
						border-color: {$final_border_color};
					}
				";
			}
			
			// icon hover color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_hover_color = $network_color_hover_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_hover === 'custom' ) {
					$final_hover_color = $custom_hover_color;
				} else if ( $custom_hover === 'none' ) {
					$final_hover_color = false;
				} else if ( $custom_hover === 'network_hover' ) {
					$final_hover_color = $network_color_hover_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_hover_color = false;
				$inverted_hover_color = $network_color_hover_bg_value;
				$css .= "
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-icon,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-cta,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-count {
						color: {$inverted_hover_color};
					}
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-icon svg,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-icon svg g,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-icon svg path {
						fill: {$inverted_hover_color};
					}
				";
			}
			if ( $final_hover_color ) {
				$css .= "
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-icon,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-cta,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-count {
						color: {$final_hover_color};
					}
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-icon svg,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-icon svg g,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-button-icon svg path {
						fill: {$final_hover_color};
					}
				";
			}
			
			// background hover color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_hover_bg_color = $network_color_hover_bg_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_hover_bg === 'custom' ) {
					$final_hover_bg_color = $custom_hover_bg_color;
				} else if ( $custom_hover_bg === 'none' ) {
					$final_hover_bg_color = false;
				} else if ( $custom_hover_bg === 'network_hover_bg' ) {
					$final_hover_bg_color = $network_color_hover_bg_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_hover_bg_color = false;
			}
			if ( $final_hover_bg_color ) {
				$css .= "
					$extra_selector .social-rocket-button.social-rocket-{$network}:hover {
						background-color: {$final_hover_bg_color};
					}
				";
			}
			
			// border hover color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_hover_border_color = $network_color_hover_border_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_hover_border === 'custom' ) {
					$final_hover_border_color = $custom_hover_border_color;
				} else if ( $custom_hover_border === 'none' ) {
					$final_hover_border_color = false;
				} else if ( $custom_hover_border === 'network_hover_border' ) {
					$final_hover_border_color = $network_color_hover_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_hover_border_color = false;
			}
			if ( $final_hover_border_color ) {
				$css .= "
					$extra_selector .social-rocket-button.social-rocket-{$network}:hover {
						border-color: {$final_hover_border_color};
					}
				";
			}
		
		}
		
		// button alignment
		if ( $settings['button_alignment'] === 'stretch' ) {
			$css .= "
				$extra_selector .social-rocket-buttons {
					display: -webkit-box;
					display: -moz-box;
					display: -ms-flexbox;
					display: -webkit-flex;
					display: flex;
					-webkit-box-orient: horizontal;
					-webkit-box-direction: normal;
					-ms-flex-direction: row;
					flex-direction: row;
					-ms-flex-wrap: wrap;
					flex-wrap: wrap;
					-webkit-box-pack: justify;
					-ms-flex-pack: justify;
					justify-content: space-between;
					-ms-flex-line-pack: stretch;
					align-content: stretch;
					-webkit-box-align: stretch;
					-ms-flex-align: stretch;
					align-items: stretch;
				}
				$extra_selector .social-rocket-buttons .social-rocket-button,
				$extra_selector .social-rocket-buttons .social-rocket-button-anchor,
				$extra_selector .social-rocket-buttons .social-rocket-shares-total {
					-webkit-box-flex: 1;
					-ms-flex: 1;
					flex: 1;
				}
			";
		} else {
			$css .= "
				$extra_selector .social-rocket-buttons {
					display: block;
				}
				$extra_selector .social-rocket-buttons .social-rocket-button,
				$extra_selector .social-rocket-buttons .social-rocket-button-anchor,
				$extra_selector .social-rocket-buttons .social-rocket-shares-total {
					-webkit-box-flex: initial;
					-ms-flex: initial;
					flex: initial;
				}
			";
		}
		
		// button size
		$button_size  = $this->_isset( $settings['button_size'] );
		$button_style = $this->_isset( $settings['button_style'] );
		if ( $button_size > '' && $button_size != 100 ) {
			$default_button_height    = 40;
			$default_button_width     = 40;
			$default_line_height      = in_array( $button_style, array( 'round', 'square' ) ) ? 40 : 30;
			$default_icon_size        = 16;
			$default_cta_size         = 13;
			$default_total_count_size = 18;
			$default_total_height     = 14;
			$default_total_label_size = 9;
			if ( in_array( $button_style, array( 'round', 'square' ) ) ) {
				$css .= "
				$extra_selector .social-rocket-buttons .social-rocket-button {
					width: " . ( $default_button_width * ( $button_size / 100 ) ) . "px;
					height: " . ( $default_button_height * ( $button_size / 100 ) ) . "px;
				}
				";
			}
			$css .= "
				$extra_selector .social-rocket-buttons .social-rocket-button {
					max-height: " . ( $default_line_height * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-button-anchor,
				$extra_selector .social-rocket-buttons .social-rocket-button-anchor:focus,
				$extra_selector .social-rocket-buttons .social-rocket-button-anchor:hover {
					line-height: " . ( $default_line_height * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-button-icon {
					font-size: " . ( $default_icon_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-button-icon svg {
					width: auto;
					height: " . ( $default_icon_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-button-cta,
				$extra_selector .social-rocket-buttons .social-rocket-button-count {
					font-size: " . ( $default_cta_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-shares-total {
					line-height: " . ( $default_line_height * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-shares-total i {
					font-size: " . ( $default_icon_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-shares-total-count {
					font-size: " . ( $default_total_count_size * ( $button_size / 100 ) ) . "px;
					line-height: " . ( $default_total_height * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-shares-total-label {
					font-size: " . ( $default_total_label_size * ( $button_size / 100 ) ) . "px;
					line-height: " . ( $default_total_height * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector .social-rocket-buttons .social-rocket-shares-total .social-rocket-shares-total-inner {
					margin-top: " . ( ( $default_line_height * ( $button_size / 100 ) ) * 0.16 ) . "px;
				}
			";
		}
		
		// everything else
		$css .= "
			$extra_selector .social-rocket-buttons .social-rocket-shares-total {
				color: " . $this->_isset( $settings['total_color'], 'initial' ) . ";
			}
			$extra_selector .social-rocket-buttons {
				text-align: " . ( in_array( $settings['button_alignment'], array( 'left', 'center', 'right' ) ) ?
					"{$settings['button_alignment']}" : 'left' ) . ";
			}" .
			( in_array( $settings['heading_alignment'], array( 'left', 'center', 'right' ) ) ? "
			$extra_selector .social-rocket-buttons-heading {
				text-align: {$settings['heading_alignment']};
			}" : '' ) . "
			$extra_selector .social-rocket-button {
				border-style: {$settings['border']};
				border-width: {$settings['border_size']}px;
				" . ( in_array( $settings['button_style'], array( 'rectangle', 'square' ) ) ?
					"border-radius: {$settings['border_radius']}px;" : '' ) . "
			}
			$extra_selector .social-rocket-buttons .social-rocket-button,
			$extra_selector .social-rocket-buttons .social-rocket-button:last-child,
			$extra_selector .social-rocket-buttons .social-rocket-shares-total {
				margin-bottom: {$settings['margin_bottom']}px;
				margin-right: {$settings['margin_right']}px;
			}
		";
		
		return apply_filters( 'social_rocket_css_inline', $css );
	}
	
	
	/**
	 * Gets inline CSS code for Floating Buttons.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $settings        The settings group.
	 * @param string $extra_selector  Optional. An extra CSS selector to prepend each
	 *                                CSS code block.
	 *
	 * @return string The generated CSS code.
	 */
	public function get_css_floating( $settings, $extra_selector = '' ) {
		
		$css = '';
		
		// color stuff
		$color_scheme              = $this->_isset( $settings['button_color_scheme'], '' );
		$custom_icon               = $this->_isset( $settings['button_color_scheme_custom_icon'], '' );
		$custom_icon_color         = $this->_isset( $settings['button_color_scheme_custom_icon_color'], '' );
		$custom_background         = $this->_isset( $settings['button_color_scheme_custom_background'], '' );
		$custom_background_color   = $this->_isset( $settings['button_color_scheme_custom_background_color'], '' );
		$custom_border             = $this->_isset( $settings['button_color_scheme_custom_border'], '' );
		$custom_border_color       = $this->_isset( $settings['button_color_scheme_custom_border_color'], '' );
		$custom_hover              = $this->_isset( $settings['button_color_scheme_custom_hover'], '' );
		$custom_hover_color        = $this->_isset( $settings['button_color_scheme_custom_hover_color'], '' );
		$custom_hover_bg           = $this->_isset( $settings['button_color_scheme_custom_hover_bg'], '' );
		$custom_hover_bg_color     = $this->_isset( $settings['button_color_scheme_custom_hover_bg_color'], '' );
		$custom_hover_border       = $this->_isset( $settings['button_color_scheme_custom_hover_border'], '' );
		$custom_hover_border_color = $this->_isset( $settings['button_color_scheme_custom_hover_border_color'], '' );
		
		
		foreach ( $this->networks as $network => $network_name ) {
		
			if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
				$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			} else {
				continue;
			}
			
			$network_settings = $this->_isset( $settings['networks'][$network]['settings'], array() );
		
			$final_icon_color           = false;
			$final_background_color     = false;
			$final_border_color         = false;
			$final_hover_color          = false;
			$final_hover_bg_color       = false;
			$final_hover_border_color   = false;
			$network_color_override     = $this->_isset( $network_settings['color_override'] );
			$network_color_value        = $network_color_override && $this->_isset( $network_settings['color'] ) > '' ?
			                                  $network_settings['color'] : $this->_isset( $SRN->color, '#ffffff' );
			$network_color_bg_value     = $network_color_override && $this->_isset( $network_settings['color_bg'] ) > '' ?
			                                  $network_settings['color_bg'] : $this->_isset( $SRN->color_bg, '#787878' );
			$network_color_border_value = $network_color_override && $this->_isset( $network_settings['color_border'] ) > '' ?
			                                  $network_settings['color_border'] : $this->_isset( $SRN->color_border, '#666666' );
			$network_color_hover_value  = $network_color_override && $this->_isset( $network_settings['color_hover'] ) > '' ?
			                                  $network_settings['color_hover'] : $this->_isset( $SRN->color_hover, '#ffffff' );
			$network_color_hover_bg_value = $network_color_override && $this->_isset( $network_settings['color_bg_hover'] ) > '' ?
			                                  $network_settings['color_bg_hover'] : $this->_isset( $SRN->color_bg_hover, '#666666' );
			$network_color_hover_border_value = $network_color_override && $this->_isset( $network_settings['color_border_hover'] ) > '' ?
			                                  $network_settings['color_border_hover'] : $this->_isset( $SRN->color_border_hover, '#666666' );
			
			// icon color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_icon_color = $network_color_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_icon === 'custom' && $custom_icon_color > '' ) {
					$final_icon_color = $custom_icon_color;
				} else if ( $custom_icon === 'network_icon' ) {
					$final_icon_color = $network_color_value;
				} else if ( $custom_icon === 'network_background' ) {
					$final_icon_color = $network_color_bg_value;
				} else if ( $custom_icon === 'network_border' ) {
					$final_icon_color = $network_color_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_icon_color = $network_color_bg_value;
			}
			if ( $final_icon_color ) {
				$css .= "
					$extra_selector .social-rocket-floating-button.social-rocket-{$network} .social-rocket-floating-button-icon,
					$extra_selector .social-rocket-floating-button.social-rocket-{$network} .social-rocket-floating-button-cta,
					$extra_selector .social-rocket-floating-button.social-rocket-{$network} .social-rocket-floating-button-count {
						color: {$final_icon_color};
					}
				";
			}
			
			// background color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_background_color = $network_color_bg_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_background === 'custom' ) {
					$final_background_color = $custom_background_color;
				} else if ( $custom_background === 'none' ) {
					$final_background_color = 'transparent';
				} else if ( $custom_background === 'network_icon' ) {
					$final_background_color = $network_color_value;
				} else if ( $custom_background === 'network_background' ) {
					$final_background_color = $network_color_bg_value;
				} else if ( $custom_background === 'network_border' ) {
					$final_background_color = $network_color_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_background_color = 'transparent';
			}
			if ( $final_background_color ) {
				$css .= "
					$extra_selector .social-rocket-floating-button.social-rocket-{$network} {
						background-color: {$final_background_color};
					}
				";
			}
			
			// border color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_border_color = $network_color_border_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_border === 'custom' ) {
					$final_border_color = $custom_border_color;
				} else if ( $custom_border === 'none' ) {
					$final_border_color = 'transparent';
				} else if ( $custom_border === 'network_icon' ) {
					$final_border_color = $network_color_value;
				} else if ( $custom_border === 'network_background' ) {
					$final_border_color = $network_color_bg_value;
				} else if ( $custom_border === 'network_border' ) {
					$final_border_color = $network_color_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_border_color = 'transparent';
			}
			if ( $final_border_color ) {
				$css .= "
					$extra_selector .social-rocket-floating-button.social-rocket-{$network} {
						border-color: {$final_border_color};
					}
				";
			}
			
			// icon hover color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_hover_color = $network_color_hover_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_hover === 'custom' ) {
					$final_hover_color = $custom_hover_color;
				} else if ( $custom_hover === 'none' ) {
					$final_hover_color = false;
				} else if ( $custom_hover === 'network_hover' ) {
					$final_hover_color = $network_color_hover_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_hover_color = false;
				$inverted_hover_color = $network_color_hover_bg_value;
				$css .= "
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-icon,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-cta,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-count {
						color: {$inverted_hover_color};
					}
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-icon svg,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-icon svg g,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-icon svg path {
						fill: {$inverted_hover_color};
					}
				";
			}
			if ( $final_hover_color ) {
				$css .= "
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-icon,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-cta,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-count {
						color: {$final_hover_color};
					}
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-icon svg,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-icon svg g,
					$extra_selector .social-rocket-{$network}:hover .social-rocket-floating-button-icon svg path {
						fill: {$final_hover_color};
					}
				";
			}
			
			// background hover color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_hover_bg_color = $network_color_hover_bg_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_hover_bg === 'custom' ) {
					$final_hover_bg_color = $custom_hover_bg_color;
				} else if ( $custom_hover_bg === 'none' ) {
					$final_hover_bg_color = false;
				} else if ( $custom_hover_bg === 'network_hover_bg' ) {
					$final_hover_bg_color = $network_color_hover_bg_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_hover_bg_color = false;
			}
			if ( $final_hover_bg_color ) {
				$css .= "
					$extra_selector .social-rocket-floating-button.social-rocket-{$network}:hover {
						background-color: {$final_hover_bg_color};
					}
				";
			}
			
			// border hover color
			if ( $network_color_override ) { // network-specific setting always wins
				$final_hover_border_color = $network_color_hover_border_value;
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_hover_border === 'custom' ) {
					$final_hover_border_color = $custom_hover_border_color;
				} else if ( $custom_hover_border === 'none' ) {
					$final_hover_border_color = false;
				} else if ( $custom_hover_border === 'network_hover_border' ) {
					$final_hover_border_color = $network_color_hover_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$final_hover_border_color = false;
			}
			if ( $final_hover_border_color ) {
				$css .= "
					$extra_selector .social-rocket-floating-button.social-rocket-{$network}:hover {
						border-color: {$final_hover_border_color};
					}
				";
			}
		
		}
		
		// button alignment
		if ( $settings['button_alignment'] === 'stretch' && in_array( $settings['default_position'], array( 'top', 'bottom' ) ) ) {
			$css .= "
				$extra_selector.social-rocket-floating-buttons {
					display: -webkit-box;
					display: -moz-box;
					display: -ms-flexbox;
					display: -webkit-flex;
					display: flex;
					-webkit-box-orient: horizontal;
					-webkit-box-direction: normal;
					-ms-flex-direction: row;
					flex-direction: row;
					-ms-flex-wrap: wrap;
					flex-wrap: wrap;
					-webkit-box-pack: justify;
					-ms-flex-pack: justify;
					justify-content: space-between;
					-ms-flex-line-pack: stretch;
					align-content: stretch;
					-webkit-box-align: stretch;
					-ms-flex-align: stretch;
					align-items: stretch;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button,
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-anchor {
					-webkit-box-flex: 1;
					-ms-flex: 1;
					flex: 1;
				}
			";
			if ( strpos( $extra_selector, 'mobile-only' ) !== false ) {
				$css .= "
					$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total {
						-webkit-box-flex: 1.4;
						-ms-flex: 1.4;
						flex: 1.4;
					}
				";
			} else {
				$css .= "
					$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total {
						-webkit-box-flex: 1;
						-ms-flex: 1;
						flex: 1;
					}
				";
			}
		}
		
		
		// button size
		$button_size  = $this->_isset( $settings['button_size'] );
		$button_style = $this->_isset( $settings['button_style'] );
		if ( $button_size > '' && $button_size != 100 ) {
			$default_button_height     = 50;
			$default_button_width      = 50;
			$default_line_height       = in_array( $button_style, array( 'round', 'square' ) ) ? 50 : 35;
			$default_line_height_hc    = 35;
			$default_icon_size         = 16;
			$default_cta_size          = 13;
			$default_count_size        = in_array( $button_style, array( 'round', 'square' ) ) ? 11 : 13;
			$default_total_count_size  = in_array( $button_style, array( 'round', 'square' ) ) ? 12 : 18;
			$default_total_height      = 14;
			$default_total_label_size  = 9;
			$default_total_line_height = 30;
			if ( in_array( $button_style, array( 'round', 'square' ) ) ) {
				$css .= "
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button {
					width: " . ( $default_button_width * ( $button_size / 100 ) ) . "px;
					height: " . ( $default_button_height * ( $button_size / 100 ) ) . "px;
				}
				
				$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total-$button_style .social-rocket-shares-total-count {
					font-size: " . ( $default_total_count_size * ( $button_size / 100 ) ) . "px;
					line-height: " . ( $default_total_height * ( $button_size / 100 ) ) . "px;
				}
				";
			}
			$css .= "
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-$button_style .social-rocket-floating-button-anchor,
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-$button_style .social-rocket-floating-button-anchor:focus,
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-$button_style .social-rocket-floating-button-anchor:hover {
					line-height: " . ( $default_line_height * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button .social-rocket-floating-button-anchor.social-rocket-has-count {
					line-height: " . ( $default_line_height_hc * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-$button_style .social-rocket-floating-button-icon {
					font-size: " . ( $default_icon_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-$button_style .social-rocket-floating-button-icon svg {
					width: auto;
					height: " . ( $default_icon_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-$button_style .social-rocket-floating-button-cta {
					font-size: " . ( $default_cta_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-$button_style .social-rocket-floating-button-count {
					font-size: " . ( $default_count_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total {
					line-height: " . ( $default_total_line_height * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total i {
					font-size: " . ( $default_icon_size * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total-count {
					font-size: " . ( $default_total_count_size * ( $button_size / 100 ) ) . "px;
					line-height: " . ( $default_total_height * ( $button_size / 100 ) ) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total-label {
					font-size: " . ( $default_total_label_size * ( $button_size / 100 ) ) . "px;
					line-height: " . ( $default_total_height * ( $button_size / 100 ) ) . "px;
				}
			";
			if ( in_array( $button_style, array( 'oval', 'rectangle' ) ) ) {
				$css .= "
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-oval .social-rocket-floating-button-count,
				$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button-rectangle .social-rocket-floating-button-count {
					margin-top: " . (
						( ( $default_line_height * ( $button_size / 100 ) ) - 28 ) / 2
					) . "px;
				}
				$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total-rectangle .social-rocket-shares-total-inner {
					margin-top: " . ( ( $default_line_height * ( $button_size / 100 ) ) * 0.16 ) . "px;
				}
				";
			}
		}
		
		// everything else
		$css .= "
			$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total {
				color: " . $this->_isset( $settings['total_color'], 'initial' ) . ";
			}
		";
		$css .= "
			$extra_selector.social-rocket-floating-buttons.social-rocket-position-top,
			$extra_selector.social-rocket-floating-buttons.social-rocket-position-bottom {
				text-align: " . ( in_array( $settings['button_alignment'], array( 'left', 'center', 'right' ) ) ?
					"{$settings['button_alignment']}" : 'center' ) . ";
			}
		";
		if ( ! $settings['show_counts'] ) {
			$css .= "
			$extra_selector.social-rocket-floating-buttons.social-rocket-position-top .social-rocket-floating-button-anchor,
			$extra_selector.social-rocket-floating-buttons.social-rocket-position-bottom .social-rocket-floating-button-anchor {
				text-align: center;
			}
			";
		}
		$css .= "
			$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button {
				border-style: {$settings['border']};
				border-width: {$settings['border_size']}px;
				" . ( in_array( $settings['button_style'], array( 'rectangle', 'square' ) ) ?
					"border-radius: {$settings['border_radius']}px;" : '' ) . "
			}
		";
		if ( in_array( $settings['default_position'], array( 'top', 'bottom' ) ) ) {
			$css .= "
			$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button,
			$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total {
				margin-right: {$settings['margin_right']}px;
			}
			";
		} else {
			$css .= "
			$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button,
			$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total {
				margin-bottom: {$settings['margin_bottom']}px;
			}
			";
		}
		if ( $settings['button_style'] === 'rectangle' && $settings['border'] === 'none' ) {
			$css .= "
			$extra_selector.social-rocket-floating-buttons .social-rocket-floating-button.social-rocket-floating-button-rectangle {
				padding: 1px;
			}
			";
		}
		if ( $settings['border'] !== 'none' && $settings['border_size'] > 0 ) {
			$css .= "
			$extra_selector.social-rocket-floating-buttons .social-rocket-shares-total {
				padding: {$settings['border_size']}px;
			}
			";
		}
		
		return apply_filters( 'social_rocket_css_floating', $css );
	}
	
	
	/**
	 * Gets inline CSS code for Click To Tweet.
	 *
	 * @since 1.0.0
	 *
	 * @return string The generated CSS code.
	 */
	public function get_css_tweet() {
	
		$settings = $this->settings['tweet_settings'];
		
		// default style first:
		$css = "
			.social-rocket-tweet {
				background-color: {$settings['saved_settings']['default']['background_color']};
				color: {$settings['saved_settings']['default']['text_color']};
				font-size: {$settings['saved_settings']['default']['text_size']}px;
				border-style: {$settings['saved_settings']['default']['border']};
				border-width: {$settings['saved_settings']['default']['border_size']}px;
				border-color: {$settings['saved_settings']['default']['border_color']};
				border-radius: {$settings['saved_settings']['default']['border_radius']}px;
			}
			.social-rocket-tweet a {
				" . ( $this->settings['tweet_settings']['saved_settings']['default']['accent_color'] > '' ? "border-left: 10px solid {$settings['saved_settings']['default']['accent_color']};" : '' ) . "
			}
			.social-rocket-tweet-cta {
				color: {$settings['saved_settings']['default']['cta_color']};
				text-align: {$settings['saved_settings']['default']['cta_position']};
			}
		";
		
		// now any other saved styles:
		foreach ( $settings['saved_settings'] as $key => $saved_setting ) {
			if ( $key === 'default' ) {
				continue;
			}
			$css .= "
				.social-rocket-tweet-style-{$key} {
					background-color: {$saved_setting['background_color']};
					color: {$saved_setting['text_color']};
					font-size: {$saved_setting['text_size']}px;
					border-style: {$saved_setting['border']};
					border-width: {$saved_setting['border_size']}px;
					border-color: {$saved_setting['border_color']};
					border-radius: {$saved_setting['border_radius']}px;
				}
				.social-rocket-tweet-style-{$key} a {
					" . ( $saved_setting['accent_color'] > '' ? "border-left: 10px solid {$saved_setting['accent_color']};" : '' ) . "
				}
				.social-rocket-tweet-style-{$key} .social-rocket-tweet-cta {
					color: {$saved_setting['cta_color']};
					text-align: {$saved_setting['cta_position']};
				}
			";
		}
		
		return apply_filters( 'social_rocket_css_tweet', $css );
	}
	
	
	/**
	 * Gets Call To Action for a given network and settings group.
	 *
	 * @since 1.0.0
	 *
	 * @param string $network  The network key.
	 * @param array  $settings The settings group.
	 *
	 * @return string The Call To Action text.
	 */
	public function get_cta( $network, $settings ) {
		
		$output = false;
		
		// 1) get network default
		if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
			$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			$output = $SRN->cta;
		}
		
		// 2) get network setting override, if exists
		if ( $this->_isset( $settings['networks'][$network]['settings']['cta'] ) ) {
			$output = $settings['networks'][$network]['settings']['cta'];
		}
		
		return apply_filters( 'social_rocket_get_cta', $output, $network, $settings );
	}
	
	
	/**
	 * Generates Floating Buttons HTML code.
	 *
	 * This function may be called on page load by
	 * Social_Rocket::maybe_insert_floating_buttons(), via the shortcode
	 * [socialrocket-floating], or via the global function socal_rocket_floating().
	 *
	 * @version 1.3.3
	 * @since   1.0.0
	 *
	 * @param array $args {
	 *     Optional. An array of arguments.
	 *
	 *     @type int|string $id      The post ID, term ID, user ID, or (string) URL
	 *                               for which the buttons are being generated.
	 *                               Default current page ID.
	 *
	 *     @type string $type        What type of $id was supplied. Default 'post'.
	 *                               Accepts 'post', 'page', 'term', 'category',
	 *                               'tag', 'taxonomy', 'user', 'url'.
	 *
	 *     @type string $add_class   Add CSS class(es) to wrapper div. Default ''.
	 *
	 *     @type string $networks    Comma-separated list of buttons to be shown.
	 *                               Default uses global settings.
	 *
	 *     @type string $placement   Where to position floating bar. Default 'left'.
	 *                               Accepts 'left', 'right', 'top', 'bottom',
	 *                               'none'.
	 * }
	 *
	 * @return string The Floating Buttons HTML code.
	 */
	public function get_floating_buttons_html( $args = array() ) {
		
		// get settings
		$settings = apply_filters( 'social_rocket_floating_buttons_get_settings', $this->settings['floating_buttons'], $args );
		
		// if no active networks, stop here
		if ( ! count( $settings['networks'] ) > 0 ) {
			return '';
		}
		
		// get current location
		$loc = $this->where_we_at();
		
		// parse args and/or set defaults
		// 1) id/type/url
		if ( isset( $args['id'] ) ) {
			if ( isset( $args['type'] ) ) {
				$args['type'] = strtolower( $args['type'] );
				if ( in_array( $args['type'], array( 'post', 'page' ) ) ) {
					$type = 'post';
				} elseif ( in_array( $args['type'], array( 'category', 'tag', 'taxonomy', 'term' ) ) ) {
					$type = 'term';
				} elseif ( $args['type'] === 'user' ) {
					$type = 'user';
				} elseif ( $args['type'] === 'url' ) {
					$type = 'url';
				}
			} else {
				$type = 'post';
			}
			switch ( $type ) {
				case 'post':
					$id = intval( $args['id'] );
					$url = get_permalink( $id );
					break;
				case 'term':
					$id = intval( $args['id'] );
					$url = get_term_link( $id );
					break;
				case 'user':
					$id = intval( $args['id'] );
					$url = get_author_posts_url( $id );
					break;
				case 'url':
					$id = $args['id'];
					$url = $args['id'];
			}
		} else {
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		// get networks (now that we have correct $id and $type)
		$networks = apply_filters( 'social_rocket_floating_networks', $settings['networks'], $id, $type );
		
		// continue parsing args and/or setting defaults
		// 2) networks override
		if ( isset( $args['networks'] ) ) {
			$networks = array();
			if ( is_array( $args['networks'] ) ) {
				$network_keys = $args['networks'];
			} else {
				$network_keys = explode( ',', $args['networks'] );
			}
			foreach ( $network_keys as $network_key ) {
				if ( isset( $this->networks[ $network_key ] ) ) {
					$networks[ $network_key ] = $this->networks[ $network_key ];
				}
			}
			if ( empty( $networks ) ) {
				$networks = $settings['networks'];
			}
		}
		
		// 2b) make sure all requested networks are available
		$available_networks = $this->networks;
		foreach ( $networks as $key => $value ) {
			if ( ! isset( $available_networks[ $key ] ) ) {
				unset( $networks[$key] );
			}
		}
		
		// 3) placement
		if ( isset( $args['placement'] ) ) {
			$placement = $args['placement'];
		} else {
			if ( isset( $loc['settings_key'] ) && $this->_isset( $settings['position_'.$loc['settings_key']], 'default' ) !== 'default' ) {
				$placement = $settings['position_'.$loc['settings_key']];
			} else {
				$placement = $settings['default_position'];
			}
		}		
		if ( ! $placement || $placement === 'none' ) {
			return '';
		}
		
		// 4) show CTA
		$show_cta = false;
		if (
			( $settings['button_show_cta'] && in_array( $settings['button_style'], array( 'rectangle', 'oval' ) ) ) ||
			is_admin() // for admin previews
		) {
			$show_cta = true;
		}
		
		// 5) show share counts, show total
		$show_counts = $settings['show_counts'] ||
						is_admin() // for admin previews
						? true : false;
		$show_total  = $settings['show_total'] ||
						is_admin() // for admin previews
						? $settings['total_position'] : false;
		
		$total_show_icon = $this->_isset( $settings['total_show_icon'] ) ||
						    is_admin() // for admin previews
						    ? true : false;
		
		// maybe get share counts
		if ( $show_counts || $show_total ) {
			if ( is_admin() ) { // dummy counts for admin previews
				$counts = array(
					'facebook'  => array( 'total' => '111' ),
					'twitter'   => array( 'total' => '11' ),
					'pinterest' => array( 'total' => '1' ),
				);
			} else {
				$counts = $this->get_share_counts( $id, $type, $url );
			}
			if ( isset( $counts['networks'] ) ) {
				$counts = $counts['networks'];
			}
		}
		
		// 6) wrapper classes, styles
		$classes = 'social-rocket-floating-buttons social-rocket-position-' . $placement;
		$styles  = '';
		
		// 7) left/right button bar settings
		if ( in_array( $placement, array( 'left', 'right' ) ) ) {
			if ( $settings['vertical_offset'] ) {
				$vertical_offset = $settings['vertical_offset'];
				if ( stripos( $vertical_offset, '%' ) !== false ) {
					$vertical_offset_unit = 'vh';
				} else {
					$vertical_offset_unit = 'px';
				}
				$vertical_offset = floatval( $vertical_offset );
				if ( $vertical_offset <> 0 ) {
					$styles .= 'margin-' . ( $settings['vertical_position'] === 'bottom' ? 'bottom' : 'top' ) . ': '.$vertical_offset.$vertical_offset_unit.';';
				}
			}
			if ( $settings['vertical_position'] ) {
				$classes .= ' social-rocket-vertical-position-' . $settings['vertical_position'];
			}
		}
		
		// 8) top/bottom button bar settings
		if ( in_array( $placement, array( 'top', 'bottom' ) ) ) {
			if ( $settings['background_color'] ) {
				$styles .= 'background-color: ' . $settings['background_color'] . ';';
			}
			if ( $settings['padding'] ) {
				$styles .= 'padding: ' . $settings['padding'] . ';';
			}
		}
		
		// start output
		$output      = '';
		$more_output = '';
		$total_output = '';
		
		// total shares
		if ( $show_total ) {
			$total = 0;
			foreach ( $settings['networks'] as $key => $value ) {
				if ( isset( $counts[$key] ) ) {
					$total = $total + $counts[$key]['total'];
				}
			}
			if (
				$total >= $settings['show_total_min'] ||
				is_admin() // for admin previews
			) {
				$total_output = '<div class="social-rocket-shares-total'
					. ' social-rocket-shares-total-' . $settings['button_style']
					. ( $total_show_icon ? '' : ' no-total-icon' ) . '">'
					. ( $total_show_icon ? '<i class="fas fa-share-alt"></i>' : '' )
					. '<div class="social-rocket-shares-total-inner">'
					. '<span class="social-rocket-shares-total-count">' . ( $settings['rounding'] ? $this->round( $total ) : $total ) . '</span>'
					. '<span class="social-rocket-shares-total-label">' . _n( 'Share', 'Shares', $total, 'social-rocket' ) . '</span>'
					. '</div>'
					. '</div>';
			}
		}
		
		// wrapper classes
		if ( isset( $args['add_class'] ) ) {
			$classes .= ' ' . $args['add_class'];
		}
	
		// begin button bar wrapper
		$output = '<div id="social-rocket-floating-buttons"'
					. ' class="' . $classes . '"'
					. ' style="' . $styles . '"'
					. '>';
		
		if ( $show_total === 'before' ) {
			$output .= $total_output;
		}
		
		$more_triggered = false;
		
		foreach ( $networks as $network_key => $network_name ) {
			
			$cur = '';
			
			$has_count = ( $show_counts && isset( $counts[$network_key] ) && $counts[$network_key]['total'] >= $settings['show_counts_min'] ? true : false );
			
			// begin button wrapper
			$cur .= '<div class="social-rocket-floating-button'
				. ' social-rocket-floating-button-' . $settings['button_style']
				. ' social-rocket-' . $network_key
				. ' ' . $this->get_button_wrapper_class( $network_key, 'floating' ) . '"'
				. ' data-network="' . $network_key . '">';
			
			// begin anchor
			$cur .= '<a class="social-rocket-floating-button-anchor'
				. ( $has_count ? ' social-rocket-has-count' : '' ) . '"'
				. ' href="' . $this->get_share_url( $id, $type, $url, $network_key, 'floating' ) . '"'
				. ' ' . $this->get_anchor_data( $network_key, 'floating' )
				. ' target="_blank"'
				. ' aria-label="' . esc_attr( $this->get_cta( $network_key, $settings ) ) . '">';
			
			// icon
			$cur .= '<span class="social-rocket-floating-button-icon">'
				. '<i class="' . esc_attr( $this->get_icon_class( $network_key, $settings ) ) . '">' . $this->get_icon_svg( $network_key, $settings ) . '</i>'
				. '</span>';
			
			// call to action
			if ( $show_cta ) {
				$cur .= '<span class="social-rocket-floating-button-cta">' . wp_kses_post( $this->get_cta( $network_key, $settings ) ) . '</span>';
			}
			
			// share count
			if ( $has_count ) {
				$cur .= '<span class="social-rocket-floating-button-count">';
				$cur .= ( $settings['rounding'] ? $this->round( $counts[$network_key]['total'] ) : $counts[$network_key]['total'] );
				$cur .= '</span>';
			}
			
			// end anchor
			$cur .= '</a>';
			
			// end button wrapper
			$cur .= '</div>';
			
			// if "more" threshold met, set aside this button for later use
			if ( $more_triggered ) {
				$more_output .= $cur;
			} else {
				$output .= $cur;
			}
			// set for next go around
			if ( $network_key === '_more' ) {
				$more_triggered = true;
			}
			
		}
		
		// more button
		if ( $more_output > '' ) {
			$output .= '<div class="social-rocket-more-buttons" style="display:none;">'
					. $more_output
					. '</div>';
		}
		
		if ( $show_total === 'after' ) {
			$output .= $total_output;
		}
		
		// end button bar wrapper
		$output .= '</div>';
		
		return apply_filters( 'social_rocket_get_floating_buttons_html', $output, $args );
	}
	
	
	/**
	 * Gets icon (fontawesome) class for a given network and settings group.
	 *
	 * @since 1.0.0
	 *
	 * @param string $network  The network key.
	 * @param array  $settings Optional. The settings group.
	 *
	 * @return string The icon class(es)
	 */
	public function get_icon_class( $network, $settings = array() ) {
		
		$output = false;
		
		// get default icon_class from network
		if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
			$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			$output = $SRN->icon_class;
		}
		
		// if network has custom icon_class defined via settings, get that instead
		if (
			isset( $settings['networks'][$network]['settings']['icon_class'] ) &&
			$settings['networks'][$network]['settings']['icon_class'] > ''
		) {
			$output = $settings['networks'][$network]['settings']['icon_class'];
		}
		
		return apply_filters( 'social_rocket_get_icon_class', $output, $network, $settings );
	}
	
	
	/**
	 * Gets SVG icon, if exists, for a given network and settings group.
	 *
	 * @since 1.0.0
	 *
	 * @param string $network  The network key.
	 * @param array  $settings Optional. The settings group.
	 *
	 * @return string The SVG icon if exists, else an empty string.
	 */
	public function get_icon_svg( $network, $settings = array() ) {
		
		$output = false;
		
		// if network has icon_svg defined, get it
		if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
			$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			$output = property_exists( $SRN, 'icon_svg' ) ? $SRN->icon_svg : '';
		}
		
		// if network has custom icon_svg defined via settings, get that instead
		if (
			isset( $settings['networks'][$network]['settings']['icon_svg'] ) &&
			$settings['networks'][$network]['settings']['icon_svg'] > ''
		) {
			$output = $settings['networks'][$network]['settings']['icon_svg'];
		}
		
		// apply a custom fill color, if needed
		// DG 2019-06-20: maybe we should just do this via CSS? I guess it depends which way has the most compatibility with different themes.
		$replace_icon_color = false;
		if ( ! is_admin() ) {
			$color_scheme               = $this->_isset( $settings['button_color_scheme'], '' );
			$custom_icon                = $this->_isset( $settings['button_color_scheme_custom_icon'], '' );
			$custom_icon_color          = $this->_isset( $settings['button_color_scheme_custom_icon_color'], '' );
			$network_color_override     = $this->_isset( $settings['networks'][$network]['settings']['color_override'] );
			$network_color_value        = $network_color_override && $this->_isset( $settings['networks'][$network]['settings']['color'] ) > '' ?
			                                  $settings['networks'][$network]['settings']['color'] : $this->_isset( $SRN->color, '#ffffff' );
			$network_color_bg_value     = $network_color_override && $this->_isset( $settings['networks'][$network]['settings']['color_bg'] ) > '' ?
			                                  $settings['networks'][$network]['settings']['color_bg'] : $this->_isset( $SRN->color_bg, '#787878' );
			$network_color_border_value = $network_color_override && $this->_isset( $settings['networks'][$network]['settings']['color_border'] ) > '' ?
			                                  $settings['networks'][$network]['settings']['color_border'] : $this->_isset( $SRN->color_border, '#666666' );
			
			if ( $network_color_override ) {
				$replace_icon_color = $network_color_value; // network-specific setting always wins
			} else if ( $color_scheme === 'custom' ) {
				if ( $custom_icon === 'custom' && $custom_icon_color > '' ) {
					$replace_icon_color = $custom_icon_color;
				} else if ( $custom_icon === 'network_icon' ) {
					$replace_icon_color = $network_color_value;
				} else if ( $custom_icon === 'network_background' ) {
					$replace_icon_color = $network_color_bg_value;
				} else if ( $custom_icon === 'network_border' ) {
					$replace_icon_color = $network_color_border_value;
				}
			} else if ( $color_scheme === 'inverted' ) {
				$replace_icon_color = $network_color_bg_value;
			}
		}
		if ( apply_filters( 'social_rocket_replace_icon_svg_color', $replace_icon_color ) ) {
			$output = str_replace( 'fill="#ffffff"', 'fill="'.$replace_icon_color.'"', $output );
		}
		
		return apply_filters( 'social_rocket_get_icon_svg', $output, $network, $settings );
	}
	
	
	/**
	 * Generates Inline Buttons HTML code.
	 *
	 * This function may be called on page load by
	 * Social_Rocket::maybe_insert_inline_buttons(), via the shortcode
	 * [socialrocket], or via the global function socal_rocket().
	 *
	 * @version 1.3.3
	 * @since   1.0.0
	 *
	 * @param array $args {
	 *     Optional. An array of arguments.
	 *
	 *     @type int|string $id      The post ID, term ID, user ID, or (string) URL
	 *                               for which the buttons are being generated.
	 *                               Default current page ID.
	 *
	 *     @type string $type        What type of $id was supplied. Default 'post'.
	 *                               Accepts 'post', 'page', 'term', 'category',
	 *                               'tag', 'taxonomy', 'user', 'url'.
	 *
	 *     @type string $add_class   Add CSS class(es) to wrapper div. Default ''.
	 *
	 *     @type string $force_css   If 'true', outputs necessary CSS code
	 *                               immediately before buttons HTML output.
	 *                               Default false. Accepts 'true', 'false'.
	 *
	 *     @type string $heading     Heading text. Default uses global setting.
	 *
	 *     @type string $networks    Comma-separated list of buttons to be shown.
	 *                               Default uses global settings.
	 *
	 *     @type string $share_url   URL to share. Default current page URL.
	 *
	 *     @type string $show_counts Whether to show counts on buttons. Default
	 *                               uses global setting. Accepts 'true', 'false'.
	 *
	 *     @type string $show_total  Where (or if) to display total share count.
	 *                               Default uses global setting. Accepts 'true',
	 *                               'before', 'after', 'none'.
	 * }
	 *
	 * @return string The Inline Buttons HTML code.
	 */
	public function get_inline_buttons_html( $args = array() ) {
		
		// get settings
		$settings = apply_filters( 'social_rocket_inline_buttons_get_settings', $this->settings['inline_buttons'], $args );
		
		// if no active networks, stop here
		if ( ! count( $settings['networks'] ) > 0 ) {
			return;
		}
		
		// get current location
		$loc = $this->where_we_at();
			
		// parse args and/or set defaults
		// 1) id/type/url
		if ( isset( $args['id'] ) ) {
			if ( isset( $args['type'] ) ) {
				$args['type'] = strtolower( $args['type'] );
				if ( in_array( $args['type'], array( 'post', 'page' ) ) ) {
					$type = 'post';
				} elseif ( in_array( $args['type'], array( 'category', 'tag', 'taxonomy', 'term' ) ) ) {
					$type = 'term';
				} elseif ( $args['type'] === 'user' ) {
					$type = 'user';
				} elseif ( $args['type'] === 'url' ) {
					$type = 'url';
				}
			} else {
				$type = 'post';
			}
			switch ( $type ) {
				case 'post':
					$id = intval( $args['id'] );
					$url = get_permalink( $id );
					break;
				case 'term':
					$id = intval( $args['id'] );
					$url = get_term_link( $id );
					break;
				case 'user':
					$id = intval( $args['id'] );
					$url = get_author_posts_url( $id );
					break;
				case 'url':
					$id = $args['id'];
					$url = $args['id'];
			}
		} else {
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		// get networks (now that we have correct $id and $type)
		$networks = apply_filters( 'social_rocket_inline_networks', $settings['networks'], $id, $type );
		
		// continue parsing args and/or setting defaults
		// 2) heading text
		if ( isset( $args['heading'] ) ) {
			$heading = $args['heading'];
		} else {
			$heading = $this->_isset( $settings['heading_text'], '' );
			$heading = apply_filters( 'social_rocket_inline_heading_text', $heading );
		}
		
		// 3) networks override
		if ( isset( $args['networks'] ) ) {
			$networks = array();
			if ( is_array( $args['networks'] ) ) {
				$network_keys = $args['networks'];
			} else {
				$network_keys = explode( ',', strtolower( $args['networks'] ) );
			}
			foreach ( $network_keys as $network_key ) {
				if ( isset( $this->networks[ $network_key ] ) ) {
					$networks[ $network_key ] = $this->networks[ $network_key ];
				}
			}
			if ( empty( $networks ) ) {
				$networks = $settings['networks'];
			}
		}
		
		// 3b) make sure all requested networks are available
		$available_networks = $this->networks;
		foreach ( $networks as $key => $value ) {
			if ( ! isset( $available_networks[ $key ] ) ) {
				unset( $networks[$key] );
			}
		}
		
		// 4) share url override
		$share_url = false;
		if ( isset( $args['share_url'] ) ) {
			$share_url = $args['share_url'];
		}
		
		// 5) show CTA
		$show_cta = false;
		if (
			$settings['button_show_cta'] ||
			is_admin() // for admin previews
		) {
			$show_cta = true;
		}
		
		// 6) show share counts
		$show_counts = $settings['show_counts'] ? true : false;
		if ( isset( $args['show_counts'] ) ) {
			if ( $args['show_counts'] === 'true' ) {
				$show_counts = true;
			}
			if ( $args['show_counts'] === 'false' ) {
				$show_counts = false;
			}
		}
		
		// 7) show total
		$show_total = $settings['show_total'] ? $settings['total_position'] : false;
		if ( isset( $args['show_total'] ) ) {
			if ( $args['show_total'] === 'true' ) {
				$show_total = $settings['total_position'];
			} elseif ( in_array( $args['show_total'], array( 'before', 'after' ) ) ) {
				$show_total = $args['show_total'];
			} elseif ( $args['show_total'] === 'none' ) {
				$show_total = false;
			}
		}
		
		$total_show_icon = $this->_isset( $settings['total_show_icon'] ) ||
						    is_admin() // for admin previews
						    ? true : false;
		
		// maybe get share counts
		if ( $show_counts || $show_total ) {
			if ( is_admin() ) { // dummy counts for admin previews
				$counts = array(
					'facebook'  => array( 'total' => '111' ),
					'twitter'   => array( 'total' => '11' ),
					'pinterest' => array( 'total' => '1' ),
				);
			} else {
				$counts = $this->get_share_counts( $id, $type, $url );
			}
			if ( isset( $counts['networks'] ) ) {
				$counts = $counts['networks'];
			}
		}
		
		// 8) wrapper classes
		$classes = 'social-rocket-inline-buttons';
		if ( isset( $args['add_class'] ) ) {
			$classes .= ' ' . $args['add_class'];
		}
		
		// start output
		$output       = '';
		$more_output  = '';
		$total_output = '';
		
		// output the css now?
		if ( isset( $args['force_css'] ) && $args['force_css'] === 'true' ) {
			$output .= '<style type="text/css">' . $this->get_css_inline( $settings ) . '</style>';
		}
		
		// total shares
		if ( $show_total ) {
			$total = 0;
			foreach ( $networks as $key => $value ) {
				if ( isset( $counts[$key] ) ) {
					$total = $total + $counts[$key]['total'];
				}
			}
			if ( $total >= $settings['show_total_min'] ) {
				$total_output = '<div class="social-rocket-shares-total">'
					. ( $total_show_icon ? '<i class="fas fa-share-alt"></i>' : '' )
					. '<div class="social-rocket-shares-total-inner">'
					. '<span class="social-rocket-shares-total-count">' . ( $settings['rounding'] ? $this->round( $total ) : $total ) . '</span>'
					. '<span class="social-rocket-shares-total-label">' . _n( 'Share', 'Shares', $total, 'social-rocket' ) . '</span>'
					. '</div>'
					. '</div>';
			}
		}
		
		// begin main wrapper div
		$output .= '<div class="' . $classes . '">';
		
		// heading text
		if ( $heading > '' ) {
			$heading_element = $this->_isset( $settings['heading_element'], 'h4' );
			$output .= "<$heading_element class=\"social-rocket-buttons-heading\">$heading</$heading_element>";
		}
		
		// begin button bar wrapper
		$output .= '<div class="social-rocket-buttons">';
		
		if ( $show_total === 'before' ) {
			$output .= $total_output;
		}
		
		$more_triggered = false;
		
		foreach ( $networks as $network_key => $network_name ) {
			
			$cur = '';
			
			// begin button wrapper
			$cur .= '<div class="social-rocket-button'
				. ' social-rocket-button-' . $settings['button_style']
				. ' social-rocket-' . $network_key
				. ' ' . $this->get_button_wrapper_class( $network_key )
				. '" data-network="' . $network_key . '">';
			
			// begin anchor
			$cur .= '<a class="social-rocket-button-anchor"'
				. ' href="' . $this->get_share_url( $id, $type, $url, $network_key, 'inline', $share_url ) . '"'
				. ' ' . $this->get_anchor_data( $network_key )
				. ' target="_blank"'
				. ' aria-label="' . esc_attr( $this->get_cta( $network_key, $settings ) ) . '">';
			
			// icon
			$cur .= '<i class="'. esc_attr( $this->get_icon_class( $network_key, $settings ) ) . ' social-rocket-button-icon">' .
				$this->get_icon_svg( $network_key, $settings ) .
				'</i>';
			
			// call to action
			if ( $show_cta ) {
				$cur .= '<span class="social-rocket-button-cta">' . wp_kses_post( $this->get_cta( $network_key, $settings ) ) . '</span>';
			}
			
			// share count
			if ( 
				$show_counts &&
				isset( $counts[$network_key] ) &&
				$counts[$network_key]['total'] >= $settings['show_counts_min']
			) {
				$cur .= '<span class="social-rocket-button-count">'
					. ( $settings['rounding'] ? $this->round( $counts[$network_key]['total'] ) : $counts[$network_key]['total'] )
					. '</span>';
			}
			
			// end anchor
			$cur .= '</a>';
			
			// end button wrapper
			$cur .= '</div>';
			
			// if "more" threshold met, set aside this button for later use
			if ( $more_triggered ) {
				$more_output .= $cur;
			} else {
				$output .= $cur;
			}
			// set for next go around
			if ( $network_key === '_more' ) {
				$more_triggered = true;
			}
			
		}
		
		// more button
		if ( $more_output > '' ) {
			$output .= '<div class="social-rocket-more-buttons" style="display:none;">'
					. $more_output
					. '</div>';
		}
		
		if ( $show_total === 'after' ) {
			$output .= $total_output;
		}
		
		// end button bar wrapper
		$output .= '</div>';
		
		// end main wrapper div
		$output .= '</div>';
		
		return apply_filters( 'social_rocket_get_inline_buttons_html', $output, $args );
	}
	
	
	public function get_position( $post_id = 0 ) {
		
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		if ( ! $post_id ) {
			return false;
		}
		
		$post_type = get_post_type( $post_id );
		
		$position = get_post_meta( $post_id, 'social_rocket_position', true );
		if ( ! $position > '' ) {
			if ( isset( $this->settings['position_'.$post_type] ) ) {
				$position = $this->settings['position_'.$post_type];
			} else {
				return false;
			}
		}
		
		return apply_filters( 'social_rocket_get_position', $position, $post_id );
		
	}
	
	
	public static function get_post_types() {
	
		$cpts = get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'names'
		);
		$types = array(
			'page',
			'post',
			'attachment',
		);
		if ( ! empty( $cpts ) ) {
			$types = array_merge( $types, array_values( $cpts ) );
		}
		
		return apply_filters( 'social_rocket_post_types', $types );
	}
	
	
	public function get_share_counts( $id = 0, $type = 'post', $url = '' ) {
	
		if ( ! $id ) {
			$loc  = $this->where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];;
			$url  = $loc['url'];
		}
		
		$data = $this->maybe_update_share_counts( $id, $type, $url );
		
		return apply_filters( 'social_rocket_get_share_counts', $data, $id, $type, $url );
	
	}
	
	
	public function get_share_url( $id = 0, $type = 'post', $url = '', $network = '', $scope = 'inline', $override_url = false ) {
		
		$output = false;
		
		if ( $override_url ) {
			$url = $override_url;
		} else {
			$url = apply_filters( 'social_rocket_pre_get_share_url', $url, $id, $type, $network );
		}
		
		if ( class_exists( 'Social_Rocket_'.ucfirst($network) ) ) {
			
			$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			$output = $SRN->share_url;
			
			if ( $url > '' ) {
			
				// replace wildcards
				
				// %title%
				$title = $this->get_title( $id, $type, $url );
				$output = str_replace( '%title%', rawurlencode( $title ), $output );
		
				// %url%
				$output = str_replace( '%url%', urlencode( $url ), $output );
				
			}
			
		}
		
		return apply_filters( 'social_rocket_get_share_url', $output, $id, $type, $url, $network, $scope );
	}
	
	
	public function get_title( $id = 0, $type = 'post', $url = '' ) {
	
		if ( ! $id ) {
			$loc  = $this->where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];;
			$url  = $loc['url'];
		}
		
		$title = '';
		
		if ( $type === 'post' ) {
			
			// 1) check for Social Rocket setting
			$title = get_post_meta( $id, 'social_rocket_og_title', true );
			
			// 2) if nothing, check Yoast
			if ( ! $title && class_exists( 'WPSEO_OpenGraph' ) ) {
				$title = get_post_meta( $id, '_yoast_wpseo_opengraph-title', true );
			}
			
			// 3) if still nothing, use the title
			if ( ! $title ) {
				$title = get_the_title( $id );
			}
			
		} elseif ( $type === 'term' ) {
			
			// 1) check for Social Rocket setting
			$title = get_term_meta( $id, 'social_rocket_og_title', true );
			
			// 2) if nothing, check Yoast
			if ( ! $title && class_exists( 'WPSEO_OpenGraph' ) ) {
				$title = get_term_meta( $id, '_yoast_wpseo_opengraph-title', true );
			}
			
			// 3) if still nothing, use the title
			if ( ! $title ) {
				$title = get_the_archive_title();
			}
			
		} else {
			
			$title = wp_get_document_title();
			
		}
		
		return $title;
	}
	
	
	/**
	 * Generates Click to Tweet HTML code.
	 *
	 * This function may be called by the shortcode [socialrocket-tweet],
	 * or via the global function socal_rocket_tweet().
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     Optional. An array of arguments.
	 *
	 *     @type string $quote       Text displayed on the page.
	 *
	 *     @type string $tweet       Text that populates the tweet message when
	 *                               clicked.
	 *
	 *     @type string $url         Specify URL to be included in the tweet
	 *                               message. Default uses the current page URL.
	 *
	 *     @type string $via         Specify the via {username} to be included in
	 *                               the tweet message. Default uses global
	 *                               setting.
	 *
	 *     @type string $include_url Whether to include the page URL in the tweet.
	 *                               Default uses global setting.
	 *
	 *     @type string $include_via Whether to include the via {username} in the
	 *                               tweet. Default uses global setting.
	 *
	 *     @type string $style_id    Internal ID of saved style to use.
	 *
	 *     @type string $add_class   Add CSS class(es) to wrapper div. Default ''.
	 *
	 *     @type string $force_css   If 'true', outputs necessary CSS code
	 *                               immediately before buttons HTML output.
	 *                               Default false. Accepts 'true', 'false'.
	 * }
	 *
	 * @return string The Click to Tweet HTML code.
	 */
	public function get_tweet_code( $args = array() ) {
		
		// parse args and/or set defaults
		$quote = $this->_isset( $args['quote'], '' );
		$quote = str_replace( '__quot__', '"', $quote );
		
		$tweet = $this->_isset( $args['tweet'], '' );
		$tweet = str_replace( '__quot__', '"', $tweet );
		
		$cta = $this->settings['tweet_settings']['saved_settings']['default']['cta_text'];
		if (
			$this->_isset( $args['style_id'] ) &&
			$this->_isset( $this->settings['tweet_settings']['saved_settings'][ $args['style_id'] ]['cta_text'] )
		) {
			$cta = $this->settings['tweet_settings']['saved_settings'][ $args['style_id'] ]['cta_text'];
		}
		
		$include_url = $this->_isset( $args['include_url'], $this->_isset( $this->settings['tweet_settings']['saved_settings']['default']['include_url'] ) );
		if ( is_string( $include_url ) && strtolower( $include_url ) === 'true' ) {
			$include_url = true;
		} elseif ( is_string( $include_url ) && strtolower( $include_url ) === 'false' ) {
			$include_url = false;
		}
		
		$include_via = $this->_isset( $args['include_via'], $this->_isset( $this->settings['tweet_settings']['saved_settings']['default']['include_via'] ) );
		if ( is_string( $include_via ) && strtolower( $include_via ) === 'true' ) {
			$include_via = true;
		} elseif ( is_string( $include_via ) && strtolower( $include_via ) === 'false' ) {
			$include_via = false;
		}
		
		$extra_classes = $this->_isset( $args['add_class'], '' );
		if ( $this->_isset( $args['style_id'] ) ) {
			$extra_classes .= ( $extra_classes ? ' ' : '' ) . 'social-rocket-tweet-style-'.$args['style_id'];
		}
		
		if ( $this->_isset( $args['url'] ) > '' ) {
			$id   = $args['url'];
			$type = 'url';
			$url  = $args['url'];
		} else {
			$loc  = $this->where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		$url = apply_filters( 'social_rocket_pre_get_share_url', $url, $id, $type, 'twitter' );
		
		$via = $this->_isset( $args['via'] ) > '' ? $args['via'] : $this->settings['social_identity']['twitter'];
		$via = str_replace( '@', '', $via );
		
		// start output
		$output = '';
		
		// output the css now?
		if ( isset( $args['force_css'] ) && $args['force_css'] === 'true' ) {
			$output .= '<style type="text/css">' . $this->get_css_tweet() . '</style>';
		}
		
		// begin click to tweet wrapper
		$output .= '<div class="social-rocket-tweet' . ( $extra_classes ? ' ' . $extra_classes : '' ) . '">';
		
		// begin anchor
		$output .= '<a class="social-rocket-tweet-anchor" '
			. 'href="https://twitter.com/share?text=' . rawurlencode( $tweet )
			. ( $include_url ? '&url=' . rawurlencode( $url ) : '' )
			. ( $include_via ? '&via=' . rawurlencode( $via ) : '' )
			. '" rel="nofollow noopener" target="_blank">';
		
		// quote
		$output .= '<div class="social-rocket-tweet-quote">';
		$output .= $quote;
		$output .= '</div>';
		
		// call to action
		$output .= '<div class="social-rocket-tweet-cta">';
		$output .= $cta;
		$output .= ' <i class="fab fa-twitter"></i>';
		$output .= '</div>';
		
		// end anchor
		$output .= '</a>';
		
		// end click to tweet wrapper
		$output .= '</div>';
		
		return apply_filters( 'social_rocket_get_tweet_code', $output, $args );
	}
	
	
	public static function http_get( $url ) {
		
		$response = wp_remote_get( $url, array( 'timeout' => 10 ) );
		
		if( is_wp_error( $response ) ) {
			$error_string = $response->get_error_message();
			$response = '<div id="message" class="error"><p>' . $error_string . '</p></div>';
		}

		if( is_array( $response ) ) {
			$response = $response['body'];
		}

		return $response;
		
	}
	
	
	public function insert_floating_buttons_bottom_content( $echo = true ) {
		
		$args = array( 'placement' => 'bottom' );
		$code = '';
		
		$this->data = apply_filters( 'social_rocket_insert_floating_data', $this->data );
		
		// don't insert buttons again if already done (allows for fallbacks)
		if ( isset( $this->data['floating_bottom_done'] ) && $this->data['floating_bottom_done'] ) {
			return;
		}
		
		// don't insert buttons if we're in the excerpt
		if ( isset( $this->data['doing_excerpt'] ) && $this->data['doing_excerpt'] ) {
			return;
		}
		
		// is it an archive?
		if ( isset( $this->data['floating_archive_id'] ) ) {
			$args['id'] = $this->data['floating_archive_id'];
		}
		if ( isset( $this->data['floating_archive_type'] ) ) {
			$args['type'] = $this->data['floating_archive_type'];
		}
		
		// get buttons code
		if ( isset( $this->data['floating_bottom_add_class'] ) ) {
			if ( is_array( $this->data['floating_bottom_add_class'] ) ) {
				// multiple add_classes
				foreach ( $this->data['floating_bottom_add_class'] as $add_class ) {
					$args['add_class'] = 'social-rocket-'.$add_class;
					$code .= $this->get_floating_buttons_html( $args );
				}
			} else {
				// single add_class
				$args['add_class'] = 'social-rocket-'.$this->data['floating_bottom_add_class'];
				$code .= $this->get_floating_buttons_html( $args );
			}
		} else {
			// no add_class
			$code .= $this->get_floating_buttons_html( $args );
		}
		
		// done
		$this->data['floating_bottom_done'] = true;
		if ( $echo !== false ) {
			echo $code;
		} else {
			return $code;
		}
	}
	
	
	public function insert_floating_buttons_bottom_content_filter( $content ) {
		return $content . $this->insert_floating_buttons_bottom_content( false );
	}
	
	
	public function insert_floating_buttons_left_content( $echo = true ) {
		
		$args = array( 'placement' => 'left' );
		$code = '';
		
		$this->data = apply_filters( 'social_rocket_insert_floating_data', $this->data );
		
		// don't insert buttons again if already done (allows for fallbacks)
		if ( isset( $this->data['floating_left_done'] ) && $this->data['floating_left_done'] ) {
			return;
		}
		
		// don't insert buttons if we're in the excerpt
		if ( isset( $this->data['doing_excerpt'] ) && $this->data['doing_excerpt'] ) {
			return;
		}
		
		// is it an archive?
		if ( isset( $this->data['floating_archive_id'] ) ) {
			$args['id'] = $this->data['floating_archive_id'];
		}
		if ( isset( $this->data['floating_archive_type'] ) ) {
			$args['type'] = $this->data['floating_archive_type'];
		}
		
		// get buttons code
		if ( isset( $this->data['floating_left_add_class'] ) ) {
			if ( is_array( $this->data['floating_left_add_class'] ) ) {
				// multiple add_classes
				foreach ( $this->data['floating_left_add_class'] as $add_class ) {
					$args['add_class'] = 'social-rocket-'.$add_class;
					$code .= $this->get_floating_buttons_html( $args );
				}
			} else {
				// single add_class
				$args['add_class'] = 'social-rocket-'.$this->data['floating_left_add_class'];
				$code .= $this->get_floating_buttons_html( $args );
			}
		} else {
			// no add_class
			$code .= $this->get_floating_buttons_html( $args );
		}
		
		// done
		$this->data['floating_left_done'] = true;
		if ( $echo !== false ) {
			echo $code;
		} else {
			return $code;
		}
	}
	
	
	public function insert_floating_buttons_left_content_filter( $content ) {
		return $content . $this->insert_floating_buttons_left_content( false );
	}
	
	
	public function insert_floating_buttons_right_content( $echo = true ) {
		
		$args = array( 'placement' => 'right' );
		$code = '';
		
		$this->data = apply_filters( 'social_rocket_insert_floating_data', $this->data );
		
		// don't insert buttons again if already done (allows for fallbacks)
		if ( isset( $this->data['floating_right_done'] ) && $this->data['floating_right_done'] ) {
			return;
		}
		
		// don't insert buttons if we're in the excerpt
		if ( isset( $this->data['doing_excerpt'] ) && $this->data['doing_excerpt'] ) {
			return;
		}
		
		// is it an archive?
		if ( isset( $this->data['floating_archive_id'] ) ) {
			$args['id'] = $this->data['floating_archive_id'];
		}
		if ( isset( $this->data['floating_archive_type'] ) ) {
			$args['type'] = $this->data['floating_archive_type'];
		}
		
		// get buttons code
		if ( isset( $this->data['floating_right_add_class'] ) ) {
			if ( is_array( $this->data['floating_right_add_class'] ) ) {
				// multiple add_classes
				foreach ( $this->data['floating_right_add_class'] as $add_class ) {
					$args['add_class'] = 'social-rocket-'.$add_class;
					$code .= $this->get_floating_buttons_html( $args );
				}
			} else {
				// single add_class
				$args['add_class'] = 'social-rocket-'.$this->data['floating_right_add_class'];
				$code .= $this->get_floating_buttons_html( $args );
			}
		} else {
			// no add_class
			$code .= $this->get_floating_buttons_html( $args );
		}
		
		// done
		$this->data['floating_right_done'] = true;
		if ( $echo !== false ) {
			echo $code;
		} else {
			return $code;
		}
	}
	
	
	public function insert_floating_buttons_right_content_filter( $content ) {
		return $content . $this->insert_floating_buttons_right_content( false );
	}
	
	
	public function insert_floating_buttons_top_content( $echo = true ) {
		
		$args = array( 'placement' => 'top' );
		$code = '';
		
		$this->data = apply_filters( 'social_rocket_insert_floating_data', $this->data );
		
		// don't insert buttons again if already done (allows for fallbacks)
		if ( isset( $this->data['floating_top_done'] ) && $this->data['floating_top_done'] ) {
			return;
		}
		
		// don't insert buttons if we're in the excerpt
		if ( isset( $this->data['doing_excerpt'] ) && $this->data['doing_excerpt'] ) {
			return;
		}
		
		// is it an archive?
		if ( isset( $this->data['floating_archive_id'] ) ) {
			$args['id'] = $this->data['floating_archive_id'];
		}
		if ( isset( $this->data['floating_archive_type'] ) ) {
			$args['type'] = $this->data['floating_archive_type'];
		}
		
		// get buttons code
		if ( isset( $this->data['floating_top_add_class'] ) ) {
			if ( is_array( $this->data['floating_top_add_class'] ) ) {
				// multiple add_classes
				foreach ( $this->data['floating_top_add_class'] as $add_class ) {
					$args['add_class'] = 'social-rocket-'.$add_class;
					$code .= $this->get_floating_buttons_html( $args );
				}
			} else {
				// single add_class
				$args['add_class'] = 'social-rocket-'.$this->data['floating_top_add_class'];
				$code .= $this->get_floating_buttons_html( $args );
			}
		} else {
			// no add_class
			$code .= $this->get_floating_buttons_html( $args );
		}
		
		// done
		$this->data['floating_top_done'] = true;
		if ( $echo !== false ) {
			echo $code;
		} else {
			return $code;
		}
	}
	
	
	public function insert_floating_buttons_top_content_filter( $content ) {
		return $content . $this->insert_floating_buttons_top_content( false );
	}
	
	
	public function insert_inline_buttons_after_content( $echo = true ) {
		
		$args = array();
		$code = '';
		
		$this->data = apply_filters( 'social_rocket_insert_inline_data', $this->data );
		
		// don't insert buttons again if already done (allows for fallbacks)
		if ( isset( $this->data['inline_after_done'] ) && $this->data['inline_after_done'] ) {
			return;
		}
		
		// don't insert buttons if we're in the excerpt
		if ( isset( $this->data['doing_excerpt'] ) && $this->data['doing_excerpt'] ) {
			return;
		}
		
		// don't insert buttons unless it's the main query
		if ( apply_filters( 'social_rocket_main_query_only', ! is_main_query() ) ) {
			return;
		}
		
		// is it an archive?
		if ( isset( $this->data['inline_archive_id'] ) ) {
			$args['id'] = $this->data['inline_archive_id'];
		}
		if ( isset( $this->data['inline_archive_type'] ) ) {
			$args['type'] = $this->data['inline_archive_type'];
		}
		
		// add before code, if any
		if ( isset( $this->data['inline_archive_html_before'] ) ) {
			$code .= $this->data['inline_archive_html_before'];
		}
		
		// get buttons code
		if ( isset( $this->data['inline_after_add_class'] ) ) {
			if ( is_array( $this->data['inline_after_add_class'] ) ) {
				// multiple add_classes
				foreach ( $this->data['inline_after_add_class'] as $add_class ) {
					$args['add_class'] = 'social-rocket-'.$add_class;
					$code .= $this->get_inline_buttons_html( $args );
				}
			} else {
				// single add_class
				$args['add_class'] = 'social-rocket-'.$this->data['inline_after_add_class'];
				$code .= $this->get_inline_buttons_html( $args );
			}
		} else {
			// no add_class
			$code .= $this->get_inline_buttons_html( $args );
		}
		
		// add after code, if any
		if ( isset( $this->data['inline_archive_html_after'] ) ) {
			$code .= $this->data['inline_archive_html_after'];
		}
		
		// done
		$this->data['inline_after_done'] = true;
		if ( $echo !== false ) {
			echo $code;
		} else {
			return $code;
		}
	}
	
	
	public function insert_inline_buttons_after_content_filter( $content ) {
		return $content . $this->insert_inline_buttons_after_content( false );
	}
	
	
	public function insert_inline_buttons_before_content( $echo = true ) {
		
		$args = array();
		$code = '';
		
		$this->data = apply_filters( 'social_rocket_insert_inline_data', $this->data );
		
		// don't insert buttons again if already done (allows for fallbacks)
		if ( isset( $this->data['inline_before_done'] ) && $this->data['inline_before_done'] ) {
			return;
		}
		
		// don't insert buttons if we're in the excerpt
		if ( isset( $this->data['doing_excerpt'] ) && $this->data['doing_excerpt'] ) {
			return;
		}
		
		// don't insert buttons unless it's the main query
		if ( apply_filters( 'social_rocket_main_query_only', ! is_main_query() ) ) {
			return;
		}
		
		// is it an archive?
		if ( isset( $this->data['inline_archive_id'] ) ) {
			$args['id'] = $this->data['inline_archive_id'];
		}
		if ( isset( $this->data['inline_archive_type'] ) ) {
			$args['type'] = $this->data['inline_archive_type'];
		}
		
		// add before code, if any
		if ( isset( $this->data['inline_archive_html_before'] ) ) {
			$code .= $this->data['inline_archive_html_before'];
		}
		
		// get buttons code
		if ( isset( $this->data['inline_before_add_class'] ) ) {
			if ( is_array( $this->data['inline_before_add_class'] ) ) {
				// multiple add_classes
				foreach ( $this->data['inline_before_add_class'] as $add_class ) {
					$args['add_class'] = 'social-rocket-'.$add_class;
					$code .= $this->get_inline_buttons_html( $args );
				}
			} else {
				// single add_class
				$args['add_class'] = 'social-rocket-'.$this->data['inline_before_add_class'];
				$code .= $this->get_inline_buttons_html( $args );
			}
		} else {
			// no add_class
			$code .= $this->get_inline_buttons_html( $args );
		}
		
		// add after code, if any
		if ( isset( $this->data['inline_archive_html_after'] ) ) {
			$code .= $this->data['inline_archive_html_after'];
		}
		
		// done
		$this->data['inline_before_done'] = true;
		if ( $echo !== false ) {
			echo $code;
		} else {
			return $code;
		}
	}
	
	
	public function insert_inline_buttons_before_content_filter( $content ) {
		return $this->insert_inline_buttons_before_content( false ) . $content;
	}
	
	
	public function insert_inline_buttons_item_content( $echo = true ) {
	
		$args = array();
		$code = '';
		$loc  = $this->where_we_at();
		
		$this->data = apply_filters( 'social_rocket_insert_inline_data', $this->data );
		
		// don't insert buttons again if already done (allows for fallbacks)
		if ( isset( $this->data['inline_item_done'] ) && $this->data['inline_item_done'] === $loc['id'] ) {
			return;
		}
		
		// don't insert buttons if we're in the excerpt
		if ( isset( $this->data['doing_excerpt'] ) && $this->data['doing_excerpt'] ) {
			return;
		}
		
		// disable paged requests link to first page, for this item only
		add_filter( 'social_rocket_archives_url_use_first_page', array( $this, '_return_false' ) );
		
		// get buttons code
		if ( isset( $this->data['inline_item_add_class'] ) ) {
			if ( is_array( $this->data['inline_item_add_class'] ) ) {
				// multiple add_classes
				foreach ( $this->data['inline_item_add_class'] as $add_class ) {
					$args['add_class'] = 'social-rocket-'.$add_class;
					$code .= $this->get_inline_buttons_html( $args );
				}
			} else {
				// single add_class
				$args['add_class'] = 'social-rocket-'.$this->data['inline_item_add_class'];
				$code .= $this->get_inline_buttons_html( $args );
			}
		} else {
			// no add_class
			$code .= $this->get_inline_buttons_html( $args );
		}
		
		// restore filter
		remove_filter( 'social_rocket_archives_url_use_first_page', array( $this, '_return_false' ) );
		
		// done
		$this->data['inline_item_done'] = $loc['id'];
		if ( $echo !== false ) {
			echo $code;
		} else {
			return $code;
		}
	}
	
	
	public function insert_inline_buttons_item_content_filter( $content ) {
		return $content . $this->insert_inline_buttons_item_content( false );
	}
	

	/*
	 * Determine if current request is for some non-countable WordPress thing, like
	 * an autosave, block renderer call, some rest api nonsense, etc.
	 *
	 * @since 1.2.2
	 *
	 * @return boolean Whether current request is non-countable.
	 */
	public function is_non_countable_request() {
		
		// is an admin page, or an ajax request
		if ( is_admin() ) {
			return true;
		}
		
		// is a post or page preview
		if ( is_preview() ) {
			return true;
		}
		
		// is a debug request
		if ( defined( 'SRP_DEBUG' ) && SRP_DEBUG ) {
			return true;
		}
		
		// for rest api calls, the following is based on an idea from
		// https://wordpress.stackexchange.com/questions/221202/does-something-like-is-rest-exist,
		// with some modifications:
		/**
		 * Case #1: After WP_REST_Request initialisation
		 * Case #2: Support "plain" permalink settings
		 * Case #3: It can happen that WP_Rewrite is not yet initialized,
		 *          so do this (wp-settings.php)
		 * Case #4: URL Path begins with wp-json/ (your REST prefix)
		 *          Also supports WP installations in subfolders
		 */
		if ( 
			// (#1)
			( defined( 'REST_REQUEST' ) && REST_REQUEST )
			||
			// (#2)
			(
				isset( $_GET['rest_route'] )
				&& strpos( trim( $_GET['rest_route'], '\\/' ), rest_get_url_prefix(), 0 ) === 0
			)
		) {
			return true;
		}
		// (#3)
		global $wp_rewrite;
		if ( $wp_rewrite === null ) {
			$wp_rewrite = new WP_Rewrite();
		}
		// (#4)
		$rest_url = wp_parse_url( trailingslashit( rest_url() ) );
		$current_url = wp_parse_url( add_query_arg( array() ) );
		if ( strpos( $current_url['path'], $rest_url['path'], 0 ) === 0 ) {
			return true;
		}
		
		return false;
	}
	
	
	public function load_available_networks() {
	
		foreach ( $this->networks as $key => $value ) {
			require_once( SOCIAL_ROCKET_PATH . "includes/networks/class-social-rocket-{$key}.php" );
		}
	
	}
	
	
	/**
	 * Maybe outputs og tags/twitter cards into <head>
	 *
	 * @since 1.0.0
	 */
	public function maybe_add_og_tags() {
		
		$loc  = Social_Rocket::where_we_at();
		$id   = $loc['id'];
		$type = $loc['type'];
		$url  = $loc['url'];
		
		if ( ! $id ) {
			return false;
		}
		
		if ( $this->_isset( $this->settings['disable_og_tags'] ) && $this->_isset( $this->settings['disable_twitter_cards'] ) ) {
			return false;
		}
		
		echo "\n" . '<!-- Begin Social Rocket v' . SOCIAL_ROCKET_VERSION . ' https://wpsocialrocket.com -->' . "\n";
		
		if ( ! $this->_isset( $this->settings['disable_og_tags'] ) ) {
 
			// og:title
			if ( $type === 'post' ) {
				
				// 1) check for Social Rocket setting
				$og_title = get_post_meta( $id, 'social_rocket_og_title', true );
				
				// 2) if nothing, check Yoast
				if ( ! $og_title && class_exists( 'WPSEO_OpenGraph' ) ) {
					$og_title = get_post_meta( $id, '_yoast_wpseo_opengraph-title', true );
				}
				
				// 3) if still nothing, use the title
				if ( ! $og_title ) {
					$og_title = get_the_title( $id );
				}
				
			} elseif ( $type === 'term' ) {
				
				// 1) check for Social Rocket setting
				$og_title = get_term_meta( $id, 'social_rocket_og_title', true );
				
				// 2) if nothing, check Yoast
				if ( ! $og_title && class_exists( 'WPSEO_OpenGraph' ) ) {
					$og_title = get_term_meta( $id, '_yoast_wpseo_opengraph-title', true );
				}
				
				// 3) if still nothing, use the title
				if ( ! $og_title ) {
					$og_title = get_the_archive_title();
				}
				
			} else {
				
				$og_title = wp_get_document_title();
				
			}
			
			$og_title = apply_filters( 'social_rocket_og_title', $og_title );
			
			if ( $og_title ) {
				echo '<meta property="og:title" content="', esc_attr( $og_title ), '" />' . "\n";
			}
			
			
			// og:description
			if ( $type === 'post' ) {
				
				// 1) check for Social Rocket setting
				$og_description = get_post_meta( $id, 'social_rocket_og_description', true );
				
				// 2) if nothing, check Yoast
				if ( ! $og_description && class_exists( 'WPSEO_OpenGraph' ) ) {
					$og_description = get_post_meta( $id, '_yoast_wpseo_opengraph-description', true );
				}
				
				// 3) if still nothing, use the excerpt
				if ( ! $og_description ) {
					$og_description = str_replace( '[&hellip;]', '&hellip;', wp_strip_all_tags( get_the_excerpt() ) );
				}
				
			} elseif ( $type === 'term' ) {
				
				// 1) check for Social Rocket setting
				$og_description = get_term_meta( $id, 'social_rocket_og_description', true );
				
				// 2) if nothing, check Yoast
				if ( ! $og_description && class_exists( 'WPSEO_OpenGraph' ) ) {
					$og_description = get_term_meta( $id, '_yoast_wpseo_opengraph-description', true );
				}
				
				// 3) if still nothing, use the excerpt
				if ( ! $og_description ) {
					$og_description = str_replace( '[&hellip;]', '&hellip;', wp_strip_all_tags( get_the_excerpt() ) );
				}
				
			} else {
				
				$og_description = get_bloginfo( 'description' );
				
			}
			
			$og_description = apply_filters( 'social_rocket_og_description', $og_description );
			
			if ( $og_description ) {
				echo '<meta property="og:description" content="', esc_attr( $og_description ), '" />' . "\n";
			}
			
			
			// og:image
			$og_image = false;
			$og_image_full = false;
				
			if ( $type === 'post' ) {
				
				// 1) check for Social Rocket setting
				$og_image = get_post_meta( $id, 'social_rocket_og_image', true );
				
				if ( $og_image ) {
					$og_image_full = wp_get_attachment_image_src( $og_image, 'full' );
				}
				
				// 2) if nothing, check Yoast
				if ( ! $og_image && class_exists( 'WPSEO_OpenGraph' ) ) {
					$og_image = get_post_meta( $id, '_yoast_wpseo_opengraph-image', true );
				}
				
			} elseif ( $type === 'term' ) {
			
				// 1) check for Social Rocket setting
				$og_image = get_term_meta( $id, 'social_rocket_og_image', true );
				
				if ( $og_image ) {
					$og_image_full = wp_get_attachment_image_src( $og_image, 'full' );
				}
				
				// 2) if nothing, check Yoast
				if ( ! $og_image && class_exists( 'WPSEO_OpenGraph' ) ) {
					$og_image = get_term_meta( $id, '_yoast_wpseo_opengraph-image', true );
				}
				
			}
			
			$og_image        = apply_filters( 'social_rocket_og_image', ( is_array( $og_image_full ) ? $og_image_full[0] : $og_image ) );
			$og_image_width  = apply_filters( 'social_rocket_og_image_width', ( is_array( $og_image_full ) ? $og_image_full[1] : false ) );
			$og_image_height = apply_filters( 'social_rocket_og_image_height', ( is_array( $og_image_full ) ? $og_image_full[2] : false ) );
			
			if ( $og_image ) {
				echo '<meta property="og:image" content="', esc_attr( $og_image ), '" />' . "\n";
			}
			
			if ( $og_image_width ) {
				echo '<meta property="og:image:width" content="', esc_attr( $og_image_width ), '" />' . "\n";
			}
			
			if ( $og_image_height ) {
				echo '<meta property="og:image:height" content="', esc_attr( $og_image_height ), '" />' . "\n";
			}
			
		}
		
		
		if ( ! $this->_isset( $this->settings['disable_twitter_cards'] ) ) {
		
			echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
			
			// twitter:title
			if ( $type === 'post' ) {
				
				// 1) check for Social Rocket setting
				$twitter_title = get_post_meta( $id, 'social_rocket_og_title', true );
				
				// 2) if nothing, check Yoast
				if ( ! $twitter_title && class_exists( 'WPSEO_OpenGraph' ) ) {
					$twitter_title = get_post_meta( $id, '_yoast_wpseo_twitter-title', true );
				}
				
				// 3) if still nothing, use the title
				if ( ! $twitter_title ) {
					$twitter_title = get_the_title( $id );
				}
				
			} elseif ( $type === 'term' ) {
				
				// 1) check for Social Rocket setting
				$twitter_title = get_term_meta( $id, 'social_rocket_og_title', true );
				
				// 2) if nothing, check Yoast
				if ( ! $twitter_title && class_exists( 'WPSEO_OpenGraph' ) ) {
					$twitter_title = get_term_meta( $id, '_yoast_wpseo_twitter-title', true );
				}
				
				// 3) if still nothing, use the title
				if ( ! $twitter_title ) {
					$twitter_title = get_the_archive_title();
				}
				
			} else {
				
				$twitter_title = wp_get_document_title();
				
			}
			
			$twitter_title = apply_filters( 'social_rocket_twitter_title', $twitter_title );
			
			if ( $twitter_title ) {
				echo '<meta name="twitter:title" content="', esc_attr( $twitter_title ), '" />' . "\n";
			}
			
			
			// twitter:description
			if ( $type === 'post' ) {
				
				// 1) check for Social Rocket setting
				$twitter_description = get_post_meta( $id, 'social_rocket_og_description', true );
				
				// 2) if nothing, check Yoast
				if ( ! $twitter_description && class_exists( 'WPSEO_OpenGraph' ) ) {
					$twitter_description = get_post_meta( $id, '_yoast_wpseo_twitter-description', true );
				}
				
				// 3) if still nothing, use the excerpt
				if ( ! $twitter_description ) {
					$twitter_description = str_replace( '[&hellip;]', '&hellip;', wp_strip_all_tags( get_the_excerpt() ) );
				}
			
			} elseif ( $type === 'term' ) {
				
				// 1) check for Social Rocket setting
				$twitter_description = get_term_meta( $id, 'social_rocket_og_description', true );
				
				// 2) if nothing, check Yoast
				if ( ! $twitter_description && class_exists( 'WPSEO_OpenGraph' ) ) {
					$twitter_description = get_term_meta( $id, '_yoast_wpseo_twitter-description', true );
				}
				
				// 3) if still nothing, use the excerpt
				if ( ! $twitter_description ) {
					$twitter_description = str_replace( '[&hellip;]', '&hellip;', wp_strip_all_tags( get_the_excerpt() ) );
				}
				
			} else {
				
				$twitter_description = get_bloginfo( 'description' );
				
			}
			
			$twitter_description = apply_filters( 'social_rocket_twitter_description', $twitter_description );
			
			if ( $twitter_description ) {
				echo '<meta name="twitter:description" content="', esc_attr( $twitter_description ), '" />' . "\n";
			}
			
			
			// twitter:image
			$twitter_image = false;
			$twitter_image_full = false;
				
			if ( $type === 'post' ) {
				
				// 1) check for Social Rocket setting
				$twitter_image = get_post_meta( $id, 'social_rocket_og_image', true );
				
				if ( $twitter_image ) {
					$twitter_image_full = wp_get_attachment_image_src( $twitter_image, 'full' );
				}
				
				// 2) if nothing, check Yoast
				if ( ! $twitter_image && class_exists( 'WPSEO_OpenGraph' ) ) {
					$twitter_image = get_post_meta( $id, '_yoast_wpseo_twitter-image', true );
				}
				
			} elseif ( $type === 'term' ) {
				
				// 1) check for Social Rocket setting
				$twitter_image = get_term_meta( $id, 'social_rocket_og_image', true );
				
				if ( $twitter_image ) {
					$twitter_image_full = wp_get_attachment_image_src( $twitter_image, 'full' );
				}
				
				// 2) if nothing, check Yoast
				if ( ! $twitter_image && class_exists( 'WPSEO_OpenGraph' ) ) {
					$twitter_image = get_term_meta( $id, '_yoast_wpseo_twitter-image', true );
				}
				
			}
			
			$twitter_image = apply_filters( 'social_rocket_twitter_image', ( is_array( $twitter_image_full ) ? $twitter_image_full[0] : $twitter_image ) );
			
			if ( $twitter_image ) {
				echo '<meta name="twitter:image" content="', esc_attr( $twitter_image ), '" />' . "\n";
			}
			
		}
		
		echo '<!-- / Social Rocket -->' . "\n";
		
	}
	
	
	/**
	 * Maybe disables conflicting sources of og tags/twitter cards.
	 *
	 * Checks if Social Rocket will be outputting og tags/twitter cards, and if so
	 * disables conflicting sources of other og tags/twitter cards.
	 *
	 * @since 1.1.0
	 */
	public function maybe_disable_conflicting_og_tags() {
		
		if ( ! $this->_isset( $this->settings['disable_og_tags'] ) ) {
			
			// Jetpack
			add_filter( 'jetpack_enable_open_graph', '__return_false' );
			
		}
		
		if ( ! $this->_isset( $this->settings['disable_twitter_cards'] ) ) {
		
			// Jetpack
			add_filter( 'jetpack_disable_twitter_cards', '__return_true' );
			
		}
		
	}
	
	
	public function maybe_insert_floating_buttons() {
		
		// if we're loading on an AMP page, stop here (our styles won't be available anyway)
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return;
		}
		
		// if this is a "non-page page", stop here
		if ( is_404() || is_attachment() || is_feed() || is_search() ) {
			return;
		}
		
		/*
		 * Define hooks/filters for various locations
		 */
		
		$theme_locations = array(
			'left' => array(
				array(
					'hook'     => 'wp_footer',
					'filter'   => false,
					'priority' => 10,
				),
			),
			'right' => array(
				array(
					'hook'     => 'wp_footer',
					'filter'   => false,
					'priority' => 10,
				),
			),
			'top' => array(
				array(
					'hook'     => 'wp_footer',
					'filter'   => false,
					'priority' => 10,
				),
			),
			'bottom' => array(
				array(
					'hook'     => 'wp_footer',
					'filter'   => false,
					'priority' => 10,
				),
			),
		);
		
		$theme_locations = apply_filters( 'social_rocket_floating_theme_locations', $theme_locations );

		
		// Where are we loading right now?
		$loc  = $this->where_we_at();
		$id   = $loc['id'];
		$type = $loc['type'];
		$url  = $loc['url'];
		
		
		/*
		 * Determine desktop (i.e. default) placement settings for our current location
		 */
		
		// 1) start with appropriate default
		$placement = $this->settings['floating_buttons']['default_position'];
		
		// 2) check for any post_type- or archive-specific override
		if ( $this->_isset( $this->settings['floating_buttons']['position_'.$loc['settings_key']], 'default' ) !== 'default' ) {
			$placement = $this->settings['floating_buttons']['position_'.$loc['settings_key']];
		}
		
		// 3) check for post-specific override
		if ( $type === 'post' ) {
			$placement_override = get_post_meta( $id, 'social_rocket_floating_position', true );
			if ( $placement_override > '' ) {
				$placement = $placement_override;
			}
		}
		
		// 4) apply filter
		$placement = apply_filters( 'social_rocket_floating_buttons_placement', $placement, $id, $type, $url );
		
		
		/*
		 * Determine mobile placement settings for our current location
		 */
		
		// 1) start with global setting
		if ( $this->settings['floating_mobile_setting'] === 'default' ) {
			$placement_mobile = 'on'; // same as desktop
		} else {
			$placement_mobile = 'none';
		}
		
		// 4) apply filter
		$placement_mobile = apply_filters( 'social_rocket_floating_buttons_mobile_placement', $placement_mobile, $id, $type, $url );
		
		
		/*
		 * put it all together
		 */
		$inserts = array();
		if ( in_array( $placement, array( 'left', 'right', 'top', 'bottom' ) ) ) {
			if ( $placement_mobile === $placement || $placement_mobile === 'on' ) {
				$inserts[] = array( 'where' => $placement, 'what' => 'all' );
			} else {
				$inserts[] = array( 'where' => $placement, 'what' => 'desktop-only' );
			}
		}
		if ( in_array( $placement_mobile, array( 'left', 'right', 'top', 'bottom' ) ) ) {
			if ( $placement !== $placement_mobile ) {
				$inserts[] = array( 'where' => $placement_mobile, 'what' => 'mobile-only' );
			}
		}
		
		$inserts = apply_filters( 'social_rocket_floating_buttons_inserts', $inserts, $id, $type, $url );
		
		
		/*
		 * now add our hooks
		 */
		
		foreach ( $inserts as $insert ) {
		
			$where = $insert['where'];
			
			// backwards compatibility for Social Rocket <= 1.0.1
			if ( isset( $theme_locations[$where]['hook'] ) ) {
				$theme_locations[$where] = array( $theme_locations[$where] );
			}
			
			// add hook
			if ( isset( $theme_locations[$where] ) ) {
				foreach ( $theme_locations[$where] as $theme_location ) {
					if ( $theme_location['hook'] ) {
						add_action( $theme_location['hook'], array( $this, 'insert_floating_buttons_'.$where.'_content' ), $theme_location['priority'] );
					} elseif ( $theme_location['filter'] ) {
						add_filter( $theme_location['filter'], array( $this, 'insert_floating_buttons_'.$where.'_content_filter' ), $theme_location['priority'] );
					}
				}
			}
			
			// save this so we know which classes to add where
			if ( $insert['what'] !== 'all' ) {
				$this->data['floating_'.$where.'_add_class'] = $insert['what'];
			}
			
			// save this so we know if we need to enqueue CSS with an extra selector
			if ( $insert['what'] === 'desktop-only' || ( is_array( $insert['what'] ) && in_array( 'desktop-only', $insert['what'] ) ) ) {
				$this->data['floating_css_extra_selector'] = '.social-rocket-floating-buttons.social-rocket-desktop-only';
			}
			
		}
		
		if ( ! is_singular() ) {
			// save where we're at now, in case we end up in a loop later (i.e. archives)
			$this->data['floating_archive_id']   = $id;
			$this->data['floating_archive_type'] = $type;
		}
		
	}
	
	
	public function maybe_insert_inline_buttons() {
		
		// if we're loading on an AMP page, stop here (our styles won't be available anyway)
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return;
		}
		
		// if this is a "non-page page", stop here (search results get special handling for inline buttons...)
		if ( is_404() || is_attachment() || is_feed() ) {
			return;
		}
		
		
		// Where are we loading right now?
		$loc  = $this->where_we_at();
		$id   = $loc['id'];
		$type = $loc['type'];
		$url  = $loc['url'];
		
		/*
		 * Define hooks/filters for various locations
		 */
		
		if ( is_singular() ) {
		
			/*
			 * Individual posts, pages, CPTs
			 */
		
			// Genesis Hooks
			if ( 'genesis' === get_template() ) {

				$theme_locations = array(
					'before' => array(
						array(
							'hook'     => 'genesis_entry_header',
							'filter'   => false,
							'priority' => 13,
						),
						array(
							'hook'     => false,
							'filter'   => 'the_content', // fallback
							'priority' => 10,
						),
					),
					'after'  => array(
						array(
							'hook'     => 'genesis_entry_footer',
							'filter'   => false,
							'priority' => 8,
						),
						array(
							'hook'     => false,
							'filter'   => 'the_content', // fallback
							'priority' => 10,
						),
					),
				);

			// Theme Hook Alliance
			} elseif ( current_theme_supports( 'tha_hooks', array( 'entry' ) ) ) {

				$theme_locations = array(
					'before' => array(
						array(
							'hook'     => 'tha_entry_top',
							'filter'   => false,
							'priority' => 13,
						),
					),
					'after'  => array(
						array(
							'hook'     => 'tha_entry_bottom',
							'filter'   => false,
							'priority' => 8,
						),
					),
				);

			// Otherwise, 'the_content'
			} else {

				$theme_locations = array(
					'before' => array(
						array(
							'hook'     => false,
							'filter'   => 'the_content',
							'priority' => 11,
						),
					),
					'after'  => array(
						array(
							'hook'     => false,
							'filter'   => 'the_content',
							'priority' => 12,
						),
					),
				);
			}
			
			// extra check for MyBookTable plugin
			if ( is_singular('mbt_book') ) {
			
				$theme_locations = array(
					'before' => array(
						array(
							'hook'     => false,
							'filter'   => 'the_content',
							'priority' => 1000,
						),
					),
					'after'  => array(
						array(
							'hook'     => false,
							'filter'   => 'the_content',
							'priority' => 1000,
						),
					),
				);
				
			}
			
			// extra check for Thrive Architect (page builder)
			if ( defined( 'TVE_IN_ARCHITECT' ) && TVE_IN_ARCHITECT ) {
				if (
					get_post_meta( $id, 'tcb2_ready', true ) && 
					get_post_meta( $id, 'tcb_editor_enabled', true )
				) {
					if ( get_post_meta( $id, 'tve_landing_page', true ) ) {
						$theme_locations = array(
							'before' => array(
								array(
									'hook'     => false,
									'filter'   => 'tve_landing_page_content',
									'priority' => 10,
								),
							),
							'after'  => array(
								array(
									'hook'     => false,
									'filter'   => 'tve_landing_page_content',
									'priority' => 10,
								),
							),
						);
					} else {
						$theme_locations = array(
							'before' => array(
								array(
									'hook'     => false,
									'filter'   => 'the_content',
									'priority' => 10,
								),
							),
							'after'  => array(
								array(
									'hook'     => false,
									'filter'   => 'the_content',
									'priority' => 10,
								),
							),
						);
					}
				}
			}
		
		} else {
		
			/*
			 * Archives
			 */
			
			// On search results, only show if the archives setting is "item"
			if ( is_search() ) {
			
				$theme_locations = array(
					'item'  => array(
						array(
							'hook'     => false,
							'filter'   => defined( 'DOING_AJAX' ) && DOING_AJAX ? // infinite scroll
											'the_content' :
											'the_excerpt',
							'priority' => 12,
						),
					),
				);
			
			// all other archives...
			} else {
			
				$theme_locations = array(
					'before' => array(
						array(
							'hook'        => 'loop_start',
							'filter'      => false,
							'priority'    => 8,
							'html_before' => '<div class="entry"><div class="entry-content">',
							'html_after'  => '</div></div>',
						),
					),
					'after'  => array(
						array(
							'hook'        => 'loop_end',
							'filter'      => false,
							'priority'    => 12,
							'html_before' => '<div class="entry"><div class="entry-content">',
							'html_after'  => '</div></div>',
						),
					),
					'item'  => array(
						array(
							'hook'     => false,
							'filter'   => defined( 'DOING_AJAX' ) && DOING_AJAX ? // infinite scroll
											'the_content' :
											'the_excerpt',
							'priority' => 12,
						),
						array(
							'hook'     => false,
							'filter'   => 'the_content',
							'priority' => 12,
						),
					),
				);
				
			}
			
		}
		
		$theme_locations = apply_filters( 'social_rocket_inline_theme_locations', $theme_locations );
		
		/*
		 * Determine desktop (i.e. default) placement settings for our current location
		 */
		
		// 1) start with appropriate default
		$placement = is_singular() ? 
			$this->settings['inline_buttons']['default_position'] :
			$this->settings['inline_buttons']['default_archive_position'];
			
		// 2) check for any post_type- or archive-specific override
		if ( $this->_isset( $this->settings['inline_buttons']['position_'.$loc['settings_key']], 'default' ) !== 'default' ) {
			$placement = $this->settings['inline_buttons']['position_'.$loc['settings_key']];
		}
		
		// 3) check for post-specific override
		if ( $type === 'post' ) {
			$placement_override = get_post_meta( $id, 'social_rocket_inline_position', true );
			if ( $placement_override > '' ) {
				$placement = $placement_override;
			}
		}
		
		// 4) apply filter
		$placement = apply_filters( 'social_rocket_inline_buttons_placement', $placement, $id, $type, $url );
		
		
		/*
		 * Determine mobile placement settings for our current location
		 */
		
		// 1) start with global setting
		if ( $this->settings['inline_mobile_setting'] === 'default' ) {
			$placement_mobile = 'on'; // same as desktop
		} else {
			$placement_mobile = 'none';
		}
		
		// 2) apply filter
		$placement_mobile = apply_filters( 'social_rocket_inline_buttons_mobile_placement', $placement_mobile, $id, $type, $url );
		
		
		/*
		 * put it all together
		 */
		$inserts = array();
		if ( $placement === 'above' || $placement === 'both' ) {
			if ( in_array( $placement_mobile, array( 'on', 'above', 'both' ) ) ) {
				$inserts[] = array( 'where' => 'before', 'what' => 'all' );
			} else {
				$inserts[] = array( 'where' => 'before', 'what' => 'desktop-only' );
			}
		}
		if ( $placement === 'below' || $placement === 'both' ) {
			if ( in_array( $placement_mobile, array( 'on', 'below', 'both' ) ) ) {
				$inserts[] = array( 'where' => 'after', 'what' => 'all' );
			} else {
				$inserts[] = array( 'where' => 'after', 'what' => 'desktop-only' );
			}
		}
		if ( $placement === 'item' ) {
			if ( in_array( $placement_mobile, array( 'on', 'item' ) ) ) {
				$inserts[] = array( 'where' => 'item', 'what' => 'all' );
			} else {
				$inserts[] = array( 'where' => 'item', 'what' => 'desktop-only' );
			}
		}
		if ( $placement_mobile === 'above' || $placement_mobile === 'both' ) {
			if ( ! in_array( $placement, array( 'above', 'both' ) ) ) {
				$inserts[] = array( 'where' => 'before', 'what' => 'mobile-only' );
			}
		}
		if ( $placement_mobile === 'below' || $placement_mobile === 'both' ) {
			if ( ! in_array( $placement, array( 'below', 'both' ) ) ) {
				$inserts[] = array( 'where' => 'after', 'what' => 'mobile-only' );
			}
		}
		if ( $placement_mobile === 'item' ) {
			if ( $placement_mobile !== $placement ) {
				$inserts[] = array( 'where' => 'item', 'what' => 'mobile-only' );
			}
		}
		
		$inserts = apply_filters( 'social_rocket_inline_buttons_inserts', $inserts, $id, $type, $url );
		
		
		/*
		 * now add our hooks
		 */
		
		foreach ( $inserts as $insert ) {
		
			$where = $insert['where'];
			
			// backwards compatibility for Social Rocket <= 1.0.1
			if ( isset( $theme_locations[$where]['hook'] ) ) {
				$theme_locations[$where] = array( $theme_locations[$where] );
			}
			
			// add hook
			if ( isset( $theme_locations[$where] ) ) {
				foreach ( $theme_locations[$where] as $theme_location ) {
					if ( $theme_location['hook'] ) {
						add_action( $theme_location['hook'], array( $this, 'insert_inline_buttons_'.$where.'_content' ), $theme_location['priority'] );
					} elseif ( $theme_location['filter'] ) {
						add_filter( $theme_location['filter'], array( $this, 'insert_inline_buttons_'.$where.'_content_filter' ), $theme_location['priority'] );
					}
				}
			}
			
			// save this so we know which classes to add where
			if ( $insert['what'] !== 'all' ) {
				$this->data['inline_'.$where.'_add_class'] = $insert['what'];
			}
			
			// save this so we know if we need to enqueue CSS with an extra selector
			if ( $insert['what'] === 'desktop-only' || ( is_array( $insert['what'] ) && in_array( 'desktop-only', $insert['what'] ) ) ) {
				$this->data['inline_css_extra_selector'] = '.social-rocket-inline-buttons.social-rocket-desktop-only';
			}
			
		}
		
		if ( ! is_singular() ) {
			// save where we're at now, in case we end up in a loop later (i.e. archives)
			$this->data['inline_archive_id']          = $id;
			$this->data['inline_archive_type']        = $type;
			$this->data['inline_archive_html_before'] = $theme_locations['before'][0]['html_before'];
			$this->data['inline_archive_html_after']  = $theme_locations['before'][0]['html_after'];
		}
		
	}
	
	
	public function maybe_update_share_counts( $id = 0, $type = 'post', $url = '', $force = false ) {
		
		if ( ! $id ) {
			$loc  = $this->where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		$data = $this->get_count_data( $id, $type );
		
		if ( ! $force && $this->is_non_countable_request() ) {
			// don't get counts for admin pages
			return $data;
		}
		
		$now  = current_time( 'timestamp', 1 );
		
		foreach ( $this->settings['active_networks'] as $network ) {
			
			if ( isset( $data['networks'][$network]['last_updated'] ) ) {
				$last_update = strtotime( str_replace( ' ', 'T', $data['networks'][$network]['last_updated'] ).'+00:00' );
			} else {
				$last_update = 0;
			}
			
			if (
				$force || 
				$now - $last_update > $this->settings['refresh_interval']
			) {
		
				$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
				if ( isset( $SRN->countable ) && $SRN->countable ) {
					$this->background_processor->push_to_queue(
						md5( $url . '|' . $network ),
						array(
							'task'    => 'update_share_count',
							'network' => $network,
							'id'      => $id,
							'type'    => $type,
							'url'     => $url,
							'force'   => $force,
						) 
					);
				}
				
			}
			
		}
		
		return $data;
	
	}
	
	
	public function recalc_all_share_counts() {
		
		global $wpdb;
		
		$table_prefix = $wpdb->prefix;
		$result = $wpdb->query( "TRUNCATE {$table_prefix}social_rocket_count_data" );
		
		do_action( 'social_rocket_cron' ); // this will get some updates rolling
		
		return $result;
	}
	
	
	public function recalc_share_counts( $id = 0, $type = 'post', $url = '' ) {
		
		global $wpdb;
		
		if ( ! $id ) {
			$loc  = Social_Rocket::where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		
		/*
		if ( $type === 'post' ) {
			$where = 'post_id';
		} elseif ( $type === 'term' ) {
			$where = 'term_id';
		} elseif ( $type === 'user' ) {
			$where = 'user_id';
		} elseif ( $type === 'url' ) {
			$where = 'url';
		}
		
		$table_prefix = $wpdb->prefix;
		$result = $wpdb->delete(
			"{$table_prefix}social_rocket_count_data",
			array(
				$where => $id,
			)
		);
		*/
		
		$this->maybe_update_share_counts( $id, $type, $url, true );
		
		return true;
	}
	
	
	public function register_blocks() {

		if ( ! function_exists( 'register_block_type' ) ) {
			// Gutenberg is not active.
			return;
		}
		
		wp_register_script(
			'social-rocket-blocks',
			plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/blocks.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
			filemtime( SOCIAL_ROCKET_PATH . 'assets/js/blocks.js' )
		);

		// socialrocket (inline buttons)
		register_block_type( 'social-rocket/socialrocket', array(
			'attributes'      => array(
				'force_css'     => array(
					'type'        => 'string',
					'default'     => 'true',
				),
				'heading'       => array(
					'type'        => 'string',
				),
				'id'            => array(
					'type'        => 'string',
				),
				'networks'      => array(
					'type'        => 'array',
					'default'     => array(),
					'items'       => array(
						'type'      => 'string',
					),
				),
				'_networks_toggle' => array(
					'type'           => 'string',
					'default'        => 'default',
				),
				'share_url'     => array(
					'type'        => 'string',
				),
				'show_counts'   => array(
					'type'        => 'string',
				),
				'show_total'    => array(
					'type'        => 'string',
				),
				/* TODO: idea for a later version
				'social_media_title' => array(
					'type' => 'string',
				),
				'social_media_description' => array(
					'type' => 'string',
				),
				*/
				'type'          => array(
					'type'        => 'string',
				),
			),
			'editor_script'   => 'social-rocket-blocks',
			'render_callback' => 'social_rocket_shortcode',
		) );
		
		// socialrocket-floating (floating buttons)
		// register_block_type( 'social-rocket/socialrocket-floating', array(
			// 'editor_script' => 'social-rocket-blocks',
			// 'render_callback' => 'social_rocket_floating_shortcode',
		// ) );
		
		// socialrocket-tweet (click to tweet)
		register_block_type( 'social-rocket/socialrocket-tweet', array(
			'attributes'      => array(
				'add_class'     => array(
					'type'        => 'string',
				),
				'force_css'     => array(
					'type'        => 'string',
					'default'     => 'true',
				),
				'include_url'   => array(
					'type'        => 'string',
					'default'     => $this->_isset( $this->settings['tweet_settings']['saved_settings']['default']['include_url'] ) ? 'true' : 'false',
				),
				'include_via'   => array(
					'type'        => 'string',
					'default'     => $this->_isset( $this->settings['tweet_settings']['saved_settings']['default']['include_via'] ) ? 'true' : 'false',
				),
				'quote'         => array(
					'type'        => 'string',
				),
				'style_id'      => array(
					'type'        => 'string',
				),
				'tweet'         => array(
					'type'        => 'string',
				),
				'url'           => array(
					'type'        => 'string',
				),
				'via'           => array(
					'type'        => 'string',
				),
			),
			'editor_script'   => 'social-rocket-blocks',
			'render_callback' => 'social_rocket_tweet_shortcode',
		) );

	}
	
	
	public function round( $count ) {
		
		$count = intval( $count );
		$dec = $this->settings['decimal_places'];
		$sep = $this->settings['decimal_separator'];
		
		if ( $count >= 1000000000 ) {
			$count = rtrim( rtrim( number_format( $count/1000000000, $dec, $sep, '' ), '0' ), $sep ) . __( 'B', 'social-rocket' ); // holy underwear!
		} elseif ( $count >= 1000000 ) {
			$count = rtrim( rtrim( number_format( $count/1000000, $dec, $sep, '' ), '0' ), $sep ) . __( 'M', 'social-rocket' );
		} elseif ( $count >= 1000 ) {
			$count = rtrim( rtrim( number_format( $count/1000, $dec, $sep, '' ), '0' ), $sep ) . __( 'K', 'social-rocket' );
		}
		
		return $count;
	}
	
	
	public static function update_count_data( $id = 0, $type = 'post', $data = null ) {
	
		global $wpdb;
		
		$SR = Social_Rocket::get_instance();
		
		if ( ! $id ) {
			$loc  = $SR->where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		if ( $type === 'post' ) {
			$where = 'post_id';
		} elseif ( $type === 'term' ) {
			$where = 'term_id';
		} elseif ( $type === 'user' ) {
			$where = 'user_id';
		} elseif ( $type === 'url' ) {
			$where = 'url';
		}
		
		$table_prefix = $wpdb->prefix;
		$exists = $wpdb->get_row(
			$wpdb->prepare( 
				"SELECT * FROM {$table_prefix}social_rocket_count_data WHERE {$where} = %s",
				$id
			),
			ARRAY_A
		);
		
		if ( $exists === null ) {
			$result = $wpdb->insert(
				"{$table_prefix}social_rocket_count_data",
				array(
					$where         => $id,
					'data'         => serialize( $data ),
					'last_updated' => current_time( 'mysql', 1 ),
				)
			);
		} else {
			$result = $wpdb->update(
				"{$table_prefix}social_rocket_count_data",
				array(
					'data' => serialize( $data ),
					'last_updated' => current_time( 'mysql', 1 ),
				),
				array(
					'id' => $exists['id'],
				)
			);
		}
		
		// cache total as post meta, for admin columns, sorting, etc.
		if ( $type === 'post' ) {
			$counts = apply_filters( 'social_rocket_get_share_counts', $data );
			$total = 0;
			foreach ( $SR->networks as $key => $value ) {
				if ( in_array( $key, $SR->settings['active_networks'] ) ) {
					if ( isset( $counts['networks'][$key] ) ) {
						$total = $total + $counts['networks'][$key]['total'];
					}
				}
			}
			update_post_meta( $id, 'social_rocket_total_shares', $total );
		}
		
		return $result;
		
	}
	
	
	public function update_share_count( $network, $id = 0, $type = 'post', $url = '', $force = false ) {
		
		global $srp_debug_message;
		
		if ( ! $id ) {
			$loc  = Social_Rocket::where_we_at();
			$id   = $loc['id'];
			$type = $loc['type'];
			$url  = $loc['url'];
		}
		
		$data  = $this->get_count_data( $id, $type, true );
		$now   = current_time( 'timestamp', 1 );
		$total = 0;
		
		if ( ! $force ) {
			// check timestamp again, just in case an update already happened between pushing the task to queue and now
			if ( isset( $data['networks'][$network]['last_updated'] ) ) {
				$last_update = strtotime( str_replace( ' ', 'T', $data['networks'][$network]['last_updated'] ).'+00:00' );
			} else {
				$last_update = 0;
			}	
			if ( $now - $last_update <= $this->settings['refresh_interval'] ) {
				return true;
			}
		}
		
		if ( method_exists( 'Social_Rocket_'.ucfirst($network), 'get_count_from_api' ) ) {
			$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
			$total = $SRN->get_count_from_api( $url );
			if ( defined( 'SRP_DEBUG' ) && SRP_DEBUG ) {
				$srp_debug_message = 'got from api: ' . var_export( $total, true );
			}
		}
		
		// if api error, stop here
		if ( $total === false ) {
			return false;
		}
		
		// update total only if greater than previous total		
		if ( isset( $data['networks'][$network]['total'] ) ) {
			if ( $total > $data['networks'][$network]['total'] ) {
				$data['networks'][$network]['total'] = $total;
			}
		} else {
			$data['networks'][$network]['total'] = $total;
		}
		$data['networks'][$network]['last_updated'] = current_time( 'mysql', 1 );
		
		// update stored data (will update last_updated value, if nothing else)
		$data = apply_filters( 'social_rocket_update_share_count', $data, $network, $id, $type, $url, $force );
		$this->update_count_data( $id, $type, $data );
		
		return true;
	
	}
	
	
	public static function validate_settings() {
		
		$SR = Social_Rocket::get_instance();
		
		if ( isset( $SR->settings['inline_buttons']['networks'] ) ) {
			foreach ( $SR->settings['inline_buttons']['networks'] as $key => $value ) {
				if ( ! isset( $SR->networks[ $key ] ) ) {
					unset( $SR->settings['inline_buttons']['networks'][$key] );
				}
			}
		}
		if ( isset( $SR->settings['floating_buttons']['networks'] ) ) {
			foreach ( $SR->settings['floating_buttons']['networks'] as $key => $value ) {
				if ( ! isset( $SR->networks[ $key ] ) ) {
					unset( $SR->settings['floating_buttons']['networks'][$key] );
				}
			}
		}
		if ( isset( $SR->settings['active_networks'] ) ) {
			foreach ( $SR->settings['active_networks'] as $i => $key ) {
				if ( ! isset( $SR->networks[ $key ] ) ) {
					unset( $SR->settings['active_networks'][$i] );
				}
			}
		}
		
	}
	
	
	public static function where_we_at() {
	
		// safely piece together current URL (without query string)
		// should work on just about all servers...
		$url = '';
		if ( isset( $_SERVER['HTTPS'] ) && filter_var( $_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN ) ) {
			$url .= 'https';
		} else {
			$url .= 'http';
		}
		$url .= '://';

		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$url .= $_SERVER['HTTP_HOST'];
		} elseif ( isset( $_SERVER['SERVER_NAME'] ) ) {
			$url .= $_SERVER['SERVER_NAME'];
		} else {
			trigger_error( 'Could not get URL from $_SERVER vars' );
		}

		if ( ! in_array( $_SERVER['SERVER_PORT'], array( '80', '8080', '443' ) ) ) {
			$url .= ':'.$_SERVER["SERVER_PORT"];
		}

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$url .= $_SERVER['REQUEST_URI'];
		} elseif ( isset( $_SERVER['PHP_SELF'] ) ) {
			$url .= $_SERVER['PHP_SELF'];
		} elseif ( isset( $_SERVER['REDIRECT_URL'] ) ) {
			$url .= $_SERVER['REDIRECT_URL'];
		} else {
			trigger_error( 'Could not get URL from $_SERVER vars' );
		}
		
		$url = explode( '?', $url );
		$url = $url[0];
		
		// now there is one and only one query argument we will put back, because
		// of WP's "plain" permalinks structure:
		if ( isset( $_REQUEST['p'] ) ) {
			$url = $url . '?p=' . $_REQUEST['p'];
		}
		
		// now figure out where we are within the WP universe...  
		$result = array();
		
		// determine 'id' and 'type'
		if ( in_the_loop() || is_page() || is_single() || is_attachment() ) {
			// catches pages, posts, and attachments
			// get_queried_object_id = ID in posts table
			$result['id']   = in_the_loop() ? get_the_ID() : get_queried_object_id();
			$result['type'] = 'post';
		} elseif ( is_home() || is_date() || is_post_type_archive() ) {
			// home page (must be blog index, or we would have caught it as a page),
			// or date-based archives, or CPT-based archives
			// get_queried_object_id = always 0
			$result['id']   = $url;
			$result['type'] = 'url';
		} elseif ( is_author() ) {
			// author archives
			// get_queried_object_id = ID in users table
			$result['id']   = in_the_loop() ? get_the_ID() : get_queried_object_id();
			$result['type'] = 'user';
		} elseif ( is_archive() ) {
			// if we are here, the only types of archives left to account for
			// are category, tag, and taxonomy archives
			// get_queried_object_id = ID in terms table
			$result['id']   = in_the_loop() ? get_the_ID() : get_queried_object_id();
			$result['type'] = 'term';
		} else {
			// if all else fails, just use the URL
			$result['id']   = $url;
			$result['type'] = 'url';
		}
		
		// determine 'url'
		$result['url'] = in_the_loop() ? get_the_permalink() : $url;
		
		// consider paged requests as requests for original url (page 1)
		if ( apply_filters( 'social_rocket_archives_url_use_first_page', true ) ) {
			if ( is_home() || is_date() || is_post_type_archive() || is_author() || is_archive() ) {
				$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				if ( $page > 1 ) {
					$url = get_pagenum_link( 1 );
					$result['url'] = $url;
					if ( $result['type'] === 'url' ) {
						$result['id'] = $url;
					}
				}
			}
		}
		
		// map where we are to the correct settings key (used in Button Placement settings)
		if ( in_the_loop() || is_page() || is_single() || is_attachment() ) {
			$result['settings_key'] = 'post_type_' . get_post_type();
		} elseif ( is_home() ) {
			$result['settings_key'] = 'archive_WP_home';
		} elseif ( is_author() ) {
			$result['settings_key'] = 'archive_WP_author';
		} elseif ( is_category() ) {
			$result['settings_key'] = 'archive_WP_category';
		} elseif ( is_date() ) {
			$result['settings_key'] = 'archive_WP_date';
		} elseif ( is_tag() ) {
			$result['settings_key'] = 'archive_WP_tag';
		} elseif ( is_post_type_archive() ) {
			$result['settings_key'] = 'archive_CPT_' . get_post_type();
		} elseif ( is_tax() ) {
			$result['settings_key'] = 'archive_TAX_' . get_query_var( 'taxonomy' );
		}
		
		// lastly validate our result, in case we are given something screwy
		if ( ! $result['id'] > 0 && ! $result['id'] > '' ) {
			// we don't have a valid ID.  Fallback to URL.
			$result['id']   = $url;
			$result['type'] = 'url';
		}
		
		return $result;
	}
	
	
}
