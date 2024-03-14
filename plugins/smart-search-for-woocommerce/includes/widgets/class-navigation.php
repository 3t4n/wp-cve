<?php
/**
 * Searchanise Smart Navigation
 *
 * @package Searchanise/Navigation
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise smart navigation class
 */
class Navigation {

	/**
	 * Lang code
	 *
	 * @var string
	 */
	protected $lang_code;

	/**
	 * SmartNavigation constructor
	 *
	 * @param string $lang_code Lang code.
	 */
	public function __construct( $lang_code ) {
		$this->lang_code = $lang_code;

		if ( Api::get_instance()->is_navigation_enabled( $lang_code ) ) {
			$this->init();
		}
	}

	/**
	 * Class init
	 */
	public function init() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		add_filter( 'template_include', array( $this, 'template_include' ), 99 );
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
	}

	/**
	 * Template include filter
	 *
	 * @param string $template Template.
	 */
	public function template_include( $template ) {
		if ( $this->is_navigation_page() ) {
			wc_enqueue_js(
				<<<SCRIPT
            (function(window, undefined) {
                var sXpos = 0, sIndex = 0, sTotalFrames = 12, sInterval = null;
        
                if (document.getElementById('snize_results').innerHTML != '') {
                    return;
                }
        
                document.getElementById('snize_results').innerHTML = '<div id="snize-preload-spinner"></div>';
                sInterval = setInterval(function()
                {
                    var spinner = document.getElementById('snize-preload-spinner');
                    if (spinner) {
                        document.getElementById('snize-preload-spinner').style.backgroundPosition = (- sXpos) + 'px center';
                    } else {
                        clearInterval(sInterval);
                    }
        
                    sXpos  += 32;
                    sIndex += 1;
        
                    if (sIndex >= 12) {
                        sXpos  = 0;
                        sIndex = 0;
                    }
                }, 30);
            }(window));
SCRIPT
			);

			return SE_TEMPLATES_PATH . 'smart-navigation.php';
		}

		return $template;
	}

	/**
	 * Add searchanise class to body
	 *
	 * @param array $classes Class list.
	 */
	public function add_body_class( $classes ) {
		if ( $this->is_navigation_page() ) {
			$classes[] = 'snize-navigation';
		}

		return $classes;
	}

	/**
	 * Param check if navigation page
	 */
	private function is_navigation_page() {
		return is_woocommerce() && is_product_category();
	}
}
