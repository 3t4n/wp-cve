<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/public
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 * @since      1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @property array|null  settings
 * @property string|null app_id
 * @category   PHP
 * @package    Free_Comments_For_Wordpress_Vuukle
 * @subpackage Free_Comments_For_Wordpress_Vuukle/public
 * @author     Vuukle <info@vuukle.com>
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 * @link       https://vuukle.com
 */
class Free_Comments_For_Wordpress_Vuukle_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin all needed properties in one place
	 *
	 * @since  5.0
	 * @access protected
	 * @var    array $attributes The array containing main attributes of the plugin.
	 */
	protected $attributes;

	/**
	 * Main settings option name
	 *
	 * @since  5.0
	 * @access protected
	 * @var    string $settings_name Main settings option_name
	 */
	protected $settings_name;

	/**
	 * Main settings option name
	 *
	 * @since  5.0
	 * @access protected
	 * @var    string $settings_name Main settings option_name
	 */
	protected $app_id_setting_name;

	/**
	 * Vuukle availability in the content upon single request
	 *
	 * @since  5.0
	 * @access protected
	 * @var    bool $availability holds flag about vuukle availability in the content
	 */
	protected $availability;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @var array $attributes The array containing main attributes of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $attributes ) {
		$this->attributes          = $attributes;
		$this->plugin_name         = $this->attributes['name'];
		$this->version             = $this->attributes['version'];
		$this->settings_name       = $this->attributes['settings_name'];
		$this->app_id_setting_name = $this->attributes['app_id_setting_name'];
	}

	/**
	 * Magic method especially designed for getting the main settings array or app_id
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		if ( $name == 'settings' ) {
			if ( empty( $this->$name ) ) {
				$default_settings = Free_Comments_For_Wordpress_Vuukle_Helper::getDefaultSettings();
				$settings         = get_option( $this->settings_name );
				if ( empty( $settings ) ) {
					$settings = $default_settings;
				}
				if ( is_array( $settings ) ) {
					$settings    = array_merge( $default_settings, $settings );
					$settings    = array_intersect_key( $settings, $default_settings );
					$this->$name = $settings;
				} else {
					$this->$name = [];
				}
			}

			return $this->$name;
		} elseif ( $name == 'app_id' ) {
			$this->$name = get_option( $this->app_id_setting_name );

			return $this->$name;
		} else {
			return null;
		}
	}

	/**
	 * Magic method designed for setting settings array or app id
	 *
	 * @param $name
	 * @param $value
	 */
	public function __set( $name, $value ) {
		if ( $name == 'settings' || $name == 'app_id' ) {
			$this->$name = $value;
		}
	}

	/**
	 * @param $open
	 *
	 * @return false
	 */
	public function commentsOpen( $open ) {
		if ( $this->settings['enabled_comments'] == 'true') {
			return false;
		}

		return $open;
	}

	/**
     * Disable comments list ( this won't disable the section. For example the navigation will still present here )
     * 
	 * @param array $parsed_args
	 *
	 * @return array
	 */
	public function listCommentsArgs( $parsed_args ) {
		if ( $this->settings['enabled_comments'] == 'true') {
			$parsed_args['echo'] = false;
		}

		return $parsed_args;
	}

	/**
	 * @param $open
	 *
	 * @return false
	 */
	public function pingsOpen( $open ) {
		if ( $this->settings['enabled_comments'] == 'true' ) {
			return false;
		}

		return $open;
	}

	/**
     * Hide comments section
	 */
	public function hideComments() {
		if ( $this->settings['enabled_comments'] == 'true') {
		    ?>
            <style>
                #comments {
                    display: none !important;
                }
            </style>
            <?php
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function enqueueStyles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Free_Comments_For_Wordpress_Vuukle_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Free_Comments_For_Wordpress_Vuukle_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @return void
	 * @since  1.0.0
	 */
	public function enqueueScripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Free_Comments_For_Wordpress_Vuukle_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Free_Comments_For_Wordpress_Vuukle_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( $this->app_id && $this->checkAvailability( false ) ) {
            // Load script here
		}
	}


	/**
	 * This function generates shortcode.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function generateShortcode() {
		add_shortcode( 'vuukle', array( $this, 'shortCode' ) );
	}

	/**
	 * This function creates a block for shortcode.
	 *
	 * @param   string  $attr     this is attributes
	 * @param   string  $content  this is content
	 *
	 * @return string
	 * @since  1.0.0.
	 */
	public function shortCode( $attr, $content ) {
		return '<div id="vuukle-comments"></div>';
	}

	/**
	 * This function adds DNS.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function addDns() {
		if ( $this->checkAvailability( false ) ):
			?>
            <link rel="preconnect" href="https://cdn.vuukle.com/">
            <link rel="dns-prefetch" href="https://cdn.vuukle.com/">
            <link rel="dns-prefetch" href="https://api.vuukle.com/">
            <link rel="preconnect" href="https://api.vuukle.com/">
		<?php
		endif;
	}

	/**
	 * This function adds scripts in the front.
	 *
	 * @param   string  $content  this is a content
	 *
	 * @return string
	 * @since  1.0.0.
	 */
	public function commentBox( $content ) {
		if ( $this->checkAvailability()
		     && $this->settings['enabled_comments'] == 'true'
		     && ( in_array( $this->settings['embed_comments'], [ 1, 2, 3, 4 ] ) ) ) {
			ob_start();
			global $post;
			$amp_src_url = Free_Comments_For_Wordpress_Vuukle_Helper::getAmpSrcUrl( $this->settings, $post, $this->app_id );
			$amp_host    = wp_parse_url( get_site_url() )['host'];
			include $this->attributes['public_partials_dir_path'] . $this->attributes['name'] . '-public-comment-box.php';
			$content .= ob_get_contents();
			ob_end_clean();
		}

		return $content;
	}

	/**
	 * Check vuukle availability for the current content
	 *
	 * @param   bool  $check_single  checks if needed to proceed is_single check
	 *
	 * @return bool
	 */
	public function checkAvailability( $check_single = true ) {
        // Check if is_single check is required
		if ( $check_single && ! is_single() ) {
			return false;
		}
		// Check if not found page
		if ( is_404() ) {
			return false;
		}
		// Retrieve global post
		global $post;
		// Check if it is valid WP_Post object
		if ( empty( $post ) || ! ( $post instanceof WP_Post ) ) {
			return false;
		}
		if ( is_null( $this->availability ) ) {
			if ( empty( $this->app_id ) ) {
				return false;
			}
			// Post exceptions
			if ( ! empty( $this->settings['post_exceptions'] ) ) {
				$post_exceptions = explode( ',', $this->settings['post_exceptions'] );
				$post_exceptions = array_map( 'trim', $post_exceptions );
				if ( in_array( (string) $post->ID, $post_exceptions, true ) ) {
					$this->availability = false;

					return $this->availability;
				}
			}
			// Category exceptions
			if ( ! empty( $this->settings['category_exceptions'] ) ) {
				$category_exceptions = explode( ',', $this->settings['category_exceptions'] );
				$category_exceptions = array_map( 'trim', $category_exceptions );
				$post_categories     = get_the_category( $post->ID );
				foreach ( $post_categories as $key => $category ) {
					$post_categories[ $key ] = $category->slug;
				}
				if ( count( array_intersect( $category_exceptions, $post_categories ) ) ) {
					$this->availability = false;

					return $this->availability;
				}
			}
			// Post type exceptions
			if ( ! empty( $this->settings['post_type_exceptions'] ) ) {
				$post_type_exceptions = explode( ',', $this->settings['post_type_exceptions'] );
				$post_type_exceptions = array_map( 'trim', $post_type_exceptions );
				if ( in_array( (string) $post->post_type, $post_type_exceptions, true ) ) {
					$this->availability = false;

					return $this->availability;
				}
			}
			// Post type by URL exceptions
			if ( ! empty( $this->settings['post_type_by_url_exceptions'] ) ) {
				$post_type_by_url_exceptions = explode( ',', $this->settings['post_type_by_url_exceptions'] );
				$post_type_by_url_exceptions = array_map( function ( $item ) {
					return trim( $item, ',/' );
				}, $post_type_by_url_exceptions );
				$link                        = str_replace( home_url(), '', get_permalink() );
				if ( in_array( trim( (string) $link, '/' ), $post_type_by_url_exceptions, true ) ) {
					$this->availability = false;

					return $this->availability;
				}
			}
			$this->availability = true;
		}

		return $this->availability;
	}


	/**
	 * This function adds emote.
	 *
	 * @param   string  $content  this is a content
	 *
	 * @return string
	 * @since  1.0.0.
	 */
	public function addEmote( $content ) {
		if ( $this->checkAvailability() && $this->settings['emote'] == 'true' ) {
			$style_emote = '';
			if ( $this->settings['emote_widget_width'] ) {
				$width       = $this->settings['emote_widget_width'];
				$style_emote = "style='max-width:" . $width . "px;min-height:160px;'";
			}
			$content .= '<div id="vuukle-emote" ' . $style_emote . ' class="emotesBoxDiv"></div>';
		}

		return $content;
	}

	/**
	 * All the logic related to showing share bar
	 *
	 * @param $content
	 *
	 * @return mixed|string
	 */
	public function addShareBar( $content ) {
		if ( ! $this->checkAvailability() || ! $this->settings['share'] ) {
			return $content;
		}
		$horizontal = false;
		$vertical   = false;
		$before     = false;
		$after      = false;
		if ( $this->settings['enable_h_v'] === 'yes' ) {
			/*
			 * Horizontal for mobile and vertical for desktop 
			 * For horizontal placement currently we will choose after content or will check share_position setting
			 * For vertical placement we need standard with styles
			 */
			$after      = true;
			$horizontal = wp_is_mobile();
			$vertical   = ! wp_is_mobile();
			$styles     = $this->settings['share_vertical_styles'];
			if ( $horizontal ) {
				if ( ! empty( $this->settings['share_position2'] ) ) {
					$before = true;
					if ( ! empty( $this->settings['share_position'] ) ) {
						$after = true;
					} else {
						$after = false;
					}
				}
			}
		} else {
			/**
			 * Check share bar type horizontal or vertical or both
			 */
			if ( ! empty( $this->settings['share_type'] ) ) {
				$horizontal = true;
				if ( ! empty( $this->settings['share_position'] ) ) {
					$after = true;
				}
				if ( ! empty( $this->settings['share_position2'] ) ) {
					$before = true;
				}
			}
			if ( ! empty( $this->settings['share_type_vertical'] ) ) {
				$vertical = true;
				$styles   = $this->settings['share_vertical_styles'];
			}
		}
		/**
		 * Place html in the content
		 * Check for vertical and horizontal, before and after , etc ...
		 */
		if ( ! empty( $horizontal ) ) {
			$html = '<div class="vuukle-powerbar powerbarBoxDiv" style="min-height: 50px;" data-styles=""></div>';
			if ( ! empty( $before ) ) {
				$content = $html . $content;
			}
			if ( ! empty( $after ) ) {
				$content = $content . $html;
			} else {
				/**
				 * Add horizontal after , even both options(after/before) are disabled
				 */
				if ( empty( $before ) ) {
					$content = $content . $html;
				}
			}
		}
		if ( ! empty( $vertical ) ) {
			$content = $content . '<div class="vuukle-powerbar-vertical powerbarBoxDiv" style="' . $styles . '" data-styles="' . $styles . '"></div>';
		}

		return $content;
	}

	/**
	 * This function registers recent comments' widget.
	 *
	 * @return void
	 * @since  1.0.0.
	 */
	public function registerRecentCommentsWidget() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'Recent_Comments_Widget' );
		unregister_widget( 'Most_Commented_Stories_Widget' );
	}

	/**
	 * This function creates a Vuukle platform.
	 *
	 * @since  1.0.0.
	 */
	public function createPlatform() {
		if ( $this->checkAvailability() ) {
			global $post;
			ob_start();
			include $this->attributes['public_partials_dir_path'] . $this->attributes['name'] . '-public-platform.php';
			echo ob_get_clean();
		}
	}

	/**
	 * Include the vuukle platform with disabled services in all content
	 * except areas where we have disabled availability
	 */
	public function track_page_view() {
		if ( isset( $this->settings['non_article_pages'] ) && $this->settings['non_article_pages'] != 'off' ) {
			if ( ! is_single() && ! empty( $this->app_id ) && $this->checkAvailability( false ) ) {
				echo "<script data-cfasync=\"false\">
                    var VUUKLE_CONFIG = {
                        apiKey: '" . $this->app_id . "',
                        articleId: '1',
                        comments: {enabled: false},
                        emotes: {'enabled': false},
                        powerbar: {'enabled': false},
                        ads:{noDefaults: true}
                    };
                </script>
				<script src=\"https://cdn.vuukle.com/platform.js\" data-cfasync=\"false\" async></script>";
			}
		}
	}
}
