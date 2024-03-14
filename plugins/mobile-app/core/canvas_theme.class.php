<?php

/*
* Some of the code for theme switching is a derivative work of the code from the Apppresser plugin,
* which is licensed GPLv2. This code is also licensed under the terms of the GNU Public License, verison 2.
*/


defined( 'CANVAS_URL' ) || die();

class CanvasTheme extends Canvas {


	public $original_template   = null;
	public $original_stylesheet = null;

	public $canvas_theme               = false;
	protected $is_theme_customizer_now = false;

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ), self::PRIORITY );
		if ( Canvas::is_request_from_application() ) {

			add_action( 'wp_head', array( &$this, 'on_head' ) );

			header( 'ml_available: true' );
			// Add ml_username header for requests from application
			$user = get_current_user_id();
			if ( ! empty( $user ) ) {
				header( 'ml_username: ' . self::get_username() );
			}
		}
	}

	/**
	 * Switch theme for Canvas mobile app
	 */
	public function on_plugins_loaded() {
		// Do not switch default theme for admin view if it is not customizing
		if ( is_admin() && ! apply_filters( 'canvas_custom_request', $this->is_request_from_theme_customizer() ) ) {
			return;
		}

		if ( Canvas::is_request_from_application() || $this->is_request_from_theme_customizer() ) {
			if ( Canvas::get_option( 'wpadminbar_hide' ) ) {
				add_filter( 'show_admin_bar', '__return_false' );
			}
			// Switch theme for Canvas application or for theme settings
			if ( Canvas::get_option( self::THEME_OPTION ) ) {
				$this->canvas_theme = wp_get_theme( Canvas::get_option( self::THEME_OPTION ) );

				add_filter( 'option_template', array( $this, 'on_template_request' ), 5 );
				add_filter( 'option_stylesheet', array( $this, 'on_stylesheet_request' ), 5 );
				add_filter( 'template', array( $this, 'on_template' ) );
			}
			if ( Canvas::identify_app_by_get_param() ) {
				add_filter( 'wp_footer', array( $this, 'on_footer' ), 1000 );
			}
		}
	}

	/**
	 * Check and switch template hook
	 *
	 * @param mixed $template
	 */
	public function on_template_request( $template ) {
		// Cache our original template request
		if ( is_null( $this->original_template ) ) {
			$this->original_template = $template;
		}

		return $this->check_and_switch_template( $template );
	}

	/**
	 * Check and switch stylesheet hook
	 *
	 * @param mixed $stylesheet
	 */
	public function on_stylesheet_request( $stylesheet ) {
		if ( is_null( $this->original_stylesheet ) ) {
			$this->original_stylesheet = $stylesheet;
		}

		return $this->check_and_switch_stylesheet( $stylesheet );
	}

	/**
	 * Check and switch template or stylesheet hook
	 *
	 * @param mixed $template
	 */
	public function on_template( $template = '', $stylesheet_request = false ) {
		return $stylesheet_request ? $this->check_and_switch_stylesheet( $template ) : $this->check_and_switch_template( $template );
	}

	/**
	 * Check and switch stylesheet
	 *
	 * @param mixed $stylesheet
	 */
	protected function check_and_switch_stylesheet( $stylesheet = '' ) {
		// No need to switch, return original or default stylesheet
		if ( ! $this->canvas_theme ) {
			return ! empty( $stylesheet ) ? $stylesheet : $this->original_stylesheet;
		}

		// New stylesheet
		return Canvas::get_option( self::THEME_OPTION );
	}

	/**
	 * Check and switch template
	 *
	 * @param mixed $template
	 */
	protected function check_and_switch_template( $template = '' ) {
		// No need to switch, return original or default template
		if ( ! $this->canvas_theme ) {
			return ! empty( $template ) ? $template : $this->original_template;
		}
		// New template
		return $this->canvas_theme->get_template();
	}

	/**
	 * Source of request is one of theme customizer pages
	 */
	public function is_request_from_theme_customizer() {
		// Cached result
		if ( isset( $this->is_theme_customizer_now ) ) {
			return $this->is_theme_customizer_now;
		}

		// Check if we are in the Canvas theme customizer
		$this->is_theme_customizer_now = isset( $_GET[ self::$slug_theme ] ) && isset( $_GET['theme'] )
		// or if it's an AJAX request
		|| ( isset( $_REQUEST['wp_customize'] ) && isset( $_REQUEST['theme'] ) && Canvas::get_option( self::THEME_OPTION, '' ) == $_REQUEST['theme'] );

		return $this->is_theme_customizer_now;
	}


	/**
	 * Create unique user ID
	 */
	public static function get_username() {
		$user_id = get_current_user_id();
		if ( ! empty( $user_id ) ) {
			$selected_mode = Canvas::get_option( 'user_profile', 'user_id' );
				$result    = get_user_option( 'canvas-username', $user_id );
			if ( empty( $result ) ) {
				$chars  = 'abcdefghijklmnopqrstuvwxyz0123456789';
				$result = '';
				for ( $i = 0; $i < 32; $i++ ) {
					$result .= substr( $chars, wp_rand( 0, strlen( $chars ) - 1 ), 1 );
				}
				update_user_option( $user_id, 'canvas-username', $result );
			} elseif ( false !== strpos( $result, '@' ) ) {
				$result = substr( $result, 0, strpos( $result, '@' ) );
				update_user_option( $user_id, 'canvas-username', $result );
			}
			if ( 'user_email' === $selected_mode ) {
				$user_info = get_userdata( $user_id );
				if ( ! empty( $user_info->user_email ) ) {
					return $user_info->user_email;
				}
			}
			return $result;
		} else {
			return '';
		}
	}

	/**
	 * Add custom CSS only when the site is loaded in the Canvas app
	 */
	public function on_head() {
		$custom_css = stripslashes( get_option( 'canvas_editor_css', '' ) );
		if ( ! empty( $custom_css ) ) {
			?>
		<style type="text/css" media="screen"><?php echo $custom_css; ?></style>
														 <?php
		}
	}

	/**
	* Append "?app=true" to all links at the page.
	*
	* @since 3.5.3
	*
	*/
	public function on_footer() {
		?>
		<script type="text/javascript">
			// Append "app=true" to links at the page.
			(function(){
				var hostname = document.location.hostname;
				function ml_update_app_link( link ) {
					try {
						if ( link.hostname === hostname ) {
							var search = link.search;
							if ( -1 === search.search( /[\?&]app=true/ ) ) {
								search = ( '' === search ) ? '?app=true' : search + '&app=true';
								link.search = search;
								link.setAttribute( 'href',link.href );
							}
						}
					} catch (e) {
						console.log(e);
					}
				}

				function ml_update_app_links() {
					var l = document.links;
					for ( var i=0; i<l.length; i++ ) {
						ml_update_app_link( l[i] );
					}
				};
				document.addEventListener( "DOMContentLoaded", ml_update_app_links );
				document.addEventListener( "load", ml_update_app_links );

				var ml_MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
				if ( 'undefined' !== typeof ml_MutationObserver ) {
					try {
						var observer = new ml_MutationObserver(function(mutations) {
							for ( let mutation of mutations ) {

								for( let node of mutation.addedNodes ) {
									if ( ! ( node instanceof HTMLElement ) ) continue;
									if ( node.matches( 'a[href]' ) ) {
										ml_update_app_links( node );
									}
									for( let elem of node.querySelectorAll( 'a[href]' ) ) {
										ml_update_app_links( elem );
									}
								}
							}
						});
						var container = document.documentElement || document.body;
						observer.observe( container, { childList: true, subtree: true } );
					} catch ( e ) {
						console.log( e );
					}
				}
			})();
		</script>
		<?php

	}

}
