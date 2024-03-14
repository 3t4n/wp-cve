<?php
/**
 * Add support for Porto theme
 *
 * @since      2.0.0
 * @author     YITH
 * @package    YITH/Search
 */

class YITH_WCAS_Porto_Support {
	use YITH_WCAS_Trait_Singleton;

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	protected function __construct() {
		if (  ! yith_wcas_is_fresh_block_installation() && ! yith_wcas_user_switch_to_block()  ) {
			$this->init_legacy();
		}else{
			$this->init();
		}
	}

	/**
	 * Init the support
	 *
	 * @return void
	 */
	public function init() {
		$template = get_stylesheet_directory() . '/woocommerce/yith-woocommerce-ajax-search.php';
		if ( defined( 'PORTO_VERSION' ) && ( ! is_child_theme() || ( is_child_theme() && ! file_exists( $template ) ) ) ) {
			add_filter( 'woocommerce_locate_template', array( $this, 'override_porto_template' ), 99999, 2 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_porto_styles' ), 9999 );
		}
	}
	/**
	 * Init the legacy integration with Porto Theme
	 *
	 * @return void
	 */
	public function init_legacy() {
		$template = get_stylesheet_directory() . '/woocommerce/yith-woocommerce-ajax-search.php';
		if ( defined( 'PORTO_VERSION' ) && ( ! is_child_theme() || ( is_child_theme() && ! file_exists( $template ) ) ) ) {
			add_filter( 'woocommerce_locate_template', array( $this, 'override_legacy_porto_template' ), 99999, 2 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_porto_legacy_styles' ), 9999 );
			add_filter( 'yith_wcas_submit_as_input', '__return_false' );
			add_filter( 'yith_wcas_submit_label', array( $this, 'porto_submit_label' ) );
			add_filter( 'yith_wcas_porto_header', '__return_true' );
		}
	}


	/**
	 * Override the template path
	 *
	 * @param string $template The template path.
	 * @param string $template_name The template file name.
	 *
	 * @return string
	 * @since  1.8.4
	 */
	public function override_legacy_porto_template( $template, $template_name ) {
		if ( 'yith-woocommerce-ajax-search.php' === $template_name ) {
			$template = trailingslashit( YITH_WCAS_DIR . 'templates/' ) . $template_name;
		}

		return $template;
	}

	/**
	 * Override the template path
	 *
	 * @param string $template The template path.
	 * @param string $template_name The template file name.
	 *
	 * @return string
	 * @since  1.8.4
	 */
	public function override_porto_template( $template, $template_name ) {
		if ( 'yith-woocommerce-ajax-search.php' === $template_name ) {
			$template = trailingslashit( YITH_WCAS_DIR . 'templates/porto/' ) . 'porto.php';
		}

		return $template;
	}

	/**
	 * Set an icon in the label of the search button
	 *
	 * @return string
	 * @since  1.8.4
	 */
	public function porto_submit_label() {
		return '<i class="fas fa-search"></i>';
	}

	/**
	 * Enqueue styles for Porto theme
	 *
	 * @return void
	 * @since  1.8.4
	 */
	public function enqueue_porto_legacy_styles() {
		$b = porto_check_theme_options();

		$show_type       = get_option( 'yith_wcas_show_search_list' );
		$show_categories = get_option( 'yith_wcas_show_category_list' );
		$search_width    = 390;


		if ( ( 'yes' === $show_type && 'no' === $show_categories ) || ( 'no' === $show_type && 'yes' === $show_categories ) ) {
			$search_width = 260;
		} elseif ( 'yes' === $show_type && 'yes' === $show_categories ) {
			$search_width = 160;
		}

		ob_start();
		?>

        .searchform div.yith-ajaxsearchform-container,
        .searchform div.yith-ajaxsearchform-container .yith-ajaxsearchform-select {
        display: flex;
        }

        .searchform div.yith-ajaxsearchform-container .search-navigation {
        order: 1;
        flex:1!important;
        }

        .searchform div.yith-ajaxsearchform-container .yith-ajaxsearchform-select {
        order: 2;
        font-size: 0;
        }

        .searchform div.yith-ajaxsearchform-container #yith-searchsubmit {
        order: 3;
        }

        .searchform div.yith-ajaxsearchform-container input {
        border-right: <?php echo( ( 'simple' === $b['search-layout'] ) ? '1px solid ' . esc_html( $b['searchform-border-color'] ) . '!important' : 'none' ); ?>;
        width: <?php echo esc_attr( $search_width ); ?>px!important;
        max-width: 100%!important;
        }

        .searchform div.yith-ajaxsearchform-container > .yith-ajaxsearchform-select select {
        width: 130px!important;
        background-image: url(<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/images/select-bg.svg)!important;
        background-position-x: 96%!important;
        background-position-y: 49%!important;
        background-size: 26px 60px!important;
        background-repeat: no-repeat!important;
        background-attachment: initial!important;
        background-origin: initial!important;
        background-clip: initial!important;
        }

        .autocomplete-suggestions {
        margin-top: <?php echo( ( 'simple' === $b['search-layout'] || 'large' === $b['search-layout'] ) ? '15px' : 0 ); ?>;
        }

		<?php
		$custom_css = ob_get_clean();
		wp_add_inline_style( 'yith_wcas_frontend', $custom_css );
		ob_start()
		?>
        jQuery(
        function ( $ ) {
        $( document ).on(
        'yith_wcas_done',
        function () {
        $( '.search-toggle' ).addClass( 'opened' );
        $( '.searchform' ).show();
        }
        );
        }
        );
		<?php
		$custom_js = ob_get_clean();
		wp_add_inline_script( 'yith_wcas_frontend', $custom_js );

	}

	/**
	 * Fix style for mobile
	 *
	 * @return void
	 */
	public function enqueue_porto_styles() {
		$css = '@media screen and (max-width: 991px) { 
	            .searchform-popup .wp-block-yith-search-block {
	                display:none;
	            }
	            
	            .ywcas-search-mobile{
	            z-index:9999!important;
	            }
	            
	    }';

		$js  = "jQuery(function ($) {
                $('.wp-block-yith-search-block').addClass('searchform');
               
               $('.searchform-popup .search-toggle i').on('click',function(e){
                  $('.searchform-popup .wp-block-yith-search-block').addClass('searchform');
                           
               });

        })";

		wp_add_inline_style('ywcas-frontend', $css);
		wp_add_inline_script('ywcas-search-results-script', $js);
	}
}